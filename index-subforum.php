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
    <link rel="stylesheet" href="css/style-subforum.css">
</head>
<body>
    <!-- NAVBAR -->
    <?php include "includes/Navigation-bar.php";?>

    <div class="container-forum-topic go-column">
        <h1 id="main-forum-category-title">HTML Topic</h1>
        <p id="main-forum-category-subtitle">In this topic we will discuss only about HTML</p>

        <hr>
        <section class="main-subforum-topics">
            <div class="main-subforum-page-list ">
                <ul class="subforum-page-list go-row">
                    <li class="list-link go-last-page"><a href="#"><i class="fa fa-angle-double-left"></i></a></li> 
                    <!-- qeta Ardit deshta me ba si Firt page Jump link 
                    (nese je te faqja 5 psh me shku direkt te e para edhe mu ba butoni ose lista display: none;)-->

                    <li><a class="list-link active" href="#">1</a></li>
                    <li><a class="list-link" href="#">2</a></li>
                    <li><a class="list-link" href="#">3</a></li>
                    <li class="list-link go-next-page"><a href="#">NEXT</a></li>
                    <li class="list-link go-last-page" ><a href="#"><i class="fa fa-angle-double-right"></i></a></li> <!-- last page Jump link (If this category has n topics me shku te faqja e fundit) -->

                    
                    <li style="margin-left: auto;"><a class="list-link" href="create-post.php">Add Post</a></li>
                    <li class="list-link go-last-paget" id="selectable-page-list"><a href="#">Page 1 of n</a></li>
                </ul>
            </div>
            <hr>
        
            <ol class="forum-topics">
                <li class="forum-topic-link go-row">
                    <div class="forum-topic-main align-center">
                        <h4><a href="index-forum-topic-posts.php" id="main-forum-topic-title">Can somebudy show me how to make a HTML Form?</a></h4>
                    </div>

                    <div class="forum-posts-sum-container go-row align-center align-right">
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