<?php

class User {
    private $username;
    private $con;
    
    public function __construct ($con, $user) {
        $this->con = $con;
        $user_details_query = mysqli_query($con, "SELECTION * FROM users WHERE username='username'");
        $this->username = mysqli_fetch_array($user_details_query);
    }
}

?>
