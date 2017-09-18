<!DOCTYPE html>
<html lang="en">
<head>
   <style>
        
       	#signUpBox {
            margin: auto;
			background-color: rgba(255,255,255,0.5);
            border-radius: 20px;
            border-style: solid;
            border-color: #62179B;
            vertical-align: middle;
			width: 30%;
			height: 30%;
            padding: 5%;
		}
       
        #topbar {
			width: 100%;
			height: 50px;
			margin:0;
			background-color: #222222;
            padding:none;
		}
       
       	#WildClubs {
			color: white;
			font-size: 120%;
			padding: 13px;
			margin-left: 10%;
			float:left;
			font-family: tahoma;
		}
       
		#backgroundImage {
			background-image: url(./Images/campus5.jpg);
			background-repeat:no-repeat;
			background-size:cover;
            height: 80%
		}
       
/*
		#loginBox {
            margin: 0;
			background-color: rgba(34,34,34,0.7);
			width: 30%;
			height: 100%;
			right:0;
			z-index:-1;
			position:absolute;
            padding: 5%
		}
*/
       	#loginBox {
			background-color: none;
			width: 30%;
			height: 100%;
			right:0;
			z-index:-1;
			position:absolute;
            padding: 5%
		}
       
       
       #welcome {
           	color:black;
			font-weight:bold;
			font-size: 170%;
       }
       
       td {
           width: 200px;
       }
       
       input[type=text], input[type=password] {
           padding: none;
           width: 100%;
           border: 1px solid #ccc;
           border-radius: 4px;
       }
       
		#email {
			color:black;
			z-index:1;
		}      
       
		#signinbutton {
			padding: 5px 10px;
			display: inline-block;
			cursor:pointer;
			background-color: white;
			border-radius: 4px;
			border-style: solid;
			border-width: 1px;
            margin-top: 10px;
            float: right;
		}
       
       .error{
           color:#FF674F;
           font-size: 80%
       }
    
   </style>
   
    <meta charset="UTF-8">
    <title>Document</title>
</head>

<body id = "backgroundImage">

<?php
    
    include_once 'database_session/Database.php';
    include_once 'database_session/session.php';
    include_once 'database_session/utilities.php';
    
    // Initially assume that there are no errors in the form:
    $emailErr = "";
    $passwordErr = "";
    $invalidErr = "";
    
    if (isset($_POST['signin'])) {
        
        
        //Store the inputs in variables:
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        // Initially assume that there are no errors in the form:
        $emailErr = "";
        $passwordErr = "";
        $invalidErr = "";
        $emailFound = false;
        
        if (empty($email)) {
            $emailErr = "*This field is required.";
        }
        
        if (empty($password)) {
            $passwordErr = "*This field is required.";
        }
        
        // Checking if email exists in the database:
        $sqlQuery = "SELECT * FROM users WHERE email = :email";
        $statement = $db -> prepare($sqlQuery);
        $statement -> execute(array(':email' => $email));
        
        if($emailErr == "" && $passwordErr == "") {
            
            // Checking if password matches:
            while ($row = $statement->fetch()) {
                $emailFound = true;
                $id = $row['id'];
                $hashed_password = $row['password'];
                $username = $row['username'];
            
                if ($password == $hashed_password) { // if (password_verify($password, $hashed_password))
                    $_SESSION['id'] = $id;
                    $_SESSION['email'] = $email;
                    header("location: index.php");
                } else {
                    $invalidErr = "*Invalid username or password.";
                }
            }
            
            if(!$emailFound) {
                $invalidErr = "*Invalid username or password";
            }
        }
    }
                
        // email and password have been validated so proceed to sign in:
           
        
    
    
?>

<!--
		<div id="topbar">
			<div id="WildClubs"> WildClub Connect </div>
		</div>
-->

<div id="signUpBox">            
<span id="welcome"> Welcome, Wildcat! </span>
                                                                                                
<form action="signin_markup.php" method="post">
   
   <table>
    
       <tr> <td> <span id="email"> Northwestern email: </span> </td>
           <td> <input type="text" name="email"> </td> </tr>
           
        <tr> <td> </td> <td> <span class="error"> <?php echo $emailErr; ?> </span> </td></tr>

       <tr> <td> <span id="email"> Password: </span> </td>
           <td> <input type="password" name="password"> </td> </tr>
           
        <tr> <td> </td> <td> <span class="error"> <?php echo $passwordErr; ?> </span> </td> </tr>

       <tr> <td> <span class="error"> <?php echo $invalidErr ?> </span> </td> <td> <input type="submit" name="signin" id="signinbutton" value="Sign in"> </td> </tr>
       
       <tr> <td> Don't have an account? <br> <a href="signup_markup.php"> Sign up! </a> </td> <td> </td></tr>
    
    </table>
    
</form>
    
</div>

        
</body>
</html>