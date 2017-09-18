<?php
    include 'database_session/session.php';
    include 'includes/db.php';
    include 'includes/classes/Post.php';
    include 'includes/classes/Club.php';

    if (isset ($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $email_length = strlen($email);
    $username = substr($email, 0, $email_length - 19);
    } 

    header('Cache-Control: no cache'); //no cache

    session_cache_limiter('must-revalidate');

//    function loadPostComments {
//        
//    }

    function loadAllPosts($upvote_author) {
    $str = "";
    $connection = mysqli_connect('localhost', 'root','', 'wildclubconnect');
    $data = mysqli_query($connection, "SELECT * FROM posts ORDER BY post_likes DESC");

        // First while loop to fetch individual posts
        while($row = mysqli_fetch_array($data)) {
            
            $commentstr = "";
            $post_id = $row['post_id'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_content = $row['post_content'];
            $post_likes = $row['post_likes'];
            $linktoprofile = "<a class='four' href='profile.php?id=" . $row['post_author'] . "'>" . $post_author . "</a>";            
            $commentbutton = $post_id . "commentbutton";
            $loadcomments = $post_id . "load";
            $bump = $post_id . "bumpp";
            
            $post_comment_author = "";
            $post_comment_date = "";
            $post_comment_content = "";            
            
            $data2 = mysqli_query($connection, "SELECT * FROM post_comments WHERE post_id = $post_id ORDER BY id DESC");
            
                // Second while loop to fetch comments for individual posts
                while($row = mysqli_fetch_array($data2)) {
                    
                    $post_comment_author = $row['post_comment_author'];
                    $post_comment_date = $row['post_comment_date'];
                    $post_comment_content = $row['post_comment_content'];
                    
                    $commentstr .= "<span style='font-weight:bold'>" . $post_comment_author . "</span> " . "<span style='color:black'>" . $post_comment_content . "</span>" . "<br>";
                }
            
            $str .= "<div class='postcss'>
            $linktoprofile ($post_date)" . "&nbsp Upvotes: $post_likes <br>
            $post_content

            <button class='loadcomments' id='$loadcomments' onclick='togglecomment($post_id); 'downtoup($loadcomments)'> v </button>";
            
            // Check to see if the user has already upvoted the post
            
            $sql9 = "SELECT * FROM post_upvotes WHERE post_id = $post_id AND upvote_author = '$upvote_author'";
            $data3 = mysqli_query($connection, $sql9);
            $upvoted = false; // assume the user has not upvoted the post yet
            
            while($row = mysqli_fetch_array($data3)) {
                $upvoted = true;
            }
            
            if($upvoted) {
                $str .= "<input type='submit' value='Upvoted' style='float:right'>";
            } else {
                $str .= "<form action='index.php?id=$bump' method='post' class='loadcomments'> <input type='submit' name='$bump' value='Upvote'> </form>";
            }
                        
            $str .=
                        "<br>

                        <div id=$post_id style='display:none'>
                        <form action='index.php?id=$loadcomments' method='post'> 
                            <input id='commenttext' name='$post_id' type='text' placeholder='Write a comment...' style='width: 100%'> <input type='submit' value='Comment' id='commentbutton' name=$commentbutton> 
                        </form> <br>
                        
                        <div> $commentstr </div>
                        </div>
                    </div>" ;            
        }
        
        
        echo $str;

        }

        $querystring = $_SERVER['QUERY_STRING'];
        $querystringlen = strlen($querystring);

        $querystring2 = substr($querystring, 3, $querystringlen - 5);
        $load = substr($querystring, -4);

        $querystring3 = substr($querystring, 3, $querystringlen - 6);
        $bump = substr($querystring, -5);

        if($load == "load") {
            $post_id = intval($querystring2);
            $post_comment_date = date("Y-m-d H:i:s");
            $post_comment_content = $_POST[$post_id];
            
            $post_comment_content = strip_tags($post_comment_content); // removes html tags
            $post_comment_content = mysqli_real_escape_string($connection, $post_comment_content);
            $check_empty = preg_replace('/\s+/','',$post_comment_content); // deletes all spaces
            
            if($check_empty != "") {
            
            $sql = "INSERT INTO post_comments VALUES ('','$post_id','$username','$post_comment_date','$post_comment_content')";
            mysqli_query($connection, $sql);
            }
            header("location:index.php");
        }

        if($bump == "bumpp") {
            $post_id = intval($querystring3);
            
            // Retrieve the number of upvotes
            $sql = "SELECT post_likes FROM posts WHERE post_id=$post_id";
            $result = mysqli_query($connection, $sql);
            
            while($row = mysqli_fetch_assoc($result)) {
                $post_likes = $row['post_likes'];
            }
            
            // Increment the number of upvotes and put back into the database
            $post_likes = $post_likes + 1;
            
            $sql2 = "UPDATE posts SET post_likes='$post_likes' WHERE post_id = '$post_id'";
            mysqli_query($connection, $sql2);
            header("location:index.php");
            
            // Update 'post_upvotes' table
            $sql3 = "INSERT INTO post_upvotes VALUES ('','$post_id', '$username')";
            $result3 = mysqli_query($connection, $sql3);
        }


    function loadAllClubs($data) {
        $str = "<table id='myTable'> <tr> <th> Club Name </th> <th> Admin </th> <th> Status </th> <th> Hrs/Week </th> <th> Rating </th> </tr>";
        $connection = mysqli_connect('localhost','root','','wildclubconnect');

        while ($row = mysqli_fetch_array($data)) {
            $clubAdmin = $row['clubAdmin'];
            $clubCategory = $row['clubCategory'];
            $clubTime = $row['clubTime'];
            $clubName = $row['clubName'];
            $clubRatingCount = $row['clubRatingCount'];
            $clubRatingSum = $row['clubRatingSum'];
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

            if ($clubRatingCount == 0 || $clubRatingSum == 0) {
                $clubRating = "No ratings";
            } else {$clubRating = number_format(round($clubRatingSum / $clubRatingCount,2), 2, '.','');}
                        
            
            $str .= "<tr> <td> <a href='clubpage.php?id=$clubName'>" . $clubName . "</a>" . "</td> <td> $clubAdmin </td> <td>" . $clubStatus .     "</td> <td> $clubTime </td> <td> $clubRating </td> </tr>";
        }
        
        $str .= "</table>";

        echo $str;
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
               <form method='post' action='searchusers.php' style='display:inline-block'> <input type='text' style='width:100%; height: 100%;' placeholder='Search for users...' name='usersearched'> <input type='submit' name='searchusers' value='Search' style='display:none'> </form> 
            <span class='close' onclick='showusers()'>&times</span>
             <br> <br>
            </div>
        </div>

		<div id="topbar">
            <div id="WildClubs"> WildClub Connect </div> 
			
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
           		
           	<div id="grey">
           		           		
            <div id="browse">
                
                                                                    
               <?php if(isset($_SESSION['email']) && $email !== ""): ?>
                    <button class="button" onclick="createpost()" name="create"> Post a quick announcement or question </button>
                <?php else: ?>
                    <div id="signintopost"> Disclaimer: Sign in to post an announcement or comment. </div>  
                <? endif ?>
                
                
                <div id="formdiv"> 
                <form action="index.php" method="post">                    
                     <input type="text" name="content" id="content" placeholder="Content..." style='width: 100%'>
                     <input type="submit" name="contentsubmit1" class="contentsubmit" value="Post">
                </form>
                </div>
                
                <div id="postsection">
                
                        <?php
                                            
                        if (isset($_POST['contentsubmit1'])) {
                            
                            // Delete posts that are more than 48 hours old
                            
                            $sql = "DELETE FROM posts WHERE post_date < NOW() - INTERVAL 72 HOUR";
                            mysqli_query($connection, $sql);
                            
                            $sql = "DELETE FROM post_comments WHERE post_comment_date < NOW() - 72 HOUR";
                            mysqli_query($connection, $sql);
                            
                        
                            // submitting and loading the post
                            $post_author = $username;
                            $post_date = date("Y-m-d H:i:s");
                            $post_content = $_POST['content'];
                            
                            if(strlen($post_content) <= 61) {
                            
                            $post = new Post($connection, $username);
                            $post->submitPost($post_author, $post_date, $post_content);
                            loadAllPosts($username);
                            }
                            
                        } else {
                            loadAllPosts($username);
                            
                        } 
                
                        ?>
                    
                
                </div>
           
            </div>

	<!-- Below is the code for the clubs' list -->

<div id="box">
      
    <?php if(isset($_SESSION['email']) && $email !== ""): ?>  
      
       <div id="creategroupempty"> </div>
    <button onclick="creategroupform()" class="creategroup" id="createagroup" value="Create a club"> Create a club </button> <hr>
    
    <?php endif ?>
   	
   	<div id="search_box"> 
    <div id="search_input" style='padding: none'> 
        <input type="text" id="myInput" onkeyup="filter()" placeholder="Search for WildClubs..." style='width:50%;margin-left:25%'> 
    </div>
    
            <div id='filterdiv' style='margin:none'>
        <form action='index.php' method='post'>
            <table style='width:90%'> 
                <tr> <td> Sort by: <select style='display:inline-block' name='categoryselect'>
                    <option value="none"> Select... </option>
                    <option value="academics"> Academics </option>
                    <option value="arts/performance">Arts/Performance</option>
                    <option value="communityservice">Community Service</option>
                    <option value="cooking">Cooking</option>
                    <option value="culture">Culture</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="Greek">Greek</option>
                    <option value="media">Media/Publication</option>
                    <option value="political">Political/Advocacy</option>
                    <option value= "pre-professional"> Pre-professional </option>
                    <option value="religious"> Religious/Spiritual </option>
                    <option value="sports/recreation"> Sports/Recreation </option>
                </select>
                    </td>
                    
                    <td> <input type="checkbox" name="timecheckbox"> Descending Hrs/Week </td>                
                    <td> <input type="checkbox" name="ratingcheckbox"> Descending rating </td>
                    <td> <input type="submit" name="filtersubmit" value="Filter"> </td>
                    
                </tr>
                
            </table>
        </form>
        </div>
    </div>
      
    
       
	
   <form id="creategroupform" action="index.php" method="post">
     
       <div style="font-size: 12px"> Simply fill out the club details, click on 'create', and your club will be visible to all WildClub Connect members. </div>

      
       <input type="text" name="clubName" id="clubname" placeholder="Club Name"> <br>
       
       Description <br>
       
       <textarea type="text" name="clubDescription"> </textarea> <br>
           
        
           
           <div class="styled-select styled-select1">
           
           <table>
               <tr>
               <td> Hours per week: </td>
               <td>
                   <select name = "clubTime">
                           <option value="1~2"> 1~2 </option>
                           <option value="3~4"> 3~4 </option>
                           <option value="5~7"> 5~7 </option>
                           <option value="8~10"> 8~10 </option>
                           <option value="11~15"> 11~15</option>
                           <option value="16~20"> 16~20 </option>
                           <option value=">20"> > 20 </option>
                    </select>
               </td>
               </tr>
            
               <tr>
               <td> Status: </td>
               <td>
                    <select name = "clubStatus">
                        <option value="Actively Recruiting"> Actively Recruiting </option>
                        <option value="Recruiting"> Recruiting </option>
                        <option value="Limited Recruiting"> Limited Recruiting</option>
                        <option value="Not Recruiting"> Not Recruiting </option>
                    </select> <br>
               </td>
               </tr>
               
               <tr>
               <td> Category: </td>
               <td>
            
                    <select name = "clubCategory">
                        <option value="academics"> Academics </option>
                        <option value="arts/performance">Arts/Performance</option>
                        <option value="communityservice">Community Service</option>
                        <option value="cooking">Cooking</option>
                        <option value="culture">Culture</option>
                        <option value="entertainment">Entertainment</option>
                        <option value="Greek">Greek</option>
                        <option value="media">Media/Publication</option>
                        <option value="political">Political/Advocacy</option>
                        <option value= "pre-professional"> Pre-professional </option>
                        <option value="religious"> Religious/Spiritual </option>
                        <option value="sports/recreation"> Sports/Recreation </option>
                    </select>
               </td>
               </tr>
            
               </table>
        
              </div> 
              
              <input type="submit" name="clubsubmit" class="clubsubmit" value="Create">
           
       
<!--       <input type="checkbox" name="clubpageredirect" id="clubpageredirect" checked> <span id="redirectcss"> Redirect me to my newly created club page </span>-->
   </form>
     
        
        <div id="tablediv">
        
        <?php
    
            if(isset($_POST['filtersubmit'])) {
                $categoryselect = $_POST['categoryselect'];
                if($categoryselect != "Select...") {
                    $data = mysqli_query($connection, "SELECT * FROM clubs WHERE clubCategory = '$categoryselect' ORDER BY clubName");
                }
                

                
                loadAllClubs($data);
            }
    
            if(isset($_POST['clubsubmit'])) {

                $clubAdmin = $username;
                $clubName = $_POST['clubName'];
                $clubDescription = $_POST['clubDescription'];
                $clubCategory = $_POST['clubCategory'];
                $clubTime = $_POST['clubTime'];
                $clubStatus = $_POST['clubStatus'];

                $club = new Club($connection, $clubAdmin);
                $club->submitClub($clubAdmin, $clubName, $clubDescription, $clubCategory, $clubTime, $clubStatus, $username);
                $data = mysqli_query($connection, "SELECT * FROM clubs ORDER BY clubName");
                loadAllClubs($data);
            } else {
                $data = mysqli_query($connection, "SELECT * FROM clubs ORDER BY clubName");
                if(!isset($_POST['filtersubmit'])) {
                    loadAllClubs($data);
                }
            }      
    
        ?>
        
        </div>
     
      
</div>
        
        </div>

		<script>
            
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
            
            function showusers () {
                var x = document.getElementById('modal');
                if(x.style.display == 'block') {
                    x.style.display = 'none';
                } else {
                    x.style.display = 'block';
                }
            }
            
            function togglecomment($post_id) {
                var x = document.getElementById($post_id);
                if(x.style.display == 'block')
                    x.style.display = 'none';
                else
                    x.style.display = 'block';
            }
                
            function downtoup ($loadcomments) {
                console.log("Asdf");
                var y = document.getElementById($loadcomments);
                if(y.innerHTML == "v") {
                    y.innerHTML = "^";
                } else {
                    y.innerHTML = "v";
                }
            }

            function creategroupform() {
                var x = document.getElementById('myTable');
                if (x.style.display == 'none') {
                    x.style.display = 'table';
                } else {
                    x.style.display = 'none';
                }
                
                var y = document.getElementById("search_box");
                if (y.style.display == 'none') {
                    y.style.display = 'block';
                } else {y.style.display = 'none';}
                
                var z = document.getElementById("creategroupform");
                if (z.style.display == 'block') {
                    z.style.display = 'none';
                } else {z.style.display = 'block';}

                var a = document.getElementById("createagroup");
                if (a == "Create a club") {a.innerHTML = "Search for clubs";}
                else {a.innerHTML = "Create a club";}
                
            }
            
            function createpost() {
                var x = document.getElementById('formdiv');
                if (x.style.display == 'block') {
                    x.style.display = 'none';
                } else {
                    x.style.display = 'block';
                }
                
                var y = document.getElementById('postsection');
                if(y.style.height == '86%') {
                    y.style.height = '93%';
                } else {
                    y.style.height = '86%';
                }
            }
            
            function filter() {
              var input, filter, table, tr, td, i;
              input = document.getElementById("myInput");
              filter = input.value.toUpperCase();
              table = document.getElementById("myTable");
              tr = table.getElementsByTagName("tr");
                
              for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                  if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                  } 

                  else {
                    tr[i].style.display = "none";
                  }
                }       
              }
            }

		</script>
		
	</body>
</html>




