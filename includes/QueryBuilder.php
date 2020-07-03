
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
        //Query for inserting a comment on a news post
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
        //Query for inserting a forum post
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
        //Query for inserting a comment on a forum post
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
        //Function to retrieve the pages from the newsposts table, limiting them depending on the number we want, and after that displaying them
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
        //Function to get the number of pages of our pagination
        public function numberOfPages(){
            $resultsPerPage = 5;
            
            $stmt = $this->pdo->query('SELECT * FROM newsposts');
            $posts = $stmt->fetchAll();
            $numberOfPosts = count($posts);

            $numberOfPages = ceil($numberOfPosts/$resultsPerPage);

            return $numberOfPages;
        }
        //getPagesForSpecificCategory - Same as getPages, but this time for a specific category.
        public function getPagesForSpecificCategory($page, $category){
            $stmt = $this->pdo->prepare('SELECT id FROM category WHERE name = :name ');
            $stmt->bindParam(":name", $category);
            $stmt->execute();
            $categoryId = $stmt->fetchColumn();

            $resultsPerPage = 5;

            $stmt = $this->pdo->query("SELECT * FROM postcategories WHERE categoryid = " . $categoryId);
            $posts = $stmt->fetchAll();
            $numberOfPosts = count($posts);

            $numberOfPages = ceil($numberOfPosts/$resultsPerPage);

            $thisPageFirstResult = ($page-1)*$resultsPerPage;

            //Retrieve selected results from database
            $stmt = $this->pdo->prepare('SELECT * FROM newsposts INNER JOIN postcategories ON newsposts.id = postcategories.postid WHERE postcategories.categoryid = :categoryId ORDER BY newsposts.timeposted DESC LIMIT ' . $thisPageFirstResult . "," . $resultsPerPage);
            $stmt->bindParam(":categoryId", $categoryId);
            $stmt->execute();
            return $stmt->fetchAll();
            
        }

        //Same as the getPage() method but for our forum posts.
        public function getForumPosts($page, $category){
            $resultsPerPage = 5;

            $stmt = $this->pdo->query('SELECT * FROM forumposts WHERE category = ' . $category);
            $posts = $stmt->fetchAll();
            $numberOfPosts = count($posts);

            $numberOfPages = ceil($numberOfPosts/$resultsPerPage);
     
            $thisPageFirstResult = ($page-1)*$resultsPerPage;

            $stmt = $this->pdo->prepare('SELECT * FROM forumposts WHERE category = :category ORDER BY timeposted DESC LIMIT ' . $thisPageFirstResult . "," . $resultsPerPage);
            $stmt->bindParam(":category", $category);
            return $stmt->fetchAll();
        }
        //same as numberOfPages but for forum pagination.
        public function numberOfPagesForum($category){
            $resultsPerPage = 5;
            
            $stmt = $this->pdo->query('SELECT * FROM forumposts WHERE category = ' . $category);
            $posts = $stmt->fetchAll();
            $numberOfPosts = count($posts);

            $numberOfPages = ceil($numberOfPosts/$resultsPerPage);

            return $numberOfPages;
        }
        /*Functions about deleting posts & comments.*/

        //Delete news post function - Available only to the admin.
        public function deleteNewsPost($newsPostId){
            $stmt = $this->pdo->query("DELETE FROM newscomment WHERE newspostid = " . $newsPostId);
            $stmt = $this->pdo->query("DELETE FROM postcategories WHERE postid = " . $newsPostId);
            $stmt = $this->pdo->query("DELETE FROM newsposts WHERE id = " . $newsPostId);

            header("Location: index.php?success=newsPostDeleted");
        }
        //Delete a news comment function - Available to the user who commented and an admin.
        public function deleteNewsComment($newsCommentId){
            $query_strings = $_SERVER['QUERY_STRING'];
            $stmt = $this->pdo->query("DELETE FROM newscomment WHERE id = " . $newsCommentId);

            header("Location: newsPost.php?" . $query_strings);
        }
        //Delete forum post function - Available to the user who posted it and to an admin.
        public function deleteForumPost($forumPostId, $category){
            $stmt = $this->pdo->query("DELETE FROM forumcomment WHERE forumpostid = " . $forumPostId);
            $stmt = $this->pdo->query("DELETE FROM forumposts WHERE id = " . $forumPostId);
            
            header("Location: forum.php?category=" . $category);
        }
        //Delete a forum post comment - Available to the user who commented and to an admin.
        public function deleteForumComment($forumCommentId){
            $query_strings = $_SERVER['QUERY_STRING'];
            $stmt = $this->pdo->query("DELETE FROM forumcomment WHERE id = " . $forumCommentId);

            header("Location: forumPost.php?" . $query_strings);
        }
    }
?>
