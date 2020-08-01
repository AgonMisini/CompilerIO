<?php 
    include 'includes/Connection.php';
    include 'includes/QueryBuilder.php';
    session_start();;
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    // var_dump($_SESSION);
    $stmt = $conn->prepare("SELECT userid FROM forumposts WHERE id = :id");
    $stmt->execute([
        ":id"=>$_GET['id']
    ]);
    $theUserId = $stmt->fetchColumn();
    $user = $query->selectAllWhere("users", "id", $theUserId);

    foreach($user as $user){
        $userId = $user['id'];
        $usernameOP = $user['username'];
        $profilepicture = $user['profilepic'];
    }
    $forumPost = $query->selectAllWhere("forumposts", "id", $_GET['id']);
    foreach($forumPost as $forumPost){
        $title = $forumPost['forumposttitle'];
        $content = $forumPost['forumpostcontent'];
        $date = date('h:m:s A M/d', strtotime($forumPost['timeposted']));
        $likes = $forumPost['likes'];
    }
    $stmt = $conn->prepare("SELECT * FROM forumcomment WHERE forumpostid = :id ORDER BY timecommented DESC");
    $stmt->bindParam(":id", $_GET['id']);
    $stmt->execute();
    $comments = $stmt->fetchAll();
    $commentCount = count($comments);

    if(isset($_POST['submitComment'])){
        $commentContent = $_POST['commentTextArea'];
        $forumId = $_GET['id'];
        $userID = $_SESSION['userId'];

        $forumArrayInfo = array($forumId, $userID, $commentContent);
        $query->insertForumComment($forumArrayInfo,$_GET['category']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="css/style-topic-posts.css">
</head>
<body>
    <!-- NAVBAR -->
    <?php include "includes/Navigation-bar.php";?>

    <div class="container-forum-topic-posts">
        <div class="main-user-topic-post go-row align-center">


                <!-- Useri qe e ka kriju topickun -->
                <img class="topic-user-profile-pic" src="<?php echo $profilepicture; ?>" alt="User Profile Pic">

            <div class="go-column width">
            <div class="go-column main-user-post">
                <div class="go-row">
                    <h1 id="main-forum-category-title"><?php echo $title; ?></h1>
                    <span class="user-additional-info align-righ" id="user-topic-name">Topic Created by : <a style="color: #6495ED;" href="index-p.php?id=<?php echo $userId ?>" ><?php echo $usernameOP; ?></a>, 
                        <span class="user-additional-info"> in <?php echo $_GET['category']; ?> category | 
                            <span class="user-additional-info" id="sum-of-replies"><?php 
                                echo $commentCount . " replies";
                            ?> 
                            </span>
                            <span>
                                <?php echo "|  Likes: " . $likes;  ?>
                            </span>
                        </span>
                    </span>
                </div>
                <hr style="margin: 10px 0 10px;">
                <p class="main-user-post-content"><?php echo $content; ?></p>
            </div>
                <div class="main-topic-content-menu go-row">
                    <ul class="go-row">
                        <li class="content-menu-button"><a href=""><i class="far fa-heart"></i></a></li>
                        <li class="content-menu-button"><a href=""><i class="far fa-comments"></i></a></li>
                        <li class="content-menu-button"><a href=""><i class="fas fa-share"></i></a></li>
                    </ul>
                </div>
                <div class="go-row">
                    <a id="view-comment" class="go-row" href="">View Comments</a>
                </div>

                <div class="main-comments">
                    <?php foreach($comments as $comment): ?>
                        <?php 
                            $userinformation = $query->selectAllWhere("users","id", $comment['userid']);
                            foreach($userinformation as $user){
                                $username = $user['username'];
                                $profilepic = $user['profilepic'];
                                
                            }    
                        ?>

                        <div class="user-comment align-center go-row">
                            <img class="user-profile-pic" src="<?php echo $profilepic; ?>" alt="User Profile Pic">

                            <div class="user-comment-container go-column">
                                <div class="user-comment-content">
                                    <div class="go-row">
                                    <h4><?php echo $username; ?> | <span class="date"><?php echo date('h:m:s A M/d', strtotime($comment['timecommented'])); ?></span></h4>
                                    

                                    </div>
                                    <hr style="margin: 5px 0 0;">  

                                    <p><?php echo $comment['forumcomment']; ?></p>
                                </div>

                                <ul class="go-row align-center">
                                    <li class="content-menu-button"><a href=""><i class="far fa-comments"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php 
                    if(isset($_SESSION['logged_in'])){
                        include "includes/comment.php";
                    }
                ?>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>