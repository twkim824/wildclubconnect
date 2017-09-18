<?php
            $querystring = $_SERVER['QUERY_STRING'];
            $underscorepos = strpos($querystring, '_');            
            $querystringlen = strlen($querystring);
            
            $requester = substr($querystring, 3, $underscorepos - 3);
            $clubName = substr($querystring, $underscorepos + 1);
            $clubName = str_replace("%20"," ",$clubName);


            $connection = mysqli_connect('localhost', 'root', '','wildclubconnect');

        if(isset($_POST['accept'])) {
            $sql = "INSERT INTO members VALUES ('','$clubName','$requester')";
            mysqli_query($connection, $sql); 
            
            if (isset ($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $email_length = strlen($email);
                $username = substr($email, 0, $email_length - 19);
            } 
            
            $sql = "DELETE FROM joinrequests WHERE requester = '$requester' AND clubName = '$clubName'";
            mysqli_query($connection, $sql);
            
            header('location: profile.php');
            
        }

        if (isset($_POST['reject'])) {
            $sql = "DELETE FROM joinrequests WHERE requester = '$requester' AND clubName = '$clubName'";
            mysqli_query($connection, $sql);
            
            header('location: profile.php');
        }

?>