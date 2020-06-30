<?php

class Product {

    private $dbh;
    private $product;

    public function __construct($databasehandler)
    {
        $this->dbh = $databasehandler;
    }

    public function addProduct($title, $description, $price, $quantity) {

        $return_object = new stdClass();

        if ($this->productTitleExists($title) === false) {
        
            $query = "INSERT INTO products (title, description, price, quantity) VALUES (:title, :description, :price, :quantity);";

            $sth = $this->dbh->prepare($query);

                if ($sth !== false) {

                    $sth->bindParam(':title', $title);
                    $sth->bindParam(':description', $description);
                    $sth->bindParam(':price', $price);
                    $sth->bindParam(':quantity', $quantity);

                    $sth->execute();

                    $return_object->state = "success";
                    $return_object->name = $title;
                    $return_object->message = "is now added to database";

                } else {
                    $return_object->state = "error";
                    $return_object->message = "Something went wrong...";
                }
        } else {

            $return_object->state = "error";
            $return_object->message = "Product already exists in database";

        }

        return json_encode($return_object);
    }

    private function productTitleExists($title) {

        $return_object = new stdClass();

        $query = "SELECT COUNT(id) FROM products WHERE title = :title";
        $sth = $this->dbh->prepare($query);

            if ($sth !== false) {

            $sth->bindParam(':title', $title);
            $sth->execute();

            $numberOfProducts = $sth->fetch()[0];

                if ($numberOfProducts > 0) {
                    $return_object->state = "error";
                    $return_object->message = "Product already exists in database";
                    return true;

                } else {
                    $return_object->state = "success";
                    $return_object->message = "Yay!";
                    return false;
                }

            } else {
                $return_object->state = "error";
                $return_object->message = "Something went wrong...";
            }

        return json_encode($return_object);
    }

    public function fetchAll()
    {
        $return_object = new stdClass();

        $query = "SELECT id, title, description, price, quantity FROM products;";

        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {

            $sth->execute();

            $return_array = $sth->fetchAll(PDO::FETCH_ASSOC);
            $this->products = $return_array;

            if(!empty ($return_array) ) {
                $return_object->state = "success";
                $return_object->message = "Here is all of our products";
            }

        } else {
            $return_object->state = "error";
            $return_object->message = "Something went wrong...";
        }

        return json_encode($return_object);
    }

    public function fetchProduct()
    {
        return $this->product;
    }

    public function deleteProduct($productID)
    {
        $return_object = new stdClass();
        $query = "DELETE FROM products WHERE id= :productID";
        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {

            $sth->bindParam(':productID', $productID);
            $sth->execute();

            $return_object->state = "success";
            $return_object->message = "The product is deleted from database";

        } else {
            $return_object->state = "error";
            $return_object->message = "Something went wrong...";
        }

        return json_encode($return_object);
    }

    private $editProduct;

    public function getEditProduct($productID)
    {
        $return_object = new stdClass();

        $query = "SELECT id, title, description, price, quantity FROM products WHERE id= :productID;";

        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {

            $sth->bindParam(':productID', $productID);
            $sth->execute();

            $return_array = $sth->fetchAll(PDO::FETCH_ASSOC);
            $this->editProduct = $return_array;

            $return_object->state = "success";
            $return_object->message = "Here is your product";   
        } else {

            $return_object->state = "error";
            $return_object->message = "Something went wrong...";
        }
    
        return json_encode($return_object);
    }

    public function fetchEditProduct()
    {
        return $this->editProduct;
    }

    public function editProduct($title, $description, $price, $quantity, $productid)
    {
        $return_object = new stdClass();

        $query = "UPDATE products SET title = :title, description = :description, price = :price, quantity = :quantity WHERE Id = :productid; ";
        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {
            $sth->bindParam(':title', $title );
            $sth->bindParam(':description', $description);
            $sth->bindParam(':price', $price);
            $sth->bindParam(':quantity', $quantity);
            $sth->bindParam(':productid', $productid);

            $sth->execute();

            $return_object->state = "success";
            $return_object->message = "Product updated";

        } else {

            $return_object->state = "error";
            $return_object->message = "Something went wrong...";
        }
        
        return json_encode($return_object);
    }


    public function searchProduct($searchQ) {

        $return_object = new stdClass();
        
        $query = "SELECT id, title, description, price, quantity FROM products WHERE title LIKE :searchQ OR description LIKE :searchQ;";

        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {
        $queryParam = '%'. $searchQ . '%';
        $sth->bindParam(':searchQ', $queryParam);

        $return_array = $sth->execute();
        
        $return_array = $sth->fetchAll(PDO::FETCH_ASSOC);
        $this->products = $return_array;

        } else {

            $return_object->state = "error";
            $return_object->message = "Something went wrong...";
        }
        
        return json_encode($return_object);
    }
}