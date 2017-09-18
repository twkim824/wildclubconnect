<?php

    include "database_session/Database.php";
    
    class Club {
        private $clubAdmin;
        private $connection;

        public function __construct ($connection, $clubAdmin) {
            $this->connection = $connection;
            $user_details_query = mysqli_query($connection, "SELECT * FROM clubs WHERE clubAdmin='clubAdmin'");
            $this->clubAdmin = mysqli_fetch_array($user_details_query);
        }

        public function submitClub($clubAdmin, $clubName, $clubDescription, $clubCategory, $clubTime, $clubStatus, $username) {
            
            $clubDescription = strip_tags($clubDescription); // removes html tags
            $clubDescription = mysqli_real_escape_string($this->connection, $clubDescription);
            $check_empty = preg_replace('/\s+/','',$clubDescription); // deletes all spaces
            
            
            $clubName = strip_tags($clubName); // removes html tags
            $clubName = mysqli_real_escape_string($this->connection, $clubName);
            $check_empty_2 = preg_replace('/\s+/','',$clubName); // deletes all spaces

            // checking if the user already has started 3 clubs -----------------------------------------
            $connection = mysqli_connect('localhost', 'root', '','wildclubconnect');
            $sqlQuery = mysqli_query($connection, "SELECT clubnumber FROM users WHERE username = '$clubAdmin'");
            $clubnumbervalue = mysqli_fetch_array($sqlQuery)[0];
            
            if ($clubnumbervalue == 3) {
                
                echo "*You are not allowed to be admin of more than three clubs.";

            } else {
                                
                        if ($check_empty != "" && $check_empty_2 != "") {

                            // Insert club into database
                            $query = mysqli_query($this->connection, "INSERT INTO clubs VALUES('', '$clubName', '$clubDescription', '$clubAdmin','$clubCategory','','','$clubTime','$clubStatus')");
                            $returned_id = mysqli_insert_id($this->connection);

                            $clubnumbervalue++;

                            // Increment the club count for the user in the users database
                            $query = mysqli_query($this->connection, "UPDATE users SET clubnumber='$clubnumbervalue' WHERE username = '$clubAdmin'");
                            $returned_id = mysqli_insert_id($this->connection);
                            
                            // Add the name of the submitted club to the users database
                            
                            
                            $connection = mysqli_connect('localhost', 'root', '','wildclubconnect');
                            $sqlQuery = mysqli_query($connection, "SELECT club1 FROM users WHERE username = '$clubAdmin'");
                            $result1 = mysqli_fetch_array($sqlQuery)[0];
                            $sqlQuery2 = mysqli_query($connection, "SELECT club2 FROM users WHERE username = '$clubAdmin'");
                            $result2 = mysqli_fetch_array($sqlQuery2)[0];
                            
                            if (!$result1) {
                                $query = mysqli_query($this->connection, "UPDATE users SET club1='$clubName' WHERE username = '$clubAdmin'");
                                $returned_id = mysqli_insert_id($this->connection);
                            }
                            
                            elseif (!$result2) {
                                $query = mysqli_query($this->connection, "UPDATE users SET club2='$clubName' WHERE username = '$clubAdmin'");
                                $returned_id = mysqli_insert_id($this->connection);
                            }
                            
                            else {
                                $query = mysqli_query($this->connection, "UPDATE users SET club3='$clubName' WHERE username = '$clubAdmin'");
                                $returned_id = mysqli_insert_id($this->connection);
                            }
                            
                            

                        } else {
                            echo "*Unsuccessful. You must fill in all fields.";
                            }
                }
            
            

        }
        


    }

    function getClubRating($clubName) {
        if ($clubName != '0') {
            $connection = mysqli_connect('localhost', 'root', '','wildclubconnect');
            $sql = "SELECT clubRatingCount, clubRatingSum FROM clubs WHERE clubName = '$clubName'";
            $clubresult = mysqli_query($connection, $sql);
            while($row = mysqli_fetch_assoc($clubresult)) {
                $clubRatingSum = $row['clubRatingSum'];
                $clubRatingCount = $row['clubRatingCount'];
                if ($clubRatingCount > 0) {
                    $clubRating = number_format(round($clubRatingSum / $clubRatingCount,2), 2, '.','');

                } else {
                    $clubRating = "No ratings yet";
                }
            }
            echo $clubRating;
        }
    }

?>