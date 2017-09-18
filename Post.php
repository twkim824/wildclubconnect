<?php

class Post {
    private $username;
    private $connection;
    
    public function __construct ($connection, $username) {
        $this->connection = $connection;
        $user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='username'");
        $this->user = mysqli_fetch_array($user_details_query);
    }


    public function submitPost($post_author, $date_added, $body) {
        $body = strip_tags($body); // removes html tags
        $body = mysqli_real_escape_string($this->connection, $body);
        $check_empty = preg_replace('/\s+/','',$body); // deletes all spaces
    
        if ($check_empty != "") {
        
            // Current date and time
            $date_added = date("Y-m-d H:i:s");
        
            // Insert post into database
            $query = mysqli_query($this->connection, "INSERT INTO posts VALUES('', '$post_author', '$date_added', '$body','0')");
            $returned_id = mysqli_insert_id($this->connection);
            
            
        } else {
            echo "you didnt type shit";
        }
    }
    

    public function loadMyPost() {
        $str = "";
        $data = mysqli_query($this->connection, "SELECT * FROM posts WHERE username='$this->username'");
        
        $x = 0;
        $row = mysqli_fetch_array($data);
        
        while($x < 1) {
            
            $post_content = $row['post_content'];
            
            $str .= "<div class='postcss'>
                        
                        $post_content
                
                    </div>" ;
            echo $str;
            $x++;
        }
    }
}

?>
