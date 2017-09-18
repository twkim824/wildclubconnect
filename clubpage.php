<?php

    include 'database_session/session.php';
    include 'includes/db.php';
    include 'database_session/Database.php';

    if (isset ($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $email_length = strlen($email);
    $username = substr($email, 0, $email_length - 19);
        
        header('Cache-Control: no cache'); //no cache

    session_cache_limiter('must-revalidate');

    }

    $connection = mysqli_connect('localhost','root','','wildclubconnect');
            
    // Retrieving the URL to get the name of the club
    $querystring = $_SERVER['QUERY_STRING'];
    $querystringlen = strlen($querystring);
    $querystring2 = substr($querystring, 3, $querystringlen - 1); // gets everything after php?id=
    $clubName = str_replace("%20"," ",$querystring2);

    $sqlQuery = "SELECT * FROM clubs WHERE clubName = :clubName";
    $statement = $db -> prepare($sqlQuery);
    $statement -> execute(array(':clubName' => $clubName));
            
            if ($row = $statement->fetch()) {
                $clubDescription = $row['clubDescription'];
                $clubAdmin = $row['clubAdmin'];
                $clubCategory = $row['clubCategory'];
                $clubRatingCount = $row['clubRatingCount'];
                $clubRatingSum = $row['clubRatingSum'];
                $clubTime = $row['clubTime'];
                $clubStatus = $row['clubStatus'];
                
            if($clubStatus == "Actively Recruiting") {
                $clubStatus = "<span style='color:#32CD32'> $clubStatus </span>";
            } elseif($clubStatus == "Recruiting") {
                $clubStatus = "<span style='color:blue'> $clubStatus </span>";
            } elseif($clubStatus == "Limited Recruiting") {
                $clubStatus = "<span style='color:orange'> $clubStatus </span>";
            } elseif ($clubStatus == "Not Recruiting") {
                $clubStatus = "<span style='color:red'> $clubStatus </span>";
            }
            }
        
        if ($clubRatingCount != 0) {
            $clubRating = number_format(round($clubRatingSum / $clubRatingCount,2), 2, '.','');

            $clubRating = $clubRating . "/10";
        } else {
            $clubRating = "";
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
            
        #creategroupempty {
             height: 3%;
             background-color:none;
         }    
            
        .creategroup {
            text-decoration: none;
            display:block;
            margin:auto;
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
            
            #description {
                border-radius: 8px;
                display: inline-block;
                color: #62179B;
                margin-right: 10%;
                margin-left: 10%;
                width: 80%;
                background-color: white;
            }
            
            .joinrequest {
                margin-top: 20px;
                margin-right: 10%;
                margin-left: 10%;
                width: 80%;
                background-color: #62179B;
                height: 10%;
                color: white;
                font-size: 20px;
                transition-duration: 0.4s;
            }
            
            .joinrequest:hover {
                background-color: #45106D;
                cursor: pointer;
            }
            
            .joinrequestsent {
                margin-top: 20px;
                margin-right: 10%;
                margin-left: 10%;
                width: 80%;
                background-color: #62179B;
                color: white;
                font-size: 20px;
                text-align: center;
            }
            
            #rightsection {
                border-style: solid;
                border-radius: 8px;
                display:inline-block;
                float: left;
                color: white;
                background-color: purple;
                margin-left: 10%;
                margin-right: 10%;
                margin-top: 20px;
                width: 80%;
            }
            
            #submitquestion {
                float:right;
                margin: none;
            }

            #clubpagebottomleft {
                height: 100%;
                background-color: #EFEFEF;
                width: 60%;
                display: inline-block;
                outline-style: solid;
                outline-color: white;
            }
            
            #clubpagebottomright {
                height: 100%;
                background-color: #D8D6D8;
                width: 40%;
                display:inline-block;
                float:right;
                outline-style: solid;
                outline-color: white;
            }
            
            #individualQ {
                height: 30px;
                width: 90%;
                font-size: 12px;
                color: #62179B;
                background-color: white;
                margin: auto;
                margin-top: 10px;
                margin-bottom: 10px;
                padding: 4px;
                overflow: auto;
                border-radius: 6px;
            }

            
            #allQ {
                overflow-y: scroll;
                height: 85%;
            }
            

            
            #bottomhalf {
                height: 80%;
            }
            
            #evaluations {
                height: 100%;
                display: block;
                padding: 20px;
                background-color: #AB9ABA;
            }
            
            #questiondiv {
                width: 90%;
                border-radius: 6px;
                margin: auto;
                padding: 4px;
                display: block;
                height: 10%;
            }
            
            textarea {
            resize: none;
            outline: 1px solid #62179B;
            width: 80%;
            padding: 1%;
            height: 50px;
            overflow-y:scroll;
            }
            
            #individualE {
                width: 100%;
            }
            
            a.four{
                text-decoration: none;
                font-weight:bold;
            }

            a.four:hover {
                text-decoration: underline;
            }

            input[type=text], select {
                width: 100%;
                padding: 10px 10px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }
            
            .anon {
                color:#62179B;
                font-weight: bold;
            }
            
            #formdiv {
                height: 200px;
            }
            
            .showmembers:hover {
                text-decoration: underline;
                cursor: pointer;
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

        <div class='modal' id='coadmins'> 
            <div class='modal-content'>
 
            <span class='close' onclick='showmembers()'>&times</span>
             <br> <br>
             
             <?php
                
                // if the viewer is an admin or coadmin, show promotion button next to each member.
                $sql = "SELECT * FROM clubs WHERE clubName = '$clubName' AND clubAdmin = '$username'";
                $result = mysqli_query($connection, $sql);
                $isadmin = false;
                if(mysqli_num_rows($result) > 0) {
                    $isadmin = true;
                }
                
                $sql = "SELECT * FROM coadmins WHERE clubName = '$clubName' AND coadmin = '$username'";
                $result = mysqli_query($connection, $sql);
                $iscoadmin = false;
                if(mysqli_num_rows($result) > 0) {
                    $iscoadmin = true;
                }
                
                echo "<span style='font-weight: bold'>Coadmins</span><br>";
                
                $sql = "SELECT * FROM coadmins WHERE clubName = '$clubName' ORDER BY coadmin ASC";
                $result = mysqli_query($connection, $sql);
                if(mysqli_num_rows($result) > 0) {
                    if($isadmin) {
                        while($row = mysqli_fetch_array($result)) {
                            $coadmin = $row['coadmin'];
                            echo $coadmin . "<form action='makeadmin.php?id=$coadmin" . "_$clubName' method='post'> <input type='submit' name='makeadmin' value='Make Admin'> </form>" . "<br>";
                        }
                    } else {
                        while($row = mysqli_fetch_array($result)) {
                            $coadmin = $row['coadmin'];
                            echo $coadmin . "<br>";
                        }
                    }
                }
                
                
                echo "<br><span style='font-weight: bold'>Members</span><br>";
                
                $sql = "SELECT * FROM members WHERE clubName = '$clubName' ORDER BY member ASC";
                $result = mysqli_query($connection, $sql);
                if(mysqli_num_rows($result) > 0) {
                    if($iscoadmin || $isadmin) {
                        while($row = mysqli_fetch_array($result)) {
                            $member = $row['member'];
                            echo $member . "<form action='makecoadmin.php?id=$member" . "_$clubName'" . "method='post'> <input type='submit' name='makecoadmin' value='Make Coadmin'> </form>";
                        }
                    } else {
                        while($row = mysqli_fetch_array($result)) {
                            $member = $row['member'];
                            echo $member;
                        }
                    }
                }
                
             ?>
             
            </div>
        </div>

		<div id="topbar">
            <div id="WildClubs"> <a class="two" href="index.php"> WildClub Connect </a> </div>
			
			<?php if(isset($_SESSION['email']) && $email != ""): ?>
                <a href="signout.php"> <div class="signin_button"> Sign out </div> </a>
                <span class="username"> <a class="three" href="settings.php"> Settings
                </a> </span>
                <span class="username"> <a class="three" href="profile.php"> <?php echo $username; ?>
                </a> </span>
                <span class="signup_button"> <a class="two" href="why.php" class="why"> Site Information </a> </span> 
			<?php else: ?>
                <a href="signin_markup.php"> <div class="signin_button"> Sign in </div> </a>
                <a class="two" href="signup_markup.php"> <div class="signup_button"> Sign up <a class="two" href="why.php" class="why"> (Why?) </a>  </div> </a>
			<?php endif ?>
			
		</div>



		<div id="background"> 
        <?php echo $clubName; ?>
        </div>	
    
       <div id='bottomhalf'>
       
        <div id="clubpagebottomleft">
        
        <?php
    
            if(isset($_SESSION['email'])) {
                
                $message = "";
                
                // Check through database to see if user is admin of the club.
                $sql = "SELECT * FROM clubs WHERE clubName = '$clubName' AND clubAdmin = '$username'";
                $result = mysqli_query($connection, $sql);
                $isAdmin = false;
                
                if(mysqli_num_rows($result) > 0) {
                    $isAdmin = true;
                    $message = "You are the admin. Click " . "<a class='two' href='editclub.php?id=" . $clubName . "'>here</a> to edit club details.";
                }
                
                // Check through database to see if user is a coadmin of the club.
                $sql = "SELECT * FROM coadmins WHERE clubName = '$clubName' AND coadmin = '$username'";
                $result = mysqli_query($connection, $sql);
                $isCoadmin = false;
                
                if(mysqli_num_rows($result) > 0) {
                    $isCoadmin = true;
                    $message = "You are a coadmin.";
                }
                
                // Check through database to see if user is a member.
                $sql = "SELECT * FROM members WHERE clubName = '$clubName' AND member = '$username'";
                $result = mysqli_query($connection, $sql);
                $isMember = false;

                if(mysqli_num_rows($result) > 0) {
                    $isMember = true;
                    $message = "You are a member.";
                }
                
                // Check through database to see if user has already sent a request for this club
                $sql = "SELECT * FROM joinrequests WHERE requester = '$username' AND clubName = '$clubName'";
                $data = mysqli_query($connection, $sql);
                $requested = false;
                
                if (mysqli_num_rows($data) > 0) {
                    $requested = true;
                    $message = "Request to join sent.";
                }
                
                echo "<form method='post' action='clubpage.php?id=$clubName'> <div class='joinrequestsent'> $message </div>";
                
                if(!$requested && !$isMember && !$isCoadmin && !$isAdmin) {
                    echo "<form method='post' action='clubpage.php?id=$clubName'><select style='display:none' name='clubselect'> <option value='$clubName'> </option> </select> <input type='submit' name='joinrequest' class='joinrequest' value='Request to join'> </form>";
                }
                
                if(isset($_POST['submitquestion'])) {
                    $question_text = $_POST['questiontext'];
                    $question_date = date("Y-m-d H:i:s");
                    $clubName = $_POST['clubselect'];

                    $sql = "INSERT INTO questions VALUES ('','$clubName','$username','$question_text','$question_date')";
                    mysqli_query($connection, $sql);

                }
                
            }
            
            if(isset($_POST['joinrequest'])) {
                // Check through database to see if user has already sent a request for this club
                $sql = "SELECT * FROM joinrequests WHERE requester = '$username' AND clubName = '$clubName'";
                $data = mysqli_query($connection, $sql);
                $requested = false;
                
                while($row = mysqli_fetch_array($data)) {
                    $requested = true;
                }
                
                if(!$requested) {
                $clubName = $_POST['clubselect'];
                $requester = $username;
                
                $connection = mysqli_connect('localhost','root','','wildclubconnect');
                $sql = "INSERT INTO joinrequests VALUES ('','$requester','$clubName')";
                
                mysqli_query($connection, $sql);
                }
            }
    
        ?>
        
           <div id="description">
            
            <span style='font-weight: bold'>Main admin: <?php echo $clubAdmin ?> <br>
            Status: <?php echo $clubStatus ?> <br>
                Rating: <?php echo $clubRating ?> (<?php echo $clubRatingCount ?> Ratings) </span> <br>
               Click <span class='showmembers' id='showmembers' onclick='showmembers()'>here</span> for a list of coadmins and members. <br> <br>
                <?php echo $clubDescription ?> <br>
            
        </div>
                
        </div>          
        
        <div id="clubpagebottomright">          

            <?php
                if(isset($_SESSION['email'])) {
                    
                    $string = " <div id='questiondiv'> <form action='clubpage.php?id=$clubName' method='post'> 
                                <input type='text' name='questiontext' id='questiontext' placeholder='Ask us a quick question, or email an admin for a lengthier one!'> <br> 
                                <input type='submit' name='submitquestion' id='submitquestion' value='Ask'>
                                <select style='display:none' name='clubselect'> <option value='$clubName'> </select>
                                </form> </div> <br> <div id='allQ'> ";
                    
                    echo $string;
                }
            
                    $questionstring = "";
                    $connection = mysqli_connect('localhost','root','','wildclubconnect');                 
                    $sql = "SELECT * FROM questions WHERE question_club = '$clubName' ORDER BY id DESC";
                    $result = mysqli_query($connection, $sql);
                    
                    if(mysqli_num_rows($result) > 0) {
                        
                        
                        while ($row = mysqli_fetch_array($result)) {
                            $question_author = $row['question_author'];
                            $question_text = $row['question_text'];
                            $question_date = $row['question_date'];
                            $linktoprofile = "<a class='four' href='profile.php?id=" . $question_author . "'>" . $question_author . "</a>"; 
                            
                            $questionstring .= "<div id='individualQ'> <span style='font-weight:bold'>". $linktoprofile . "</span>" . ' (' . $question_date .') <br>' . $question_text . "</div>";
                        }

                        
                        echo $questionstring;
                    } else {
                        echo '&nbsp &nbsp &nbsp &nbsp' . "No questions asked at this time. <br> <br>";
                    }
            
            ?>
            
            
            </div>
        </div>

        
        <div id="evaluations">
            <span style='font-weight:bold; font-size: 20px;'> EVALUATIONS AND FEEDBACK BY MEMBERS, COADMINS AND ADMIN </span> <br> <br>
            
            <?php
    
                if(isset($_SESSION['email']) && ($isMember || $isCoadmin || $isAdmin)) {
                                        
                    echo "<form action='clubpage.php?id=$clubName' method='post'>
                            <textarea type='text' name='evaluationtext'> </textarea> <br>
                            <select style='display:none' name='sneakyclub'> <option value='$clubName'> </option> </select>
                            Your rating /10:
                            <select style='width: 50px;' name='rating'> 
                                <option value='1'> 1 </option>
                                <option value='2'> 2 </option>
                                <option value='3'> 3 </option>
                                <option value='4'> 4 </option>
                                <option value='5'> 5 </option>
                                <option value='6'> 6 </option>
                                <option value='7'> 7 </option>
                                <option value='8'> 8 </option>
                                <option value='9'> 9 </option>
                                <option value='10'> 10 </option>
                            </select> <br>
                            <input type='submit' name='submitevaluation'> <input type='checkbox' name='anon' checked> Post anonymously &nbsp &nbsp
                            <input type='checkbox' name='checkeval' checked> Overwrite my previous evaluation
                        </form> <br> <br>";
                    
                    echo "<div id='evaluationsdiv'>";
                    
                    // Insert the evaluation into the database, and insert the rating into the club table
                            if(isset($_POST['submitevaluation'])) {
                                if(isset($_POST['anon'])) {
                                    $eval_user = "Anonymous";
                                } else {
                                    $eval_user = $username;
                                }
                                $eval_club = $_POST['sneakyclub'];
                                $clubName = $_POST['sneakyclub'];
                                $eval_content = $_POST['evaluationtext'];
                                $eval_date = date("Y-m-d H:i:s");
                                $eval_rate = $_POST['rating'];

                                $check_empty = strip_tags($eval_content); // removes html tags
                                $check_empty = mysqli_real_escape_string($connection, $eval_content);
                                $check_empty = preg_replace('/\s+/','',$eval_content); // deletes all spaces
                                
                                $sql = "SELECT * FROM evalcheck WHERE clubName = '$clubName' AND evaluator = '$username'";
                                $result = mysqli_query($connection, $sql);
                                $already = false;
                                $deleteP = false;
                                
                                if($row = mysqli_fetch_array($result)) {
                                    $already = true;
                                }
                                
                                if(isset($_POST['checkeval'])) {
                                    $deleteP = true;
                                }
                                
                                if($check_empty) {
                                    if($deleteP || (!$already && !$deleteP)) {
                                        $sql = "INSERT INTO evaluations VALUES ('','$eval_user','$eval_club','$eval_content','$eval_date','0','$eval_rate')";
                                        mysqli_query($connection, $sql);
                                        
                                        if(!$already) {
                                            $sql2 = "UPDATE clubs SET clubRatingCount = clubRatingCount+1 WHERE clubName = '$clubName'";
                                            mysqli_query($connection, $sql2);

                                            $sql3 = "UPDATE clubs SET clubRatingSum = clubRatingSum + $eval_rate WHERE clubName = '$clubName'";
                                            mysqli_query($connection, $sql3);
                                            
                                            $sql4 = "INSERT INTO evalcheck VALUES ('','$clubName','$username')";
                                            mysqli_query($connection, $sql4);
                                        }
                                        
                                        if($already) {
                                            $sql5 = "SELECT eval_rating FROM evaluations WHERE eval_user = '$username' AND eval_club = '$clubName'";
                                            $result = mysqli_query($connection, $sql5);
                                            $row = mysqli_fetch_array($result);
                                            $current_rating = $row['eval_rating'];
                                            $newRating = $eval_rate - $current_rating;
                                            
                                            $sql6 = "UPDATE clubs SET clubRatingSum = clubRatingSum + $newRating WHERE clubName = '$clubName'";
                                            mysqli_query($connection, $sql6);
                                        }
                                    }
                                    
                                    if(!$deleteP && $already) {
                                        echo "<span style='color: red'> You already posted an evaluation for this club. Please select to overwrite. </span>";
                                    }
                                } else {
                                    echo "<span style='color: red'> *You did not type anything. </span> <br> <br>";
                                }
                            }
                    
                    // Retrieve the evaluations from the database
                    $sql = "SELECT * FROM evaluations WHERE eval_club = '$clubName' ORDER BY eval_upvotes, id DESC";
                    $data = mysqli_query($connection, $sql);
                    
                    $evaluationstr = "";
                    
                    if(mysqli_num_rows($data) > 0) {
                        while($row=mysqli_fetch_array($data)) {
                            $eval_user = $row['eval_user'];
                            $eval_content = $row['eval_content'];
                            $eval_date = $row['eval_date'];
                            $eval_upvotes = $row['eval_upvotes'];
                            $eval_rating = $row['eval_rating'];
                            
                            if($eval_user != "Anonymous") {
                                $eval_user = "<a class='four' href='profile.php?id=" . $eval_user . "'>" . $eval_user . "</a>";
                            } else {
                                $eval_user = "<span class='anon'> Anonymous </span>";
                            }
                            
                            $evaluationstr .= "<div id='individualE'> $eval_user ($eval_date) (Rating: $eval_rating) Upvotes: $eval_upvotes <br> $eval_content </div> <br>";
                        }
                        echo $evaluationstr;
                    }
                    
                }
            
            if(isset($_SESSION['email']) && !$isMember && !$isCoadmin && !$isAdmin) {
                echo "You must be approved as a member to write an evaluation.";
            }
            
            if(!isset($_SESSION['email'])) {
                echo "You must be signed in to view evaluations.";
            }
            
            echo "</div>";
    
            ?>
            
        </div>
        
    
    <script>

            function showmembers () {
                var x = document.getElementById('coadmins');
                if(x.style.display == 'block') {
                    x.style.display = 'none';
                } else {
                    x.style.display = 'block';
                }
            }
        
        function askquestion() {
            var x = document.getElementById('questiondiv');
            if(x.style.display == 'none') {
                x.style.display = 'block';
            } else {
                x.style.display = 'none';
            }
        }
        
    </script>       		

	</body>
</html>




