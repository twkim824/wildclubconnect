<?php

            session_start();

            if (isset ($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $email_length = strlen($email);
                $username = substr($email, 0, $email_length - 19);
            }     
    
            if(isset($_POST['makeadmin'])) {
                $connection = mysqli_connect('localhost', 'root', '','wildclubconnect');

                $querystring = $_SERVER['QUERY_STRING'];
                $underscorepos = strpos($querystring, '_');            
                $querystringlen = strlen($querystring);

                $coadmin = substr($querystring, 3, $underscorepos - 3);
                $clubName = substr($querystring, $underscorepos + 1);
                $clubName = str_replace("%20"," ",$clubName);

                $sql = "UPDATE clubs SET clubAdmin = '$coadmin' WHERE clubName = '$clubName'";
                mysqli_query($connection, $sql);
                
                $sql = "DELETE FROM coadmins WHERE clubName = '$clubName' AND coadmin = '$coadmin'";
                mysqli_query($connection, $sql);

                $sql = "INSERT INTO coadmins VALUES ('','$clubName', '$username')";
                mysqli_query($connection, $sql);
            } else {
                echo "ERROR";
            }

            header('location:clubpage.php?id=$clubName');

?>