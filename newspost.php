<?php 
    require 'includes/Connection.php';
    require 'includes/QueryBuilder.php';
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    session_start();
    $newspostId = $_GET['id'];

    $newsPost = $query->selectAllWhere("newsposts", "id", $newspostId);
    
    foreach($newsPost as $newsPost){
        $title = $newsPost['newsposttitle'];
        $content = $newsPost['newspostcontent'];
        $date = date('H:i:s A M/d', strtotime($newsPost['timeposted']));
        $userIdAdmin = $newsPost['userid'];
    }
    $userInfo = $query->selectAllWhere("users", "id", $userIdAdmin);
    foreach($userInfo as $user){
        $usernameAdmin = $user['username'];
        $profilePicture = $user['profilepic'];
    }

    $comments = $query->selectAllWhere("newscomment","newspostid", $newspostId);
    $commentsMade = count($comments);

    if(isset($_POST['submitComment'])){
        $commentContent = nl2br($_POST['commentTextArea']);
        $commentUserId = $_SESSION['userId'];

        $newsCommentInfo = array($newspostId,$commentUserId,$commentContent);

        $query->insertNewsComment($newsCommentInfo);
    }
    if(isset($_POST['editNewsPost'])){
        $newsPostContent = nl2br($_POST['editNewsPostArea']);
        $query->editNewsPost($newsPostContent, $newspostId);
    }
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="css/news-post.css">
    <title>News Post</title>
</head>
<body>
    <?php include "includes/Navigation-bar.php"; ?>
    <div class="justify-center">
        <h1 class="title1">News Post</h1>
    </div>
    <div class="post-main-container">
        <div class="top go-row align-center">
            <img src="<?php echo $profilePicture; ?>" alt="Admin-Profile-Pic">
            <p id="admin-name"><a style="color: #6495ED;"href="index-p.php?id=<?php echo $userId;?>"><?php echo $usernameAdmin; ?></a></p> <i class="fas fa-angle-right"></i>
            <p id='admin-category-indicator'>News/Categories/ <?php $categories = $query->selectAllWhere("postcategories", "postid", $_GET['id']);
            foreach($categories as $category){
                $stmt = $conn->query('SELECT name FROM category WHERE id = ' . $category['categoryid']);
                $categoryLink = $stmt->fetchColumn();
                echo '<a style="display: inline; color: #6495ED;" href="index.php?category=' . $categoryLink . '">' . $categoryLink .'</a>/';
            }
            ?></p>  

            <div class="go-row">
            
            <?php if(isset($_SESSION['logged_in'])): ?>
                <span style="margin: 0 5px; font-size: 15px; "><?php echo "Posted on: " . $date; ?></span>

                <?php else: ?>
                <a  style="color: #6495ED; margin: 0 5px; font-size: 10px;" href="index-l.php">Login/Create Account to comment</a>

            <?php endif; ?>
            </div>  

            <span style="margin: 0 5px; font-size: 15px; "><?php echo "replies (" . $commentsMade . ")"; ?></span>

            <div class="admin-dropdown-post-menu-container">

                <?php if(isset($_SESSION['logged_in'])): ?>
                        <?php if($_SESSION['userId'] == $userIdAdmin || $_SESSION['admin'] == 1): ?>
                            <button class="admin-dropdown-post-menu-button">···</button>
                            <ul>
                                <li onclick="fun1()" class="button-edit admin-list-button"><a href="#">Edit</a></li>
                                <li class="button-remove admin-list-button"><a href="action/deleteCommentPost.php?newspostid=<?php echo $newspostId; ?>">Remove</a></li>
                            </ul>


                        <?php endif; ?>    
                    <?php endif; ?>
            </div>
        </div>
        <div class="middle">
            <hr class="admin-hr1">
            <h3 id="admin-title-post"><?php echo $title; ?></h3>
            <p id="admin-content-post"><?php echo $content; ?></p>
            <div id="editNewsPost">
                <form method="POST">
                    <textarea style="border-radius: 20px; padding:10px; display:inline-block; margin: 0 auto; "name="editNewsPostArea" id="" cols="60" rows="20"><?php  echo $content; ?></textarea>            
                    <button style="display:block; margin: 0 auto;"class="user-submit-btn" type="submit" name="editNewsPost">Submit</button>
                </form>
            </div>
        </div>
        <hr class="admin-hr2">
        <div class="bottom justify-center">
            <?php if(isset($_SESSION['logged_in'])): ?>
                <a class="admin-button" href=""><i class="far fa-comment-alt"></i></a>
                <?php endif; ?>

            
            
        </div>
        <hr class="admin-hr2">
        <div class="category-links">
            <?php 
            $categories = $query->selectAllWhere("postcategories", "postid", $_GET['id']);
            foreach($categories as $category): ?>
                <?php $stmt = $conn->query('SELECT name FROM category WHERE id = ' . $category['categoryid']);
                      $categoryLink = $stmt->fetchColumn();  
                ?>
                <a href="index.php?category=<?php echo $categoryLink;  ?>"><?php echo $categoryLink; ?></a>
                
            <?php endforeach; ?>        
        </div>
        <?php foreach($comments as $comment): ?>
            <?php $commentUserInfo = $query->selectAllWhere("users","id",$comment['userid']);

                  foreach($commentUserInfo as $commentUser){
                    $commentUsername = $commentUser['username'];
                    $commentProfilePic = $commentUser['profilepic'];
                  }  
            ?>
            <div class="comments-main-wrapper">
                <hr class="admin-hr1">
                <div class="comments-wrapper go-row align-center">
                <img src="<?php echo $commentProfilePic; ?>" alt="Admin-coment-Profile-Pic">
                <div class="comment-content">
                    <p id="comenter-name"><a style="color: #6495ED;" href="index-p.php?id=<?php echo $comment['userid']; ?>"><?php echo $commentUsername; ?></a></p> 
                    <p id='comenter-content'><?php echo $comment['newscommenttext']; ?></p>
                    <div id="editNewsComment">
                        <form method="POST" action="action/editNewsComment.php?newsPostId=<?php echo $newspostId . "&newsCommentId=" . $comment['id']; ?>">
                            <textarea style="border-radius: 20px; padding:10px; display:inline-block; margin: 0 auto; "name="newsCommentText" id="" cols="30" rows="10"><?php  echo $comment['newscommenttext']; ?></textarea>            
                            <button style="display:block;"class="user-submit-btn" type="submit" name="editNewsComment">Submit</button>
                        </form>
                    </div>
                    <p style="margin-top:15px;" id='comenter-content'><?php 
                    $date = date('H:i:s A M/d', strtotime($comment['timecommented'])); 
                    echo $date;
                    ?></p>
                </div>   
                <div class="dropdown-comment-menu-container">
                    <?php if(isset($_SESSION['logged_in'])): ?>
                        <?php if($_SESSION['userId'] == $comment['userid'] || $_SESSION['admin'] == 1): ?>
                            <button class="dropdown-comment-menu-button">···</button>
                            <ul>
                                <li onclick="fun2()" class="button-edit commenter-list-button"><a href="#">Edit</a></li>
                                <li class="button-remove commenter-list-button"><a href="action/deleteCommentPost.php?commentid=<?php echo $comment['id'] . "&newspostidcomment=" . $newspostId; ?>">Remove</a></li>
                            </ul>
                        <?php endif; ?>    
                    <?php endif; ?>
                </div>
                </div>    
            </div>
        <?php endforeach; ?>
        <hr class="admin-hr1">
        <?php if(isset($_SESSION['logged_in'])){
                include "includes/comment.php";
        } ?>
    </div>
    <?php include "includes/footer.php"; ?>
    <script>
        function fun1(){
            var content = document.getElementById("admin-content-post");
            var divclass = document.getElementById("editNewsPost");
            content.style.display = "none";
            divclass.style.display = "block";
        }
        function fun2(){
            var content = document.getElementById("comenter-content");
            var divclass = document.getElementById("editNewsComment");
            content.style.display = "none";
            divclass.style.display = "block";
        }
    </script>
</body>
</html>