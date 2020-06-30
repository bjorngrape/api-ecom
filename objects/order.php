
<?php

class Order {

    private $dbh;

    public function __construct($databasehandler)
    {
        $this->dbh = $databasehandler;
    }

    public function addOrder($product_id, $user_id) {

        $return_object = new stdClass();
        
        $mysql_time = date("Y-m-d H:i:s");

        $query = "INSERT INTO orders (product_id, user_id, date) VALUES (:product_id, :user_id, :date);";

            $sth = $this->dbh->prepare($query);

                if ($sth !== false) {

                    $sth->bindParam(':product_id', $product_id);
                    $sth->bindParam(':user_id', $user_id);
                    $sth->bindParam(':date', $mysql_time);

                    $sth->execute();

                    $return_object->state = "success";
                    $return_object->message = "Order created";

                } else {
                    $return_object->state = "error";
                    $return_object->message = "Something went wrong...";
                }
                
        return json_encode($return_object);
    }
}