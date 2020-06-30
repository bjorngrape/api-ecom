<?php

class Product {

    private $dbh;
    private $books;

    public function __construct($databasehandler)
    {
        $this->dbh = $databasehandler;
    }

    public function addProduct($title, $content, $price, $qty) {

        $return_object = new stdClass();

        if ($this->bookTitleExists($title) === false) {
        
            $query = "INSERT INTO products (title, descrip, price, quantity) VALUES (:title, :content, :price, :qty);";

            $sth = $this->dbh->prepare($query);

                if ($sth !== false) {

                    $sth->bindParam(':title', $title);
                    $sth->bindParam(':content', $content);
                    $sth->bindParam(':price', $price);
                    $sth->bindParam(':qty', $qty);

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
            $return_object->message = "Book already exists in database";

        }

        return json_encode($return_object);
    }

    private function bookTitleExists($title) {

        $return_object = new stdClass();

        $query = "SELECT COUNT(id) FROM products WHERE title = :title";
        $sth = $this->dbh->prepare($query);

            if ($sth !== false) {

            $sth->bindParam(':title', $title);
            $sth->execute();

            $numberOfbooks = $sth->fetch()[0];

                if ($numberOfbooks > 0) {
                    $return_object->state = "error";
                    $return_object->message = "Book already exists in database";
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

        $query = "SELECT id, title, descrip, price, quantity FROM products;";

        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {

            $sth->execute();

            $return_array = $sth->fetchAll(PDO::FETCH_ASSOC);
            $this->books = $return_array;

            if(!empty ($return_array) ) {
                $return_object->state = "success";
                $return_object->message = "Here is all our books";
            }

        } else {
            $return_object->state = "error";
            $return_object->message = "Something went wrong...";
        }

        return json_encode($return_object);
    }

    public function fetchBook()
    {
        return $this->books;
    }

    public function deleteBook($bookID)
    {
        $return_object = new stdClass();
        $query = "DELETE FROM products WHERE id= :bookID";
        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {

            $sth->bindParam(':bookID', $bookID);
            $sth->execute();

            $return_object->state = "success";
            $return_object->message = "The book is deleted from db";

        } else {
            $return_object->state = "error";
            $return_object->message = "Something went wrong when trying to connect to db";
        }

        return json_encode($return_object);
    }

    private $editbook;

    public function getEditBook($bookID)
    {
        $return_object = new stdClass();

        $query = "SELECT id, title, descrip, price, quantity FROM products WHERE id= :bookID;";

        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {

            $sth->bindParam(':bookID', $bookID);
            $sth->execute();

            $return_array = $sth->fetchAll(PDO::FETCH_ASSOC);
            $this->editbook = $return_array;

            $return_object->state = "success";
            $return_object->message = "Here is your book";   
        } else {

            $return_object->state = "error";
            $return_object->message = "Something went wrong when trying to connect to db";
        }
        

        return json_encode($return_object);
        
    }

    public function fetchEditBook()
    {
        return $this->editbook;
    }

    public function editBook($title, $descrip, $price, $qty, $bookid)
    {
        $return_object = new stdClass();

        $query = "UPDATE products SET title = :title, descrip = :descrip, price = :price, quantity = :qty WHERE Id = :bookid; ";
        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {
            $sth->bindParam(':title', $title );
            $sth->bindParam(':descrip', $descrip);
            $sth->bindParam(':price', $price);
            $sth->bindParam(':qty', $qty);
            $sth->bindParam(':bookid', $bookid);

            $sth->execute();

            $return_object->state = "success";
            $return_object->message = "Book is updated";

        } else {

            $return_object->state = "error";
            $return_object->message = "Something went wrong when trying to connect to db";
        }
        
        return json_encode($return_object);
    }


    public function searchBook($searchQ) {

        $return_object = new stdClass();
        
        $query = "SELECT id, title, descrip, price, quantity FROM products WHERE title LIKE :searchQ OR descrip LIKE :searchQ;";

        $sth = $this->dbh->prepare($query);

        if ($sth !== false) {
        $queryParam = '%'. $searchQ . '%';
        $sth->bindParam(':searchQ', $queryParam);

        $return_array = $sth->execute();
        
        $return_array = $sth->fetchAll(PDO::FETCH_ASSOC);
        $this->books = $return_array;

        } else {

            $return_object->state = "error";
            $return_object->message = "Something went wrong when trying to connect to db";
        }
        
        return json_encode($return_object);
    }
       
    
}