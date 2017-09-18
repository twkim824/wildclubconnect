<?php
    include_once 'database_session/session.php';
    include 'includes/classes/Club.php';
    
    $email = $_SESSION['email'];
    $email_length = strlen($email);
    $username = substr($email, 0, $email_length - 19);

    $connection = mysqli_connect('localhost', 'root', '','wildclubconnect');
    $sqlQuery = mysqli_query($connection, "SELECT club1 FROM users WHERE username = '$username'");
    $result1 = mysqli_fetch_array($sqlQuery)[0];
    $result1_link = "<a class='one' href=clubpage.php?id=" . "$result1" . ">" . $result1 . "</a>";

    $sqlQuery = mysqli_query($connection, "SELECT club2 FROM users WHERE username = '$username'");
    $result2 = mysqli_fetch_array($sqlQuery)[0];
    $result2_link = "<a class='one' href=clubpage.php?id=" . "$result2" . ">" . $result2 . "</a>";

    $sqlQuery = mysqli_query($connection, "SELECT club3 FROM users WHERE username = '$username'");
    $result3 = mysqli_fetch_array($sqlQuery)[0];
    $result3_link = "<a class='one' href=clubpage.php?id=" . "$result3" . ">" . $result3 . "</a>";

    function deleteClub($clubName, $clubAdmin, $clubnumber) { //clubnumber = club1 or club2 or club3
        $email = $_SESSION['email'];
        $email_length = strlen($email);
        $username = substr($email, 0, $email_length - 19);
        
        $connection = mysqli_connect('localhost','root','','wildclubconnect');
        // Delete club from clubs database
        $sql = "DELETE FROM clubs WHERE clubName = '$clubName'";
        if(mysqli_query($connection, $sql)) {
            echo "Club deleted in database.";
        } else {
            echo "Error deleting club in database. Please contact website administrator: " . mysqli_error($connection);
        }
        
        // Update users club to 0
        $sql2 = "UPDATE users SET $clubnumber = 0 WHERE username = '$clubAdmin'";
        if(mysqli_query($connection, $sql2)) {
            echo "Club deleted in user database.";
        } else {
            echo "Error deleting club in user database. Please contact website administrator: " . mysqli_error($connection);
        }
                
        // Decrement users clubnumber and put back into clubnumber
        $sql3 = "SELECT clubnumber FROM users WHERE username = '$username'";
        $result = mysqli_query($connection, $sql3);
        while ($row = mysqli_fetch_assoc($result)) {
            $clubnumber = $row['clubnumber'];
        }
        $clubnumber = $clubnumber - 1;
        
        $sql4 = "UPDATE users SET clubnumber = '$clubnumber' WHERE username = '$username'";
        if(mysqli_query($connection, $sql4)) {
            echo "Clubnumber successfully decremented.";
        } else {
            echo "Error decrementing user clubnumber. Please contact website administrator: " . mysqli_error($connection);
        }
    }

    if(isset($_POST['delete1'])) {
        
        deleteClub($result1, $username, 'club1');
        header('location: profile.php');
    }

    if(isset($_POST['delete2'])) {
        
        deleteClub($result2, $username, 'club2');
        header('location: profile.php');

    }

    if(isset($_POST['delete3'])) {
        
        deleteClub($result3, $username, 'club3');
        header('location: profile.php');

    }

    $querystring = $_SERVER['QUERY_STRING'];
    $profile = "";
    if($querystring) {
        $querystringlen = strlen($querystring);
        $querystring2 = substr($querystring, 3, $querystringlen - 1); // gets everything after php?id=
        $profile = str_replace("%20"," ",$querystring2);
    }

?>



<!DOCTYPE html>

<html>

	<head>
		<title> WildClub Connect </title>
		<link rel="stylesheet" type="text/css" href="index.css">

		<style type="text/css">



        table, tr, td, th {
            border:none;
        }
            
        .username {
            color:white;
			font-size: 100%;
			font-family: tahoma;	
			float:left;
			padding: 15px;
			background-color: #62179B;
			margin-right: 10%;
			transition-duration: 0.4s;
        }
        
            #bottomhalf {
                width: 100%;
                height: 100%;
            }  
            
            #individualC {
                height: 500px;
                float:left;
                display:inline-block;
                width: 25%;
                color: black;
                background-color: #EFEFEF;
            }
            
		</style>

	</head>



	<body>

		<div id="topbar">
            <div id="WildClubs"> <a class="two" href="index.php"> WildClub Connect </a> </div>
			<a href="signout.php"> <div class="signin_button"> Sign out </div> </a>
			<span class="signup_button"> <a class="two" href="why.php" class="why"> Site Information </a> </span> 
		</div>

		<div id="background"> <?php if(!$profile) {echo $username;} else {echo $profile;} ?> </div>
		
		<div id='bottomhalf'>
		
		<?php if(!$profile): ?>
		
            <div id='individualC'>
                <?php if($result1 != '0'): ?>	
                <?php echo $result1_link; echo " (Rating: "; getClubRating($result1); echo ")"; ?> 
                <form action="profile.php" method="post"> 
                    <input type='submit' name='edit1' value='Edit'> <?php if(isset($_POST['edit1'])){header("location:editclub.php?id=$result1");} ?>
                    <input type="submit" name="delete1" value="Delete"> 
                </form> <br>
                
                Pending membership requests: <br>
                
                <?php
                    $sql = "SELECT * FROM joinrequests WHERE clubName = '$result1'";
                    $data = mysqli_query($connection, $sql);
                
                    if(mysqli_num_rows($data) > 0) {
                        while ($row = mysqli_fetch_array($data)) {
                            $requester = $row['requester'];
                            $linktoprofile = "<a class='one' style='font-size:12px' href='profile.php?id=" . $requester . "'>" . $requester . "</a>";
                            echo $linktoprofile;
                            echo "<form method='post' action='accept.php?id=$requester" . "_$result1'" . "> 
                            <select style='display:none'><option value='$result1' name='result1'></option></select>
                            <input type='submit' name='accept' value='Confirm'> <input type='submit' name='reject' value='Reject'>
                            </form> <br>";
                        }
                    }
                
                ?>
                
                <?php endif ?>
                
                <?php 
                    if($result1 == '0') {
                        echo "Empty slot";
                    }
                ?>
                
            </div>
            
            <div id='individualC'>
                <?php if($result2 != '0'): ?>	
                <?php echo $result2_link; echo " (Rating: "; getClubRating($result2); echo ")"; ?> 
                <form action="profile.php" method="post"> 
                <input type='submit' name='edit2' value='Edit'> <?php if(isset($_POST['edit2'])){header("location:editclub.php?id=$result2");} ?>
                <input type="submit" name="delete2" value="Delete">
                </form> <br>
                
                Pending membership requests: <br>
                
                <?php
                    $sql = "SELECT * FROM joinrequests WHERE clubName = '$result2'";
                    $data = mysqli_query($connection, $sql);
                
                    if(mysqli_num_rows($data) > 0) {
                        while ($row = mysqli_fetch_array($data)) {
                            $requester = $row['requester'];
                            $linktoprofile = "<a class='one' style='font-size:12px;' href='profile.php?id=" . $requester . "'>" . $requester . "</a>";
                            echo $linktoprofile;
                            echo "<form method='post' action='accept.php?id=$requester" . "_$result2'" . "> 
                            <select style='display:none'><option value='$result2' name='result2'></option></select>
                            <input type='submit' name='accept' value='Confirm'> <input type='submit' name='reject' value='Delete'>
                            </form> <br>";
                        }
                    }
                
                ?>
                
                <?php endif ?>
                
                <?php 
                    if($result2 == '0') {
                        echo "Empty slot";
                    }
                ?>
                
            </div>

            <div id='individualC'>
                <?php if($result3 != '0'): ?>	
                <?php echo $result3_link; echo " (Rating: "; getClubRating($result3); echo ")"; ?> 
                <form action="profile.php" method="post"> 
                    <input type='submit' name='edit3' value='Edit'> <?php if(isset($_POST['edit3'])){header("location:editclub.php?id=$result3");} ?>
                    <input type="submit" name="delete3" value="Delete"> 
                </form> <br>
                
                Pending membership requests: <br>
                
                <?php
                    $sql = "SELECT * FROM joinrequests WHERE clubName = '$result3'";
                    $data = mysqli_query($connection, $sql);
                
                    if(mysqli_num_rows($data) > 0) {
                        while ($row = mysqli_fetch_array($data)) {
                            $requester = $row['requester'];
                            $linktoprofile = "<a class='one' style='font-size: 12px;' href='profile.php?id=" . $requester . "'>" . $requester . "</a>";
                            echo $linktoprofile;
                            echo "<form method='post' action='accept.php?id=$requester" . "_$result3'" . "> 
                            <select style='display:none'><option value='$result3' name='result3'></option></select>
                            <input type='submit' name='accept' value='Confirm'> <input type='submit' name='reject' value='Delete'>
                            </form> <br>";
                        }
                    }
                
                ?>
                
                <?php endif ?>
                
                <?php 
                    if($result3 == '0') {
                        echo "Empty slot";
                    }
                ?>
                
            </div>
            
            <div id='individualC'>
                Membership <br>
                
                <?php
                
                    $sql = "SELECT * FROM members WHERE member = '$username'";
                    $result = mysqli_query($connection, $sql);
                
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result)) {
                            $clubName = $row['clubName'];
                            $linktoclub = "<a class='one' href='clubpage.php?id=$clubName'>$clubName</a>";
                            echo $linktoclub . '<br>';
                        }
                    }
                
                ?>
                
            </div>

            <?php if(!$result1 && !$result2 && !$result3) {echo "You are not admin of any clubs.";} ?>
            
            <?php else: ?>
            
            <?php echo "Asdf" ?>
            
            <?php endif ?>

        </div>


	</body>
</html>










