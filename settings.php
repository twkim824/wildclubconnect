<?php
    include 'database_session/session.php';
    include 'includes/db.php';
    include 'includes/classes/Post.php';
    include 'includes/classes/Club.php';

    if (isset ($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $email_length = strlen($email);
    $username = substr($email, 0, $email_length - 19);
        
        header('Cache-Control: no cache'); //no cache

    session_cache_limiter('must-revalidate');
    } 
     

?>



<!DOCTYPE html>

<html>

	<head>
		<title> WildClub Connect </title>
		<link rel="stylesheet" type="text/css" href="index.css">

		<style type="text/css">
            

            
        body, html {
            height:100%;
            padding:0;
            margin:0;
        }
            
		a.one:link {
			color:black;
			text-decoration:none;
		}

        a.one:hover {
			text-decoration: underline;
		}

		a.one:visited {
			color:black;
			text-decoration:none;
		}
            
        a.two {
			color:white;
			text-decoration:none;
		}

        a.two:hover {
			text-decoration: underline;
		}

		a.two:visited {
			color:white;
			text-decoration:none;
		}

            
        #formdiv {
            background-color: white;
            color: black;
            width: 90%;
            height: 10%;
            display:none;
            margin: auto;
        }

        .button {
            width: 100%;
            height: 7%;
            line-height: 40px;
            vertical-align: middle;
            background-color: #62179B;
            border: none;
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            margin:auto;
            cursor: pointer;
            font-weight: bold;
            transition-duration: 0.4s;
            }

        .button:hover {
            background-color: #45106D;
        }

            #passwordsection {
                display: inline-block;
                width: 50%;
                background-color: #62179B;
                color: white;
                text-align: center;
                outline: 2px solid white;
                height: 50%;
            }
            
            #deactivatesection {
                width: 50%;
                display:block;
                float:right;
                background-color:#62179B;
                color: white;
                text-align:center;
                outline: 2px solid white;
                height: 50%;
            }
            
            td {
                text-align: right;
            }
            
            #title {
                font-size: 50px;
            }
            
            
            #passwordtable {
                margin: auto;
            }
            
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            padding-top:100px;
            overflow-y:scroll;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
            
        .modal-content {
            background-color: white;
            margin:auto;
            padding: 20px;
            width: 20%;
        }
            
            
		</style>

	</head>



	<body>

        <div class='modal' id='modal'> 
            <div class='modal-content'>
               <form method='post' action='searchusers.php' style='display:inline-block'> <input type='text' style='width:100%; height: 100%;' placeholder='Search for users...' name='usersearched'> <input type='submit' name='searchusers' value='Search' style='display:none'> </form> 
            <span class='close' onclick='showusers()'>X</span>
             <br> <br>
             
            </div>
        </div>

		<div id="topbar">
            <div id="WildClubs"> <a class="two" href="index.php"> WildClub Connect </a> </div>
			
			<?php if(isset($_SESSION['email']) && $email != ""): ?>
                <a href="signout.php"> <div class="signin_button"> Sign out </div> </a>
                <span class="username" onclick='showusers()'> Search... </span>
                <span class="username"> <a class="three" href="profile.php"> <?php echo $username; ?>
                </a> </span>
                <span class="signup_button"> <a class="two" href="why.php" class="why"> Site Information </a> </span> 
			<?php else: ?>
                <a href="signin_markup.php"> <div class="signin_button"> Sign in </div> </a>
                <a class="two" href="signup_markup.php"> <div class="signup_button"> Sign up <a class="two" href="why.php" class="why"> (Why?) </a>  </div> </a>
			<?php endif ?>
			
		</div>

        <?php
    
                $currentpassword_err = "";
                $newpassword_err = "";
                $confirmpassword_err = "";
            
                $sql = "SELECT password FROM users WHERE username = '$username'";
                $result = mysqli_query($connection, $sql);
                while($row = mysqli_fetch_assoc($result)) {
                    $realpassword = $row['password'];
                }            
    
            if(isset($_POST['reset'])) {
                $connection = mysqli_connect('localhost', 'root', '','wildclubconnect');
                
                // Assume there are no errors
                $currentpassword_err = "";
                $newpassword_err = "";
                $confirmpassword_err = "";
                
                $currentpassword = $_POST['currentpassword'];
                $newpassword = $_POST['newpassword'];
                $confirmpassword = $_POST['confirmpassword'];
                $hashednewpassword = password_hash($newpassword, PASSWORD_DEFAULT);
                
                if(!password_verify($currentpassword, $realpassword)) {
                    $currentpassword_err = "Incorrect password.";
                } else {
                  
                    if(strlen($newpassword) < 5) {
                        $newpassword_err = "Password must be at least 5 characters.";
                    } else {
                         if($newpassword != $confirmpassword) {
                             $confirmpassword_err = "Passwords do not match.";
                         } else {
                            $sql = "UPDATE users SET password='$hashednewpassword' WHERE username = '$username'";
                            if(mysqli_query($connection,$sql)) {echo "password successfully reset.";} else {echo "error";}
                            session_destroy();
                            header("location:signin_markup.php");
                            }          
                    }
                }
            }
            
                $password2_err = "";
                $confirmpassword2_err = "";
            
            if(isset($_POST['deactivate'])) {
                $password2_err = "";
                $confirmpassword2_err = "";
                
                $password2 = $_POST['password2'];
                $confirmpassword2 = $_POST['confirmpassword2'];
                
                if(!password_verify($password2, $realpassword)) {
                    $password2_err = "Incorrect password.";
                } else {
                    if($password2 != $confirmpassword2) {
                        $confirmpassword2_err = "Passwords do not match.";
                    } else {
                        $sql = "DELETE FROM users WHERE username='$username'";
                        mysqli_query($connection, $sql);
                        session_destroy();
                        header("location:index.php");
                    }
                }
            }
    
        ?>


		<div id="background"> 
            SETTINGS
        </div>					
           		
        <div id="passwordsection">
            
            <span id="title"> RESET PASSWORD </span> <br>
            
            <form action="settings.php" method="post">
            <table id="passwordtable">
            
                <tr> <td> Current password: </td> <td> <input name="currentpassword" type="password"> </td> <td> <?php echo $currentpassword_err ?> </td> <br>
                <tr> <td> New password: </td> <td> <input name="newpassword" type="password"> </td> <td> <?php echo $newpassword_err ?> </td> <br>
                <tr> <td> Confirm new password: </td> <td> <input name="confirmpassword" type="password"> </td> <td> <?php echo $confirmpassword_err ?> </td>
                <tr> <td> </td> <td> <input type="submit" name="reset" value="Reset"> </td> </tr>

            
            </table>
            </form>
            
        </div>

        <div id="deactivatesection">
           
            <span id="title"> DEACTIVATE ACCOUNT </span> <br>
            
            <form action="settings.php" method="post">
            <table id="passwordtable">
            
                <tr> <td> Password: </td> <td> <input name="password2" type="password"> </td> <td> <?php echo $password2_err ?> </td> <br>
                <tr> <td> Confirm password: </td> <td> <input name="confirmpassword2" type="password"> </td> <td> <?php echo $confirmpassword2_err ?></td><br>
                <tr> <td> </td> <td> <input type="submit" name="deactivate" value="Deactivate"> </td></tr>
            
            </table>
            </form>
            
        </div>



        
	</body>
	
    <script>
	            function showusers () {
                var x = document.getElementById('modal');
                if(x.style.display == 'block') {
                    x.style.display = 'none';
                } else {
                    x.style.display = 'block';
                }
            }
    </script>
    
</html>




