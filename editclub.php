<?php
    include 'database_session/session.php';
    include 'includes/db.php';

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
 
        a.four{
            text-decoration: none;
            font-weight:bold;
        }

        a.four:hover {
            text-decoration: underline;
        }

        #commentbutton {
            float:right;
            margin:none;
        }

        #commenttext {
            margin:none;
            border-radius: none;
        }
            
        #commentform {
             display:none;           
        }    
            
        .loadcomments {
            float:right;
        }
            
        body, html {
            height:100%;
            padding:0;
            margin:0;
        }
             
            
        .creategroup {
            text-decoration: none;
            display:block;
            margin:auto;
            margin-top: 20px;
            margin-bottom: 20px;
            font-size: 20px;
            background-color: #62179B;
            border: none;
            transition-duration: 0.4s;
            font-family: tahoma;
            border-radius: 4px;
            color:white;
            font-family:tahoma;
            font-weight:bold;
            width: 300px;
         }  
            
            #redirectcss {
                font-size: 12px;
            }
            
        .creategroup:hover {
            background-color: #45106D;
            cursor: pointer;
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
            height: 7%;
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

        #postsection {
            margin: 0px;
            height: 93%;
            overflow-y: scroll;
            background-color: #D8D6D8;
        }


        .contentsubmit {
            display: inline-block;
            float:right;
            height: 20px;
            width: 50px;
            border:none;
            background-color:#62179B;
            color:white;
            transition-duration: 0.4s;
        }

        .contentsubmit:hover {
            background-color: #45106D;
            cursor: pointer;
        }

        #clubname {
            display:inline-block;
            width: 100%;
        }

        #creategroupform {
            padding: 3%;
            display: none;
            margin:auto;
            margin-top:none;
        }

        .postcss {
            color: #62179B;
            background-color: white;
            padding: 4px;
            font-size:12px;
            width: 90%;
            margin:auto;
            margin-top: 10px;
            border-radius: 6px;
            overflow:auto;
        }



        #signintopost {
            width:100%;
            height:7%;
            line-height: 45px;
            vertical-align: middle;
            font-family: tahoma;
            color: #46106E;
            text-align: center;
            margin: none;
        }

        textarea {
            resize: none;
            outline: 1px solid #62179B;
            width: 98%;
            padding: 1%;
            height: 200px;
            overflow-y:scroll;
        }

        #categorymenu {
            display: inline-block;
            float: right;
        }

        #tabeldiv {
            overflow-y: scroll;
        }

        
        .styled-select1 {
            height: 10%;
            width: 50%;
            display: inline-block;
            float:left;
        }    

            
        .styled-select select{
            font-size: 14px;
            height: 20px;
            padding: 4px;
            border-radius: 4px;
            width: 150px;
        }
            
            
		table {
			border-collapse: collapse;
			margin: auto;
            display: table;
            width: 90%;
            border:none;
            color: black;
		}

		td, th {
            border:none;
			text-align: left;
			padding:5px;
		}
            
        th {
            background-color: #5C1692;
            color: white;
        }

        #box {
            background-color: #EFEFEF;
            height:100%;
            width:60%;
            font-family: tahoma;
            outline: 4px solid #46106E;
            display:inline-block;
        }
            
        #tablediv {
            text-align: center;
            color: red;
        }
            
        .clubsubmit {
            float:right;
            margin-top: 5px;
            margin-right: 5px;
            font-size: 200px;
        }

        .clubsubmit:hover {
            cursor: pointer;
        }
            
		input[type=text], select {
    		padding: 10px 10px;
    		margin: 8px 0;
    		display: inline-block;
    		border: 1px solid #ccc;
    		border-radius: 4px;
    		box-sizing: border-box;
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
            
        .username {
            color:white;
			font-size: 100%;
			font-family: tahoma;	
			float:right;
			padding: 15px;
			background-color: #62179B;
			margin-right: none;
			transition-duration: 0.4s;
        }    

        .username:hover {
			background-color: #45106D;
            cursor: pointer;
        } 
            
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            display: inline-block;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
            
		</style>

	</head>



	<body>
	
        <div class='modal' id='modal'> 
            <div class='modal-content'>
               <form method='post' action='index.php' style='display:inline-block'> <input type='text' style='width:100%; height: 100%;' placeholder='Search for users...' name='usersearched'> <input type='submit' name='searchusers' value='Search' style='display:none'> </form> 
            <span class='close' onclick='showusers()'>X</span>
             <br> <br>
                <?php
                    if(isset($_POST['searchusers'])) {
                        $usersearched = $_POST['usersearched'];
                        
                        $sql = "SELECT * FROM users WHERE username LIKE '%$usersearched%'";
                        $result = mysqli_query($connection, $sql);
                        $string = "";
                        
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_array($result)) {
                                $username = $row['username'];
                                $linktoprofile = "<a href='profile.php?id=$username'>$username</a>";
                                $club1 = $row['club1'];
                                $club2 = $row['club2'];
                                $club3 = $row['club3'];
                                
                                if($club1 != '0') {
                                    $club1 = "<a href='clubpage.php?id=$club1>$club1</a>";
                                } else {
                                    $club1 = "";
                                }
                                
                                if($club2 != '0') {
                                    $club2 = "<a href='clubpage.php?id=$club2>$club2</a>";
                                } else {
                                    $club2 = "";
                                }
                                
                                if($club3 != '0') {
                                    $club3 = "<a href='clubpage.php?id=$club3>$club3</a>";
                                } else {
                                    $club3 = "";
                                }
                                
                                $adminstring = "(Admin of: $club1 $club2 $club3)";
                                
                                $string .= "<br> $linktoprofile" . " " . $adminstring;
                            }
                        }
                        
                        echo $string;
                    }
                ?>
            </div>
        </div>

		<div id="topbar">
            <div id="WildClubs"> <a class='two' href='index.php'> WildClub Connect </a> </div> 
			
			<?php if(isset($_SESSION['email']) && $email != ""): ?>
                <a href="signout.php"> <div class="signin_button"> Sign out </div> </a>
                <span class="username"> <a class="three" href="settings.php"> Settings
                </a> </span>
                <span class="username" onclick='showusers()'> Search... </span>
                <span class="username"> <a class="three" href="profile.php"> <?php echo $username; ?>
                </a> </span>
                <span class="signup_button"> <a class="two" href="why.php" class="why"> Site Information </a> </span> 
			<?php else: ?>
                <a href="signin_markup.php"> <div class="signin_button"> Sign in </div> </a>
                <a class="two" href="signup_markup.php"> <div class="signup_button"> Sign up <a class="two" href="why.php" class="why"> (Why?) </a>  </div> </a>
			<?php endif ?>
			
		</div>
		

		<div id="background"> 
            GET INVOLVED
        </div>					
        
    <?php
        
        // Retrieving the URL to get the name of the club
        $querystring = $_SERVER['QUERY_STRING'];
        $querystringlen = strlen($querystring);
        $querystring2 = substr($querystring, 3, $querystringlen - 1); // gets everything after php?id=
        $clubName = str_replace("%20"," ",$querystring2);
    
        $sql = "SELECT * FROM clubs WHERE clubName = '$clubName'";
        $connection = mysqli_connect('localhost', 'root', '','wildclubconnect');
        $result = mysqli_query($connection, $sql);
            
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_array($result)) {
                $clubName = $row['clubName'];
                $clubDescription = $row['clubDescription'];
                $clubAdmin = $row['clubAdmin'];
                $clubCategory = $row['clubCategory'];
                $clubTime = $row['clubTime'];
                $clubStatus = $row['clubStatus'];
            }
        }    
            

        echo "<form action='editclub.php?id=$clubName' method='post'>

       <input type='text' name='editClubName' value='$clubName'> <br>
       
       Description <br>
       
       <input type='text' name='editClubDescription' value='$clubDescription'> <br>
       
       <input type='text' name='editClubAdmin' value = '$clubAdmin'> <br>
           
           <div class='styled-select styled-select1'>
           
           <table>
               <tr>
               <td> Hours per week: </td>
               <td>
                   <select name = 'editClubTime'>
                           <option value='$clubTime'> Don't Change </option>
                           <option value='1~2'> 1~2 </option>
                           <option value='3~4'> 3~4 </option>
                           <option value='5~7'> 5~7 </option>
                           <option value='8~10'> 8~10 </option>
                           <option value='11~15'> 11~15</option>
                           <option value='16~20'> 16~20 </option>
                           <option value='>20'> > 20 </option>
                    </select>
               </td>
               </tr>
            
               <tr>
               <td> Status: </td>
               <td>
                    <select name = 'editClubStatus'>
                        <option value='$clubStatus'> Don't change </option>
                        <option value='Actively Recruiting'> Actively Recruiting </option>
                        <option value='Recruiting'> Recruiting </option>
                        <option value='Limited Recruiting'> Limited Recruiting</option>
                        <option value='Not Recruiting'> Not Recruiting </option>
                    </select> <br>
               </td>
               </tr>
               
               <tr>
               <td> Category: </td>
               <td>
            
                    <select name = 'editClubCategory'>
                           <option value='$clubCategory'> Don't change </option>
                           <option value='Athletics'> Athletics </option>
                           <option value='Culture'> Culture </option>
                           <option value='Recreation'> Recreation </option>
                    </select>
               </td>
               </tr>
            
               </table>
        
              </div> 
              
              <input style='float:left' type='submit' name='clubsubmit' class='clubsubmit' value='Update'>

            </form>";
            
            if(isset($_POST['clubsubmit'])) {
            
                $newClubName = $_POST['editClubName'];
                $clubDescription = $_POST['editClubDescription'];
                $clubAdmin = $_POST['editClubAdmin'];
                $clubTime = $_POST['editClubTime'];
                $clubStatus = $_POST['editClubStatus'];
                $clubCategory = $_POST['editClubCategory'];

                $sql = "UPDATE clubs clubName = $newClubName, clubDescription = $clubDescription, clubAdmin = $clubAdmin, clubTime = $clubTime, clubStatus = $clubStatus, clubCategory = $clubCategory WHERE clubName = $clubName";
            }
    ?>
        
    </body>
    
</html>
