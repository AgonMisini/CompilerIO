<?php 
    include 'includes/Connection.php';
    include 'includes/QueryBuilder.php';
    session_start();;
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    // var_dump($_SESSION);
   
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
                <img class="topic-user-profile-pic" src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Ftse4.mm.bing.net%2Fth%3Fid%3DOIP.8phkso4Vu62KfrmNZcSqGAHaHa%26pid%3DApi&f=1" alt="User Profile Pic">

            <div class="go-column width">
            <div class="go-column main-user-post">
                <div class="go-row">
                    <h1 id="main-forum-category-title">Can somebudy show me how to make a HTML Form?</h1>
                    <span class="user-additional-info align-righ" id="user-topic-name">Topic Created by : (Name of Creator), 
                        <span class="user-additional-info">In HTML Category | 
                            <span class="user-additional-info" id="sum-of-replies">N replies 
                            </span>
                        </span>
                    </span>
                </div>
                <hr style="margin: 10px 0 10px;">
                <p class="main-user-post-content">I have forgotten how to make one Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis dolorem quaerat fuga harum, aliquid exercitationem dignissimos quidem. Architecto, iusto inventore.</p>


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
                    <div class="user-comment align-center go-row">
                        <img class="user-profile-pic" src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Ftse4.mm.bing.net%2Fth%3Fid%3DOIP.8phkso4Vu62KfrmNZcSqGAHaHa%26pid%3DApi&f=1" alt="User Profile Pic">

                        <div class="user-comment-container go-column">
                            <div class="user-comment-content">
                                <div class="go-row">
                                <h4>John Doe | <span class="date">[DATE HERE]</span></h4>
                                

                                </div>
                                <hr style="margin: 5px 0 0;">  

                                <p>yada yada yada</p>
                            </div>

                            <ul class="go-row align-center">
                                <li class="content-menu-button"><a href=""><i class="far fa-comments"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>