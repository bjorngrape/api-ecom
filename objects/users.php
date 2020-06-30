<?php

class User {
    
    private $dbh;
    public $uniq_username;
    public $user_id;
    private $token_validity_time = 60; // minutes

    public function __construct($databasehandler)
    {
        $this->dbh = $databasehandler;
    }

    public function signUp($username, $password) {

        $return_object = new stdClass();

        if ($this->isUsernameTaken($username) === false) {
            

              $return = $this->insertUser($username, $password);

              if($return !== false) {
                  $return_object->state = "success";
                  $return_object->user = $return;
              } else {
                $return_object->state = "error";
                $return_object->message = "Something went wrong when trying to INSERT user";

              }

        } else {
            $return_object->state = "error";
            $return_object->message = "username e upptagen";
        }

        return json_encode($return_object);

    }

    private function insertUser($username, $password) {

        $query_string = "INSERT INTO users (username, password) VALUES (:username, :password);";

        $sth = $this->dbh->prepare($query_string);

        if ($sth !== false) {

            $sth->bindParam(':username', $username);
            $sth->bindParam(':password', $password);
            $sth->execute();
        
            $last_inserted_user_id = $this->dbh->lastInsertId();

            $query_string = "SELECT id, username, password FROM users WHERE id= :last_user_id";

            $sth = $this->dbh->prepare($query_string);

            $sth->bindParam(':last_user_id', $last_inserted_user_id);
            $sth->execute();

            return $sth->fetch();

        } else {

            return false;
        }
    }

    private function isUsernameTaken($username) {

        $return_object = new stdClass();

        $query_string = "SELECT COUNT(id) FROM users WHERE username = :username";
        $sth = $this->dbh->prepare($query_string);

            if ($sth !== false) {

            $sth->bindParam(':username', $username);
            $sth->execute();

            $numberOfusers = $sth->fetch()[0];

                if ($numberOfusers > 0) {
                    $return_object->state = "error";
                    $return_object->message = "Username already exists";
                    return true;
                } else {
                    $return_object->state = "success";
                    $return_object->message = "All good, carry on";
                    return false;
                }

        } else {
            $return_object->state = "error";
            $return_object->message = "Something went wrong when trying to connect to db";
        }

        return json_encode($return_object);

    }

    public function LogIn($username, $password) {

        $return_object = new stdClass();

        $query_string = "SELECT id, username FROM users WHERE username = :username AND password = :password";

        $sth = $this->dbh->prepare($query_string);

        if ($sth !== false) {

            $sth->bindParam(':username', $username);
            $sth->bindParam(':password', $password);
            $sth->execute();
            $return = $sth->fetch();

            if (!empty($return)) {

                $this->uniq_username = $return['username'];
                $this->user_id = $return['id'];
            
                $return_object->state = "success";
                $return_object->token = $this->getToken($this->user_id);
                $return_object->message = "You are logged in";
                
                
            } else {

                $return_object->state = "error";
                $return_object->message = "Incorrect username or password";

            }

        } else {

            $return_object->state = "error";
            $return_object->message = "Something went wrong when trying to connect to db";

        }

        return json_encode($return_object);
    }


    public function getToken($userID) {

        
        $token = $this->checkToken($userID);

        return $token;
    }

    public function viewToken($token){

        $return_object = new stdClass();

        $query_string = "SELECT id, token, user_id  FROM tokens WHERE token = :token";
        $sth = $this->dbh->prepare($query_string);


            if ($sth !== false) {

                $sth->bindParam(':token', $token);
                $sth->execute();
                $return = $sth->fetch();
            
                if(!empty($return)) {

                    return $return;

                }

            } else {

                $return_object->state = "error";
                $return_object->message = "Something went wrong when trying to connect to db";
            }

        return json_encode($return_object);
    }



    private function checkToken($userID) {

        $return_object = new stdClass();

        $query_string = "SELECT token, date_updated FROM tokens WHERE user_id = :userid";
        $sth = $this->dbh->prepare($query_string);


            if ($sth !== false) {

            $sth->bindParam(':userid', $userID);
            $sth->execute();
            $return = $sth->fetch();

            if(!empty($return['token'])) {

                $token_timestamp = $return['date_updated'];
                $diff = time() - $token_timestamp;

                if(($diff / 60) > $this->token_validity_time) {

                    $query_string = "DELETE FROM tokens WHERE user_id=:userID";
                    $sth = $this->dbh->prepare($query_string);

                    $sth->bindParam(':userID', $userID);
                    $sth->execute();

            
                    $return_object->state = "success";
                    $return_object->message = "You got a token";

                    return $this->createToken($userID);

                } else {
                    $return_object->state = "success";
                    $return_object->message = "token updated";

                    return $return['token'];
                }
            } else {

                $return_object->state = "success";
                $return_object->message = "You got a token";

                return $this->createToken($userID);
            }

        } else {

            $return_object->state = "error";
            $return_object->message = "Something went wrong when trying to connect to db";
        }

        return json_encode($return_object);
    }


    private function createToken($userID) {

        $return_object = new stdClass();

        $uniqToken = md5($this->uniq_username.uniqid('', true).time());

        $query_string = "INSERT INTO tokens (user_id, token, date_updated) VALUES (:userid, :token, :current_time)";
        $sth = $this->dbh->prepare($query_string);

            if ($sth !== false) {

                $currentTime = time();
                $sth->bindParam(':userid', $userID);
                $sth->bindParam(':token', $uniqToken);
                $sth->bindParam(":current_time", $currentTime, PDO::PARAM_INT);
                $sth->execute();

                return $uniqToken;
                
            } else {
                $return_object->state = "error";
                $return_object->message = "Something went wrong when trying to connect to db";
            }

        return json_encode($return_object);
    }



    public function validateToken($token) {

        $return_object = new stdClass();

        $query_string = "SELECT user_id, date_updated, token FROM tokens WHERE token = :token";
        $sth = $this->dbh->prepare($query_string);
        
        if ($sth !== false) {

            $sth->bindParam(':token', $token);
            $sth->execute();
            $token_data = $sth->fetch();

            if (!empty($token_data['date_updated']) ) {

                $diff = time() - $token_data['date_updated'];

                if( ($diff / 60) < $this->token_validity_time ) {

                $query_string = "UPDATE tokens SET date_updated = :updated_date WHERE token = :token";
                $sth = $this->dbh->prepare($query_string);

                $updatedDate = time();
                $sth->bindParam(':updated_date', $updatedDate, PDO::PARAM_INT);
                $sth->bindParam(':token', $token);

                $sth->execute();

                $return_object->state = "success";
                $return_object->message = "All good here, carry on";

                return true;

                } else {
                    $return_object->state = "error";
                    $return_object->message = "Time has run out";
                    return false;
                }

            } else {
                $return_object->state = "error";
                $return_object->message = "Please login";
                return false;
            }


        } else {

            $return_object->state = "error";
            $return_object->message = "Something went wrong when trying to connect to db";

            return false;
    
        }

        return json_encode($return_object);

    }

    

}