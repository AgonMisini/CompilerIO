<?php 
    include 'includes/Connection.php';
    include 'includes/QueryBuilder.php';
    session_start();;
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    // var_dump($_SESSION);
    
    $stmt = $conn->prepare("SELECT userid FROM forumposts WHERE id = :id");
    $stmt->bindParam(":id", $_GET['id']);
    $stmt->execute();
    $theUserId = $stmt->fetchColumn();
    $user = $query->selectAllWhere("users", "id", $theUserId);

    foreach($user as $user){
        $userId = $user['id'];
        $usernameOP = $user['username'];
        $profilepicture = $user['profilepic'];
    }
    $forumPost = $query->selectAllWhere("forumposts", "id", $_GET['id']);
    foreach($forumPost as $forumPost){
        $forumID = $forumPost['id'];
        $title = $forumPost['forumposttitle'];
        $content = $forumPost['forumpostcontent'];
        $date = date('H:i:s A M/d', strtotime($forumPost['timeposted']));
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
    if(isset($_SESSION['logged_in'])){
        $stmt = $conn->prepare("SELECT * FROM forumpost_user_like WHERE userid = " . $_SESSION['userId'] . " AND forumpostid = :forumID;");
        $stmt->bindParam(":forumID", $forumID);
        $stmt->execute();
        
        $result = count($stmt->fetchAll());
    }
	if (isset($_POST['liked'])) {
        $postid = $_POST['postid'];
        $userId = $_POST['liked'];
        $stmt = $conn->query("SELECT likes FROM forumposts WHERE id=$postid");
        $like = $stmt->fetchColumn();

        $stmt = $conn->query("INSERT INTO forumpost_user_like (userid, forumpostid) VALUES($userId, $postid);");
        $stmt = $conn->query("UPDATE forumposts SET likes=$like+1 WHERE id=$postid");
	

		echo $like+1;
		exit();
	}
	if (isset($_POST['unliked'])) {
		$postid = $_POST['postid'];
        $userId = $_POST['unliked'];
        $stmt = $conn->query("SELECT likes FROM forumposts WHERE id=$postid");
        $like = $stmt->fetchColumn();

        $stmt = $conn->query("DELETE FROM forumpost_user_like WHERE forumpostid=$postid AND userid=$userId");
        $stmt = $conn->query("UPDATE forumposts SET likes=$like-1 WHERE id=$postid");
	
		
        echo $like-1;
        exit();
    }
                            
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/style-topic-posts.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
    <title>FOrum Post</title>
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
                        </span>
                    </span>
                </div>
                <hr style="margin: 10px 0 10px;">
                <p class="main-user-post-content"><?php echo $content; ?></p>
            </div>
                <div class="main-topic-content-menu go-row">
                    <ul class="go-row">
                        <div class="likess">
                            <?php if(isset($_SESSION['logged_in'])): ?>
                                <?php if($result == 1): ?>
                                <span class="unlike fa fa-thumbs-up" data-id="<?php echo $forumID; ?>"></span> 
                                <span class="like hide fa fa-thumbs-o-up" data-id="<?php echo  $forumID; ?>"></span> 
                                <?php else: ?>
                                <span class="like fa fa-thumbs-o-up" data-id="<?php echo $forumID; ?>"></span> 
                                <span class="unlike hide fa fa-thumbs-up" data-id="<?php echo $forumID; ?>"></span>
                                <?php endif ?>
                                <span class="likes_count" style="color: white;"><?php echo $likes; ?> likes</span>   
                            <?php else: ?>
                                <p><a href="index-l.php" style="display:inline; color: #6495ED;">Login/Create account</a> to like posts</p>
                            <?php endif ?>
                        </div>    
                    </ul>
                </div>
                <div class="go-row">
                    <a id="view-comment" class="go-row" href="">View Comments <?php echo "(" . $commentCount . ")"; ?></a>
                </div>

                <div class="main-comments">
                    <?php foreach($comments as $comment): ?>
                        <?php 
                            $userinformation = $query->selectAllWhere("users","id", $comment['userid']);
                            foreach($userinformation as $user){
                                $username = $user['username'];
                                $profilepic = $user['profilepic'];
                                $usersId = $user['id'];
                                
                            }    
                        ?>

                        <div class="user-comment align-center go-row">
                            <img class="user-profile-pic" src="<?php echo $profilepic; ?>" alt="User Profile Pic">

                            <div class="user-comment-container go-column">
                                <div class="user-comment-content">
                                    <div class="go-row">
                                    <h4><a style="display:inline; color: #fff;" href="<?php echo "index-p.php?id=" . $usersId;  ?>"><?php echo $username; ?></a> | <span class="date"><?php echo date('h:i:s A M/d', strtotime($comment['timecommented'])); ?></span></h4>
                                    

                                    </div>
                                    <hr style="margin: 5px 0 0;">  

                                    <p><?php echo $comment['forumcomment']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php 
                    if(isset($_SESSION['logged_in'])){
                        include "includes/comment.php";
                    }else{
                        echo '<p><a style="display: inline; color: #fff;"href="index-l.php">Login/Create account </a>so you can comment.</p>';
                    }
                ?>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>
<script src="jquery.min.js"></script>
<script>
	$(document).ready(function(){
        var userId =<?php echo json_encode($_SESSION['userId']); ?>
		// when the user clicks on like
		$('.like').on('click', function(){
			var postid = $(this).data('id');
			    $post = $(this);

			$.ajax({
				url: 'index-forum-topic-posts.php',
				type: 'post',
				data: {
					'liked': userId,
					'postid': postid
				},
				success: function(response){
					$post.parent().find('span.likes_count').text(response + " likes");
					$post.addClass('hide');
					$post.siblings().removeClass('hide');
				}
			});
		});

		// when the user clicks on unlike
		$('.unlike').on('click', function(){
			var postid = $(this).data('id');
		    $post = $(this);

			$.ajax({
				url: 'index-forum-topic-posts.php',
				type: 'post',
				data: {
					'unliked': userId,
					'postid': postid
				},
				success: function(response){
					$post.parent().find('span.likes_count').text(response + " likes");
					$post.addClass('hide');
					$post.siblings().removeClass('hide');
				}
			});
		});
	});
</script>
</body>
</html>