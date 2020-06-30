<?php

class Cart {

    private $dbh;
    private $cart;

    public function __construct($databasehandler)
    {
        $this->dbh = $databasehandler;
    }

    public function addProduct2Cart($token_id, $book) {

        $return_object = new stdClass();

        $query = "INSERT INTO cart (token_id, product_id) VALUES (:token_id, :book);";

            $sth = $this->dbh->prepare($query);

                if ($sth !== false) {

                    $sth->bindParam(':token_id', $token_id);
                    $sth->bindParam(':book', $book);
            
                    $sth->execute();

                    $return_object->state = "success";
                    $return_object->message = "Added to cart";

                } else {
                    $return_object->state = "error";
                    $return_object->message = "Something went wrong...";
                }
       
        return json_encode($return_object);
    }

    public function viewCart($token_id)
    {
        $return_object = new stdClass();

        $query = "SELECT cart.id, products.title, products.price, tokens.token FROM cart 
        JOIN products ON product_id = products.id JOIN tokens ON token_id = tokens.id 
        WHERE tokens.id LIKE :token_id";

        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {

            $sth->bindParam(':token_id', $token_id);
            $sth->execute();

            $return_array = $sth->fetchAll(PDO::FETCH_ASSOC);
            $this->cart = $return_array;

            if(!empty ($return_array) ) {

            $return_object->state = "success";
            $return_object->message = "Here is your cart";

            } else {
                $return_object->state = "error";
                $return_object->message = "Your cart is empty";
        
            }

        } else {

            $return_object->state = "error";
            $return_object->message = "Something went wrong...";

        }

        return json_encode($return_object);

    }

    public function fetchCart()

    {
        return $this->cart;
    }


    public function removeFromCart($cartid)
    {
        $return_object = new stdClass();

        $query = "DELETE FROM cart WHERE id= :cartid";

        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {

            $sth->bindParam(':cartid', $cartid);
            $sth->execute();

            $return_object->state = "success";
            $return_object->message = "Item removed from cart";

        } else {
            $return_object->state = "error";
            $return_object->message = "Something went wrong...";

        }

        return json_encode($return_object);
    }

    private $cart_id;

    public function setCartId($cart_id_in)
    {
        $this->cart_id = $cart_id_in;
    }

    public function fetchCartID()
    {

        $return_object = new stdClass();

        $query = "SELECT id, product_id FROM cart WHERE id= :cartid";
        
        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {

            $sth->bindParam(':cartid', $this->cart_id);
            $sth->execute();

            return $sth->fetch();

        } else {
            $return_object->state = "error";
            $return_object->message = "Something went wrong...";

        }

        return json_encode($return_object);
    }

}