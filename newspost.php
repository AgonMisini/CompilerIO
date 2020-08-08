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
        $userId = $newsPost['userid'];
    }
    $userInfo = $query->selectAllWhere("users", "id", $userId);
    foreach($userInfo as $user){
        $username = $user['username'];
        $profilePicture = $user['profilepic'];
    }

    $comments = $query->selectAllWhere("newscomment","newspostid", $newspostId);
    $commentsMade = count($comments);

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
    <div class="example-container">
        <div class="top go-row align-center">
            <img src="profilepicture\default.jpg" alt="Admin-Profile-Pic">
            <p id="admin-name">Flamur Fazliu</p> <i class="fas fa-angle-right"></i>
            <p id='admin-category-indicator'>News/Categories/HTML</p>    
            <div class="admin-dropdown-post-menu-container">
                <button class="admin-dropdown-post-menu-button">···</button>
                <ul>
                    <li class="button-edit admin-list-button"><a href="#">Edit</a></li>
                    <li class="button-remove admin-list-button"><a href="#">Remove</a></li>
                </ul>
            </div>
        </div>
        <div class="middle">
            <hr class="admin-hr1">
            <p id="admin-content-post">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Exercitationem impedit qui cumque voluptas id, non accusantium commodi perferendis est deserunt rem eos aspernatur voluptates dolores ab neque facere, dolorum temporibus! Similique distinctio iste reprehenderit enim repellendus vitae necessitatibus id, provident a fuga quis eum illo architecto hic voluptate minima quod!</p>
        </div>

        <hr class="admin-hr2">
        <div class="bottom justify-center">    
            <a class="admin-button" href=""><i class="far fa-thumbs-up"></i></a>
            <a class="admin-button" href=""><i class="far fa-comment-alt"></i></a>
        </div>
        <hr class="admin-hr2">

        <div class="category-links">
    
            <a href="">HTML</a>
            <a href="">CSS</a>
            <a href="">Java Script</a>
            <a href="">JAVA</a>
            <a href="">PHP</a>
            <a href="">SQL</a>
        </div>

        <div class="comments-main-wrapper">
        <hr class="admin-hr1">
            <div class="comments-wrapper go-row align-center">
                <img src="profilepicture\default.jpg" alt="Admin-coment-Profile-Pic">
                <div class="comment-content">
                    <p id="comenter-name">Ardit Islami</p> 
                    <p id='comenter-content'>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt natus, nihil reprehenderit expedita suscipit nemo vero incidunt accusantium cum error explicabo, provident aperiam neque ex dolor voluptatibus doloremque, sit necessitatibus.</p> 
                </div>   
                <div class="dropdown-comment-menu-container">
                    <button class="dropdown-comment-menu-button">···</button>
                    <ul>
                        <li class="button-edit commenter-list-button"><a href="#">Edit</a></li>
                        <li class="button-remove commenter-list-button"><a href="#">Remove</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>