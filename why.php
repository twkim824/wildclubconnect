<?php

    include_once 'database_session/session.php';

    if (isset ($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $email_length = strlen($email);
    $username = substr($email, 0, $email_length - 19);
    } 

?>


<!DOCTYPE html>

<html>

	<head>
		<title> WildClub Connect </title>
		<link rel="stylesheet" type="text/css" href="index.css">

		<style type="text/css">
            

            
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

		#why_grey {
			background-color: #EEEEEE;
			color: black;
			margin:none;
			position:absolute;
			padding: 5%;
			padding-top: 2%;
            border-top: 8px solid #8154CE;
            border-bottom: 8px solid #8154CE;
		}

		#WildClubs a {
			color: white;
		}

		</style>

	</head>



	<body>	

		<div id="topbar">
			<div id="WildClubs"> <a class="two" href="index.php"> WildClub Connect </a> </div>
			
<!--
            <?php if(!isset($_SESSION['email'])): ?>
			<a href="signin_markup.php"> <div class="signin_button"> Sign in </div> </a>
            <a class="two" href="signup_markup.php"> <div class="signup_button"> Sign up <a class="two" href="why.php" class="why"> (Why?) </a>  </div> </a>
			<?php else: ?>
			<a href="signout.php"> <div class="signin_button"> Sign out </div> </a>
			<span class="username"> <a class="three" href="profile.php"> Profile </a> </span>
			<span class="signup_button"> <a class="two" href="why.php" class="why"> Site Information </a> </span> 
			<?php endif ?>
-->
        
            <?php if(isset($_SESSION['email']) && $email != ""): ?>
			<a href="signout.php"> <div class="signin_button"> Sign out </div> </a>
			<span class="username"> <a class="three" href="profile.php"> Profile </a> </span>
			<span class="signup_button"> <a class="two" href="why.php" class="why"> Site Information </a> </span> 
			<?php else: ?>
			<a href="signin_markup.php"> <div class="signin_button"> Sign in </div> </a>
            <a class="two" href="signup_markup.php"> <div class="signup_button"> Sign up <a class="two" href="why.php" class="why"> (Why?) </a>  </div> </a>
			<?php endif ?>
			
		</div>

		<div id="background">
		
		<?php if(!isset($_SESSION['email'])): ?>
		WHY SIGN UP? 
		<?php else: ?>
		ABOUT
		<?php endif ?>
		
		</div>


					<div id="why_grey"> 

					<h2> About Wildclub Connect </h2>

					<p> By creating an account, you will have unlimited access to specific rating breakdowns and written evaluations of clubs, all submitted by other students. Accessing club members' ratings and evaluations will give you the most accurate insight into what it is truly like to be part of the clubs you are interested in. </p>

					<p> Furthermore, you will be allowed to initiate up to three clubs on WildClub Connect. All you need to do is fill in a form describing the club, and everyone will be able to your clubs on the list. This is an especially useful feature if you are simply trying to gather a group of interested people for a small project.

					<h3> The rating / evaluation system </h3>

					<p> There is one prerequisite for unlimited access to WildClubs' ratings and evaluations - you must submit a rating and evaluation (optionally anonymous) of your own for at least one club that you are part of, every three months. This condition is imposed three months after you have created a new account. If you do not leave a rating and evaluation for more than three months, you will not be able to access other ratings and evaluations until you have completed it. </p>

					<p> Furthermore, you must be officially approved as a member by the WildClub for you to be able to leave a rating and evaluation for that club. </p>

					</div> <!-- Closing bracket for "grey" -->


	</body>
</html>

