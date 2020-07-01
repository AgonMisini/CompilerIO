
<?php 
    class QueryBuilder{
        protected $pdo;
        public function __construct(PDO $pdo){
            $this->pdo = $pdo;
        }

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
        public function insertNewsPost($table, $newsPostInformation){
            $currPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
            $userId = $newsPostInformation[0];
            $newsPostTitle = $newsPostInformation[1];
            $newsPostContent = $newsPostInformation[2];
            $newsPostCategory = $newsPostCategory[3];

            //Check if the fields are empty.
            if(empty($newsPostTitle) || empty($newsPostContent) || empty($newsPostCategory)){
                header("Location: " . $currPageName . "?error=emptyFields");
                exit();
            }else if(strlen($newsPostContent) > 15000){
                header("Location: " . $currPageName . "?error=postTooLong");
                exit();
            }else{
                $stmt = $this->pdo->prepare('INSERT INTO newsposts (userid, newsposttitle, newspostcontent, category, timeposted) VALUES(:userid, :newsposttitle, :newspostcontent, :category, now())');
                $stmt->bindParam(":userid",$userId);
                $stmt->bindParam(":newsposttitle",$newsPostTitle);
                $stmt->bindParam(":newspostcontent",$newsPostContent);
                $stmt->bindParam(":category",$newsPostCategory);
                $stmt->execute();

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
            $forumPostCategory = $forumPostCategory[3];

            //Check if the fields are empty.
            if(empty($forumPostTitle) || empty($forumPostContent) || empty($forumPostCategory)){
                header("Location: " . $currPageName . "?error=emptyFields");
                exit();
            }else if(strlen($forumPostContent) > 4999){
                header("Location: " . $currPageName . "?error=postTooLong");
                exit();
            }else{
                $stmt = $this->pdo->prepare('INSERT INTO forumposts (userid, forumposttitle, forumpostcontent, category, timeposted) VALUES(:userid, :forumposttitle, :forumpostcontent, :category, now())');
                $stmt->bindParam(":userid",$userId);
                $stmt->bindParam(":forumposttitle",$forumPostTitle);
                $stmt->bindParam(":forumpostcontent",$forumPostContent);
                $stmt->bindParam(":category",$forumPostCategory);
                $stmt->execute();

                header("Location: index.php?success");
            }
        }
        public function insertForumComment($table, $forumCommentInformation){
            $forumPostId = $forumCommentInformation[0];
            $userId = $forumCommentInformation[1];
            $forumComment = $forumCommentInformation[2];
            if(empty($forumComment)){
                header("Location: forumPost.php?error=emptyField");
                exit();
            }else{
                $stmt = $this->pdo->prepare('INSERT INTO forumcomment (forumpostid, userid, forumcomment, timeCommented) VALUES (:forumpostid, :userid, :forumcomment, now())');
                $stmt->bindParam(":forumpostid", $forumPostId);
                $stmt->bindParam(":userid", $userId);
                $stmt->bindParam(":forumcomment",$forumComment);
                $stmt->execute();
                header("Location: forumPost.php?success");
                exit();
            }
        }
    }
?>
