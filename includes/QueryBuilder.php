
<?php 
    class QueryBuilder{
        protected $pdo;
        public function __construct(PDO $pdo){
            $this->pdo = $pdo;
        }
        //Selects everything from the table.
        public function selectAll($table){
            $stmt = $this->pdo->query('SELECT * FROM ' . $table );
            return $stmt->fetchAll();
        }
        //Selects everything from the table with a condition.
        public function selectAllWhere($table, $column, $equalTo){
            $stmt = $this->pdo->query('SELECT * FROM ' . $table . ' WHERE ' .$column . ' = ' . $equalTo);
            return $stmt->fetchAll();
        }
        

        //Insert user query.
        public function insertUser($table, $userInformation){
            $username = $userInformation[0];
            $email = $userInformation[1];
            $password = $userInformation[2];
            $confirmPassword = $userInformation[3];

            //Check if the fields are empty, if so, send the user back and exit script.
            if(empty($username) || empty($email) || empty($password) || empty($confirmPassword)){
                header("Location index.php?error=emptyField");
                exit();
            }else{
                //MySQL for checking if the username that already exists in the DB
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username ");
                $stmt->bindParam(":username", $username);
                $stmt->execute();
                $userCheck = $stmt->fetchColumn();

                //MySQL for checking if the email that already exists in the DB
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email ");
                $stmt->bindParam(":email", $email);
                $stmt->execute();
                $emailCheck = $stmt->fetchColumn();

                //If there already exists such username, exit.
                if($userCheck == 1){
                    header("Location: index.php?error=nameAlreadyTaken");
                    exit();
                }
                //If there already exists such email, exit.
                else if($emailCheck == 1){
                    header("Location: index.php?error=EmailAlreadyTaken");
                    exit();
                }
                //Check if the passwords match.
                else if($password !== $confirmPassword){
                    header("Location: index.php?error=MismatchingPasswords");
                    exit();
                }
                //Check the passwords length.
                else if(strlen($password) < 8 || strlen($password) > 26){
                    header("Location: index.php?error=passwordShorterThan8CharactersOrLongerThan26Characters");
                    exit();
                }else{
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, profilepic, admin) VALUES (:username, :email, :password, 'profilepicture/default.jpg', 0)");
                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":password", $hashedPassword);
                    $stmt->execute();
                    

                    //Setting up the session and taking the user to the index page logged in,
                    $stmt2 = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
                    $stmt2->bindParam(":username", $username);
                    $stmt2->execute();
                    $user = $stmt2->fetchAll();
                    session_start();
                    foreach ($user as $user) {
                        $_SESSION['userId'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['admin'] = $user['admin'];
                        $_SESSION['logged_in'] = 1;
                    }
                    header("Location: index.php?successLogin");
                }
            }
        }
        //Query for logging inside the website alongside with the session being set.
        public function login($table, $loginInformation){
            $currPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
            $url = $_SERVER['QUERY_STRING'];
            echo $url;
            $username = $loginInformation[0];
            $password = $loginInformation[1];
            if(empty($username) || empty($password)){
                header("Location: " .  $currPageName .  "?" . $url . "&error=emptyFields");
                exit();
            }else{
                //MySQL for checking if the username exists in the DB
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username ");
                $stmt->bindParam(":username", $username);
                $stmt->execute();
                $userCheck = $stmt->fetchColumn();
                //If there's no such username, exit script.
                if($userCheck == 0){
                    header("Location: " . $currPageName . "?" . $url . "&error=UsernameNotFound");
                }else{
                    //Grabbing the passwords and checking if they match.
                    $stmt = $this->pdo->prepare("SELECT password FROM users WHERE username = :username");
                    $stmt->bindParam(":username", $username);
                    $stmt->execute();
                    $dbPassword = $stmt->fetchColumn();
                    $passwordCheck = password_verify($password, $dbPassword);
                    if($passwordCheck == false){
                        
                        header("Location: " . $currPageName . "?" . $url . "&error=wrongPassword");
                        exit();
                    }else if($passwordCheck == true){
                        $stmt2 = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
                        $stmt2->bindParam(":username", $username);
                        $stmt2->execute();
                        $user = $stmt2->fetchAll();
                        session_start();
                        foreach ($user as $user) {
                            $_SESSION['userId'] = $user['id'];
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['admin'] = $user['admin'];
                            $_SESSION['logged_in'] = 1;
                        }
                       header("Location: " . $currPageName . "?" . $url . "&successLogin");
                    }else{
                        header("Location: " . $currPageName ."?error=passworddoesntmatch");
                        exit();
                    }
                }
            } 
        }
        //Query for inserting a news post
        public function insertNewsPost($table, $newsPostInformation, $categoriesSelected){
            $currPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
            $userId = $newsPostInformation[0];
            $newsPostTitle = $newsPostInformation[1];
            $newsPostContent = $newsPostInformation[2];
      

            //Check if the fields are empty.
            if(empty($newsPostTitle) || empty($newsPostContent)){
                header("Location: " . $currPageName . "?error=emptyFields");
                exit();
            }else if(strlen($newsPostContent) > 15000){
                header("Location: " . $currPageName . "?error=postTooLong");
                exit();
            }else{
                //Insert news post
                $stmt = $this->pdo->prepare('INSERT INTO newsposts (userid, newsposttitle, newspostcontent, timeposted) VALUES(:userid, :newsposttitle, :newspostcontent, now())');
                $stmt->bindParam(":userid",$userId);
                $stmt->bindParam(":newsposttitle",$newsPostTitle);
                $stmt->bindParam(":newspostcontent",$newsPostContent);
                $stmt->execute();

                //Insert the categories of the news post in a different table that contains both the postid and categoryid.
                $postId = $this->pdo->lastInsertId();
                foreach($categoriesSelected as $category){
                    $stmt = $this->pdo->query('INSERT INTO postcategories (postid, categoryid) VALUES (' . $postId . ', ' . $category . ')');
                }

                header("Location: index.php?success");
            }
        }
        public function insertNewsComment($table, $newsCommentInformation){
            $newsPostId = $newsCommentInformation[0];
            $userId = $newsCommentInformation[1];
            $newsCommentText = $newsCommentInformation[2];
            if(empty($newsCommentText)){
                header("Location: newsPost.php?error=emptyField");
                exit();
            }else{
                $stmt = $this->pdo->prepare('INSERT INTO newscomment (newspostid, userid, newscommenttext, timeCommented) VALUES (:newspostid, :userid, :newscommenttext, now())');
                $stmt->bindParam(":newspostid", $newsPostId);
                $stmt->bindParam(":userid", $userId);
                $stmt->bindParam(":newscommenttext",$newsCommentText);
                $stmt->execute();
                header("Location: newsPost.php?success");
                exit();
            }
        }
        public function insertForumPost($table, $forumPostInformation){
            $currPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
            $userId = $forumPostInformation[0];
            $forumPostTitle = $forumPostInformation[1];
            $forumPostContent = $forumPostInformation[2];
            $category = $forumPostInformation[3];
            //Check if the fields are empty.
            if(empty($forumPostTitle) || empty($forumPostContent || empty($category))){
                header("Location: " . $currPageName . "?error=emptyFields");
                exit();
            }else if(strlen($forumPostContent) > 4999){
                header("Location: " . $currPageName . "?error=postTooLong");
                exit();
            }else{
                //Insert forum post
                $stmt = $this->pdo->prepare('INSERT INTO forumposts (userid, forumposttitle, forumpostcontent, category, timeposted) VALUES(:userid, :forumposttitle, :forumpostcontent, :category, now())');
                $stmt->bindParam(":userid",$userId);
                $stmt->bindParam(":forumposttitle",$forumPostTitle);
                $stmt->bindParam(":forumpostcontent",$forumPostContent);
                $stmt->bindParam(":category", $category);
                $stmt->execute();   
                header("Location: index.php?success");
            }
        }
        public function insertForumComment($table, $forumCommentInformation){
            $forumPostId = $forumCommentInformation[0];
            $userId = $forumCommentInformation[1];
            $forumComment = $forumCommentInformation[2];
            //Check if field is empty
            if(empty($forumComment)){
                header("Location: forumPost.php?error=emptyField");
                exit();
            }else{
                //Insert forum post comment
                $stmt = $this->pdo->prepare('INSERT INTO forumcomment (forumpostid, userid, forumcomment, timeCommented) VALUES (:forumpostid, :userid, :forumcomment, now())');
                $stmt->bindParam(":forumpostid", $forumPostId);
                $stmt->bindParam(":userid", $userId);
                $stmt->bindParam(":forumcomment",$forumComment);
                $stmt->execute();
                header("Location: forumPost.php?");
                exit();
            }
        }
        //Function to retrieve the pages from the newsposts table.
        //
        public function getPages($page){
            //Defining the amount of posts per page.
            $resultsPerPage = 5;

            //Getting the number of posts from the database.
            $stmt = $this->pdo->query('SELECT * FROM newsposts');
            $posts = $stmt->fetchAll();
            $numberOfPosts = count($posts);

            //Determining the number of total pages available.
            $numberOfPages = ceil($numberOfPosts/$resultsPerPage);

            //Determine the sql limit starting number for the results on the displaying page.
            $thisPageFirstResult = ($page-1)*$resultsPerPage;

            //Retrieve selected results from database
            $stmt = $this->pdo->query('SELECT * FROM newsposts ORDER BY timeposted DESC LIMIT ' . $thisPageFirstResult . "," . $resultsPerPage);
            return $stmt->fetchAll();
        }
        public function numberOfPages(){
            $resultsPerPage = 5;
            
            $stmt = $this->pdo->query('SELECT * FROM newsposts');
            $posts = $stmt->fetchAll();
            $numberOfPosts = count($posts);

            $numberOfPages = ceil($numberOfPosts/$resultsPerPage);

            return $numberOfPages;
        }
    }
?>
