<?php

session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <style>

       
		#backgroundImage {
			background-image: url(./Images/campus5.jpg);
			background-repeat:no-repeat;
			background-size:100%
		}
       
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
       
		#signupbutton {
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
       
       #why {
           float:right;
           color:black;
       }
       
       a.one {
           color: #8154CE;
           text-decoration:none;
       }
       
       a.one:hover {
           text-decoration: underline;
       }
       
       a.one:visited {
           text-decoration:none;
           color: #8154CE;
       }
       
       .error{
           color:#FF674F;
           font-size: 70%
       }
    
   </style>
   
    <meta charset="UTF-8">
    <title>Document</title>
</head>

<body id = "backgroundImage">

<?php
    
    include_once 'database_session/Database.php';
    
    
    // pre-written function for testing email validity
    function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }
    
        // Initially assume that there are no errors in the form.
        $emailErr = "";
        $passwordErr = "";
        $confirmPasswordErr = "";
        $emailTaken = "";
    
        $clicked = false;
    
        // Do not connect to database until form is complete.
        $connection = false;
    
    if (isset($_POST["submit"])) {
        $clicked = true;
        
        // Storing fields in variables:
        $email = $_POST["email"];
        $password = $_POST['password'];
        $confirmPassword = $_POST["confirmPassword"];
        
        $_SESSION['email'] = $email;            // Stores email into session variable
        
        $email_length = strlen($email);            // variable containing the length of the email
        $northwestern_email = substr($email, -19); // variable containing the last 19 characters of email (@u.northwestern.edu);

            
        // Initially assume that there are no errors in the form:
        $emailErr = "";
        $passwordErr = "";
        $confirmPasswordErr = "";
        $emailFound = false;
        
        // CHECKING EMAIL ----------------------------------------------------------------------------------
        
        // Boolean: email validity
        $checkValidEmail = test_input($_POST["email"]);
        
        // Checking if email is blank
        if (empty($email)) {
            $emailErr = "*This field is required.";
        }
        
        // Checking if valid email address
        elseif (!filter_var($checkValidEmail, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "*Invalid email format.";
        }
    
        // Checking if email is Northwestern     
        else {
            if ($northwestern_email != "@u.northwestern.edu") {
                $emailErr = "*You must use your Northwestern email address.";
            }
        }
        
        // Checking if email already exists in the database
        $sqlQuery = "SELECT * FROM users WHERE email = :email";
        $statement = $db -> prepare($sqlQuery);
        $statement -> execute(array(':email' => $email));
        
        while ($row = $statement->fetch()) {
            $emailFound = true;
        }
        
        if ($emailFound) {
            $emailErr = "*Email already taken.";
        }

        // CHECKING PASSWORD ----------------------------------------------------------------------------------
        
        $passwordLength = strlen($password);
       
        // Checking if password is blank
        if (empty($password)) {
            $passwordErr = "*This field is required.";
        }
        
        // Checking if password is between 5 and 10 characters
        elseif ($passwordLength < 5) {
            $passwordErr = "*Your password must be at least 5 characters.";
        }        
        
        // CHECKING CONFIRM PASSWORD ---------------------------------------------------------------------------
        
        if (empty($confirmPassword)) {
            $confirmPasswordErr = "*This field is required.";
        }
        
        if ($confirmPassword != $password) {
            $confirmPasswordErr = "*Your passwords do not match.";
        }
        
        // NO ERRORS -> PROCEED TO SIGNUP ----------------------------------------------------------------------
        if ($emailErr == "" && $passwordErr == "" && $confirmPasswordErr == "") {
            $connection = mysqli_connect('localhost','root','','wildclubconnect');
        }
        
        if ($connection) {
            // creating the username: simply taking away @u.northwestern.edu
            $username = substr($email, 0, $email_length - 19);
            
            // hashing the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (username, email, password, clubnumber, club1, club2, club3) VALUES ('$username','$email','$password','0','0','0','0')";
            
            mysqli_query($connection, $sql);
            header("location:signin_markup.php");
            }
        
    }
?>
		
<div id="signUpBox">            
<span id="welcome"> Welcome, Wildcat! </span>
                                                                                                
<form action="signup_markup.php" method="post">
   
   <table>
    
       <tr> <td> <span id="email"> Northwestern email: </span> </td>
           <td> <input type="text" name="email" value="<?php 
                       if($emailErr == "" && $clicked) {                           
                           echo $_SESSION['email'];
                       }
               
                ?>"> </td> </tr>
           
        <tr> <td> </td> <td> <span class="error"> <?php echo $emailErr; echo $emailTaken; ?> </span> </td></tr>

       <tr> <td> <span id="email"> Password: </span> </td>
           <td> <input type="password"  name="password"> </td> </tr>
           
        <tr> <td> </td> <td> <span class="error"> <?php echo $passwordErr; ?> </span> </td> </tr>

       <tr> <td> <span id="email"> Confirm password: </span> </td>
           <td> <input type="password"  name="confirmPassword"> </td> </tr>
           
        <tr> <td> </td> <td> <span class="error"> <?php echo $confirmPasswordErr; ?> </span> </td></tr>

       <tr> <td> </td> <td> <input type="submit" name="submit" id="signupbutton" value="Sign Up"> </td> </tr>
       <tr> <td> </td> <td> <a class="one" href="why.php"> <span id="why"> Why sign up? </span> </a> </td> </tr>
    
    </table>
    
</form>
    
</div>

        
</body>
</html>