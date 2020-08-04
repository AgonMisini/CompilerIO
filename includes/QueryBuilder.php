
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
            $stmt = $this->pdo->prepare('SELECT * FROM ' . $table . ' WHERE ' .$column . ' = :equalto');
            $stmt->bindParam(":equalto", $equalTo);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        

        //Insert user query.
        public function insertUser($userInformation){
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
                    $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, profilepic, admin, bio) VALUES (:username, :email, :password, 'profilepicture/default.jpg', 0, '')");
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
                    header("Location: index.php?success=accountCreated");
                }
            }
        }
        public function insertBio($userId, $bio){
            if(empty($bio)){
                header("Location: index-p.php?id=" . $userId . "&error=emptyBioField");
            }else if(strlen($bio) > 160){
                header("Location: index-p.php?id=" . $userId . "&error=bioExceedingCharacterLimit");
            }else{
                $stmt = $this->pdo->prepare("UPDATE users SET bio = :bio WHERE id = :id");
                $stmt->bindParam(":bio", $bio);
                $stmt->bindParam(":id", $userId);
                $stmt->execute();
                header("Location: index-p.php?id=" . $userId . "&success=bioAdded");
            }
        }
        //Function for logging out.
        public function logout(){
            session_unset();
            session_destroy();
            header("Location: index.php");
        }
        //Query for logging inside the website alongside with the session being set.
        public function login($loginInformation){
            $username = $loginInformation[0];
            $password = $loginInformation[1];
            if(empty($username) || empty($password)){
                header("Location: index-l.php?error=emptyFields");
                exit();
            }else{
                //MySQL for checking if the username exists in the DB
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username ");
                $stmt->bindParam(":username", $username);
                $stmt->execute();
                $userCheck = $stmt->fetchColumn();
                //If there's no such username, exit script.
                if($userCheck == 0){
                    header("Location: index-l.php?error=usernameNotFoundOrIncorrectPassword");
                }else{
                    //Grabbing the passwords and checking if they match.
                    $stmt = $this->pdo->prepare("SELECT password FROM users WHERE username = :username");
                    $stmt->bindParam(":username", $username);
                    $stmt->execute();
                    $dbPassword = $stmt->fetchColumn();
                    $passwordCheck = password_verify($password, $dbPassword);
                    if($passwordCheck == false){
                        
                        header("Location: index-l.php?error=usernameNotFoundOrIncorrectPassword");
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
                        header("Location: index.php?success=loggedInSuccessfully");
                    }else{
                        header("Location: index-l.php?error=usernameNotFoundOrIncorrectPassword");
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
        public function insertForumPost($forumPostInformation, $orderBy){
            $currPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
            $userId = $forumPostInformation[0];
            $forumPostTitle = $forumPostInformation[1];
            $forumPostContent = $forumPostInformation[2];
            $category = $forumPostInformation[3];
            //Check if the fields are empty.
            if(empty($forumPostTitle) || empty($forumPostContent || empty($category))){
                header("Location: " . $currPageName . "?error=emptyFields&category=" . $category . "&id=" . $userId);
                exit();
            }else if(strlen($forumPostContent) > 4999){
                header("Location: " . $currPageName . "?error=postTooLong&category=" . $category . "&id=" . $userId);
                exit();
            }else{
                //Insert forum post
                $stmt = $this->pdo->prepare('INSERT INTO forumposts (userid, forumposttitle, forumpostcontent, category, timeposted, likes) VALUES(:userid, :forumposttitle, :forumpostcontent, :category, now(), 0)');
                $stmt->bindParam(":userid",$userId);
                $stmt->bindParam(":forumposttitle",$forumPostTitle);
                $stmt->bindParam(":forumpostcontent",$forumPostContent);
                $stmt->bindParam(":category", $category);
                $stmt->execute();   
                header("Location: index-subforum.php?success=postAdded&category=" . $category . "&id=" . $userId . "&orderBy=" . $orderBy . "&page=1");
            }
        }
        //Query for inserting a comment on a forum post
        public function insertForumComment($forumCommentInformation, $category){
            $forumPostId = $forumCommentInformation[0];
            $userId = $forumCommentInformation[1];
            $forumComment = $forumCommentInformation[2];
            //Check if field is empty
            if(empty($forumComment)){
                header("Location: index-forum-topic-posts.php?id=" . $forumPostId . "&category=" . $category . "&error=emptyFields");
                exit();
            }else{
                //Insert forum post comment
                $stmt = $this->pdo->prepare('INSERT INTO forumcomment (forumpostid, userid, forumcomment, timeCommented) VALUES (:forumpostid, :userid, :forumcomment, now())');
                $stmt->bindParam(":forumpostid", $forumPostId);
                $stmt->bindParam(":userid", $userId);
                $stmt->bindParam(":forumcomment",$forumComment);
                $stmt->execute();
                header("Location: index-forum-topic-posts.php?id=" . $forumPostId . "&category=" . $category);
            }
        }
        //Query for liking a forum post while checking if the post has been liked by the same person before.
        public function likeForumPost($forumPostId ,$userId){
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM forumpost_user_like WHERE userid = " . $userId . " AND forumpostid = " . $forumPostId);
            $checkIfUserLiked = $stmt->fetchColumn();
            if($checkIfUserLiked == 1){
                $stmt = $this->pdo->query("UPDATE forumposts SET likes = likes - 1 WHERE id = " . $forumPostId);
                $stmt = $this->pdo->query("DELETE FROM forumpost_user_like WHERE userid = " . $userId . " AND forumpostid = " . $forumPostId);
            }else if($checkIfUserLiked == 0){
                $stmt = $this->pdo->query("UPDATE forumposts SET likes = likes + 1 WHERE id = " . $forumPostId);
                $stmt = $this->pdo->query("INSERT INTO forumpost_user_like (forumpostid, userid) VALUES (" . $forumPostId . ", " . $userId . ")");
            }
           
        }

        //The function & query for inserting a contact us message.
        public function insertContactUsMessage($messageInformation){
            $name = $messageInformation[0];
            $email = $messageInformation[1];
            $subject = $messageInformation[2];
            $message = $messageInformation[3];

            if(empty($name) || empty($email) || empty($subject) || empty($message)){
                header("Location: contact-us.php?error=emptyFields");
                exit();
            }else{
                $stmt = $this->pdo->prepare("INSERT INTO contactmessages (name, email, subject, message, timesent) VALUES (:name, :email, :subject, :message, now())");
                $stmt->bindParam(":name", $name);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":subject", $subject);
                $stmt->bindParam(":message", $message);
                $stmt->execute();
                header("Location: index.php");
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
        public function getNumberOfPagesForSpecificCategory($category){
            $stmt = $this->pdo->prepare('SELECT id FROM category WHERE name = :name ');
            $stmt->bindParam(":name", $category);
            $stmt->execute();
            $categoryId = $stmt->fetchColumn();

            $resultsPerPage = 5;

            $stmt = $this->pdo->query("SELECT * FROM postcategories WHERE categoryid = " . $categoryId);
            $posts = $stmt->fetchAll();
            $numberOfPosts = count($posts);

            $numberOfPages = ceil($numberOfPosts/$resultsPerPage);
            return $numberOfPages;

        }

        //Same as the getPage() method but for our forum posts.
        public function getForumPosts($page, $category, $orderBy){
            $resultsPerPage = 5;

            $stmt = $this->pdo->prepare('SELECT * FROM forumposts WHERE category = :category');
            $stmt->bindParam(":category", $category);
            $stmt->execute();
            $posts = $stmt->fetchAll();
            $numberOfPosts = count($posts);

            $numberOfPages = ceil($numberOfPosts/$resultsPerPage);
     
            $thisPageFirstResult = ($page-1)*$resultsPerPage;

            if($orderBy == "Newest"){
                $stmt = $this->pdo->prepare('SELECT * FROM forumposts WHERE category = :category ORDER BY timeposted DESC LIMIT ' . $thisPageFirstResult . "," . $resultsPerPage);
                $stmt->bindParam(":category", $category);
                $stmt->execute();
                return $stmt->fetchAll();
            }else if($orderBy == "Oldest"){
                $stmt = $this->pdo->prepare('SELECT * FROM forumposts WHERE category = :category ORDER BY timeposted ASC LIMIT ' . $thisPageFirstResult . "," . $resultsPerPage);
                $stmt->bindParam(":category", $category);
                $stmt->execute();
                return $stmt->fetchAll();
            }else if($orderBy == "Most_popular"){
                $stmt = $this->pdo->prepare('SELECT * FROM forumposts WHERE category = :category ORDER BY likes DESC LIMIT ' . $thisPageFirstResult . "," . $resultsPerPage);
                $stmt->bindParam(":category", $category);
                $stmt->execute();
                return $stmt->fetchAll();
            }
        }
        //same as numberOfPages but for forum pagination.
        public function numberOfPagesForum($category){
            $resultsPerPage = 5;
            
            $stmt = $this->pdo->prepare('SELECT * FROM forumposts WHERE category = :category');
            $stmt->bindParam(":category", $category);
            $stmt->execute();
            $posts = $stmt->fetchAll();
            $numberOfPosts = count($posts);

            $numberOfPages = ceil($numberOfPosts/$resultsPerPage);

            return $numberOfPages;
        }

        //Function for getting the forum posts a user has made
        public function getUserForumPosts($page, $userId){
            $resultsPerPage = 5;

            $stmt = $this->pdo->prepare('SELECT * FROM forumposts WHERE userid = :userid');
            $stmt->bindParam(":userid", $userId);
            $stmt->execute();
            $posts = $stmt->fetchAll();
            $numberOfPosts = count($posts);

            $numberOfPages = ceil($numberOfPosts/$resultsPerPage);
     
            $thisPageFirstResult = ($page-1)*$resultsPerPage;

            $stmt = $this->pdo->prepare("SELECT * FROM forumposts WHERE userid = :userid ORDER BY timeposted DESC LIMIT " . $thisPageFirstResult . "," . $resultsPerPage);
            $stmt->bindParam(":userid", $userId);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        //Function about returning the number of posts the user had made on the forum
        public function numberOfPagesUser($userId){
            $resultsPerPage = 5;
            
            $stmt = $this->pdo->prepare('SELECT * FROM forumposts WHERE userid = :userid');
            $stmt->bindParam(":userid", $userId);
            $stmt->execute();
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
            $stmt = $this->pdo->query("DELETE FROM forumpost_user_like WHERE forumpostid = " . $forumPostId);
            $stmt = $this->pdo->query("DELETE FROM forumposts WHERE id = " . $forumPostId);
            
            header("Location: index-subforum.php?category=" . $category . "&orderBy=Newest&page=1");
        }
        //Delete a forum post comment - Available to the user who commented and to an admin.
        public function deleteForumComment($forumCommentId){
            $query_strings = $_SERVER['QUERY_STRING'];
            $stmt = $this->pdo->query("DELETE FROM forumcomment WHERE id = " . $forumCommentId);

            header("Location: forumPost.php?" . $query_strings);
        }


        //Function about changing your user information
        public function changeProfileInformation($userInformation){
            $username = $userInformation[0];
            $email = $userInformation[1];
            $password = $userInformation[2];
            $confirmPassword = $userInformation[3];
            $userId = $userInformation[4];
            
            if(empty($username) && empty($email) && empty($password) && empty($confirmPassword)){
                header("Location: index-p.php?error=emptyFields&id=" . $userId);
                exit;
            }
            $stmt = $this->pdo->prepare('SELECT id FROM users WHERE username = :username');
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $dbUserIdUsername = $stmt->fetchColumn();

            if($dbUserIdUsername != $userId && $dbUserIdUsername != false){
                    header("Location: index-p.php?error=usernameTaken&id=" . $userId);
                    exit();
            }
            $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = :email');
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $dbUserIdEmail = $stmt->fetchColumn();
            var_dump($dbUserIdEmail);
            if($dbUserIdEmail != $userId && $dbUserIdEmail != false){
                    header("Location: index-p.php?error=emailTaken&id=" . $userId);
                    exit();
            }

            if(empty($password) && empty($confirmPassword)){
                $stmt = $this->pdo->prepare("UPDATE users SET username = :username, email = :email WHERE id = " . $userId);
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":email", $email);
                $stmt->execute();
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                header("Location: index-p.php?success=successfulChange&id=" . $userId);
                exit();
            }else if($password != $confirmPassword){
                header("Location: index-p.php?error=passwordDoesntMatch&id=" . $userId);
                exit();
            }else if(strlen($password) < 8 || strlen($password) > 26){
                header("Location: index-p.php?error=passwordTooShortOrTooLong&id=" . $userId);
                exit();
            }else{
                $stmt = $this->pdo->prepare('SELECT password FROM users WHERE id = :userId');
                $stmt->bindParam(':userId', $userId);
                $stmt->execute();
                $dbPassword = $stmt->fetchColumn();
                if(password_verify($password, $dbPassword)){
                    header("Location: index-p.php?error=samePasswordAsBefore&id=" . $userId);
                    exit();
                }else{
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $this->pdo->prepare("UPDATE users SET username = :username, email = :email, password = :password WHERE id = :userId");
                    $stmt->bindParam(":userId", $userId);
                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":password", $hashedPassword);
                    $stmt->execute();

                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;

                    header("Location: index-p.php?success=successfulChange&id=" . $userId);
                    exit();
                }
            }
        }
        //Function about changing your profile picture
        public function changeProfilePicture($userId){
            $photo = $_FILES['photo']['name'];
            $profilepic = "profilepicture/".basename($photo);
            $stmt = $this->pdo->prepare("UPDATE users SET profilepic = :profilepic WHERE id = :userId");
		    $stmt->bindParam(":profilepic", $profilepic);
		    $stmt->bindParam(":userId", $userId);
            $stmt->execute();

            if(move_uploaded_file($_FILES['photo']['tmp_name'], $profilepic)) {
                $msg = "Image uploaded successfully";
                // header ('Location: profilepage.php?userId=' . $userId);
                header("Location: index-p.php?success=profilePicChanged&id=" . $userId);
            }else{
                $msg = "Failed to upload image";
                header ('Location: index-p.php?error=failedToUploadImage&id=' . $userId);
            }
        }
        //Function about deleting an entire user along with his posts, comments. -TODO- Figure out how to delete the profile picture too.
        public function deleteUser($userId){
            //Checks if the user is an admin or not.
            $stmt = $this->pdo->query('SELECT admin FROM users WHERE id = ' . $userId);
            $result = $stmt->fetchColumn();
            //Checks if the user is an admin or not, since only admins can post newsposts so it would be pointless to run the code below on a normal user.
            if($result){
                //Selects the newsposts information and deletes the postid on postcategories.
                $stmt = $this->pdo->query('SELECT * FROM newsposts WHERE userid = ' . $userId);
                $ids = $stmt->fetchAll();
                foreach($ids as $postId){
                    $stmt = $this->pdo->query('DELETE FROM postcategories WHERE postid = ' . $postId['id']);
                }
                $stmt = $this->pdo->query('DELETE FROM newsposts WHERE userid = ' . $userId);
            }
            //Deletes the news comments the user has made.
            $stmt = $this->pdo->query('DELETE FROM newscomment WHERE userid = ' . $userId);

            //Deletes the forum posts the user has made.
            $stmt = $this->pdo->query('DELETE FROM forumposts WHERE userid = ' . $userId);

            //Deletes the forum comments the user has made.
            $stmt = $this->pdo->query('DELETE FROM forumcomment WHERE userid = ' . $userId);
        
            //And finally delete the user and send them back to the main page. If the user deactivated his account then we unset the session and destroy it.
            if($_SESSION['userId'] == $userId){
                session_unset();
                session_destroy();
                $stmt = $this->pdo->query('DELETE FROM users WHERE id = ' . $userId);

                header("Location: index.php?success=userWasDeletedSuccessfully");
            }else{
                $stmt = $this->pdo->query('DELETE FROM users WHERE id = ' . $userId);
                header("Location: index.php?success=userWasDeletedSuccessfully");
            }
        }
        //Function to delete a contact us message.
        public function deleteContactMessage($postId){
            $stmt = $this->pdo->query("DELETE FROM contactmessages WHERE id = " . $postId);
            header("Location: contactMessages.php");
        }
    }
?>
