                <?php
                    if(isset($_POST['searchusers'])) {
                        $usersearched = $_POST['usersearched'];
                        
                        $sql = "SELECT * FROM users WHERE username LIKE '%$usersearched%'";
                        $connection = mysqli_connect('localhost', 'root', '','wildclubconnect');
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
                                
                                $adminstring = "(Admin of: $club1 $club2 $club3" . ")";
                                
                                $coadminstring = "(Coadmin of: )"
                                    
                                $sql = "SELECT * FROM coadmins WHERE coadmin = $username";
                                $result = mysqli_query($connection, $sql);
                                
                                $string .= "<br> $linktoprofile" . " " . $adminstring;
                            }
                        }
                        
                        echo $string;
                    }
                ?>