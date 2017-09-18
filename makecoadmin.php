<?php

            if(isset($_POST['makecoadmin'])) {

                $connection = mysqli_connect('localhost', 'root', '','wildclubconnect');

                $querystring = $_SERVER['QUERY_STRING'];
                $underscorepos = strpos($querystring, '_');            
                $querystringlen = strlen($querystring);

                $member = substr($querystring, 3, $underscorepos - 3);
                $clubName = substr($querystring, $underscorepos + 1);
                $clubName = str_replace("%20"," ",$clubName);

                $sql = "INSERT INTO coadmins VALUES ('','$clubName', '$member')";
                mysqli_query($connection, $sql);

                $sql = "DELETE FROM members WHERE clubName = '$clubName' AND member = '$member'";
                mysqli_query($connection, $sql);
            }

?>