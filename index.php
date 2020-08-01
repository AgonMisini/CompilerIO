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
    <link rel="stylesheet" href="css/style-forum.css">
    <title>Document</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include "includes/Navigation-bar.php";?>

    <div class="container-forum go-column">
        <h1 id="main-page-title">Forums</h1>
        <hr>
        <section class="forum-categories">
            <ol class="forums-list">
                <li class="forum-row">
                    <a href="#">Community</a>
                    
                    <a href="#"></a> <!-- Qetu me ba toogle like a Drop Down of the subcategoies -->
                    <hr>
                </li>
            </ol>

            <ol class="subforums-list">
                <li class="subforum-link go-row">
                    <div class="forum-ico">

                    </div>

                    <div class="forum-main">
                        <a href="index-subforum.php" id="main-forum-title">HTML Topic</a>
                        <div>
                        In this topic we will discuss only about HTML
                        </div>
                    </div>

                    <div class="forum-posts-sum-container go-row align-right">
                        <dl class="forum-posts-sum go-column">
                            <dt>(Number of posts)</dt>
                            <dd>posts</dd>
                        </dl>
                    </div>
                </li>
            </ol>
        </section>

    </div>

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>