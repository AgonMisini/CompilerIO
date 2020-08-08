<?php 
    require 'includes/Connection.php';
    require 'includes/QueryBuilder.php';
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    session_start();
    if(!isset($_GET['page'])){
        $currentPage = 1;
    }else{
        $currentPage = $_GET['page'];
    }
    if(isset($_GET['category'])){
        $categoryPage = $_GET['category'];
        $newsPosts = $query->getPagesForSpecificCategory($currentPage, $categoryPage);
        $numberOfPages= $query->getNumberOfPagesForSpecificCategory($categoryPage);
    }else{
        $newsPosts = $query->getPages($currentPage);
        $numberOfPages = $query->numberOfPages();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    <?php include "includes/Navigation-bar.php"; ?>

    <?php foreach($newsPosts as $newsPost): ?>
        <?php 
            $userPostInfo = $query->selectAllWhere("users","id", $newsPost['userid']);
            foreach($userPostInfo as $userPostInfo){
                $username = $userPostInfo['username'];
                $profilePic = $userPostInfo['profilepic'];
                $userPostId = $userPostInfo['id'];
            }
            $comments = $query->selectAllWhere("newscomment", "newspostid", $newsPost['id']);
            $commentsMade = count($comments);    
        ?>
        <!-- Adjust the tags to your need, i just made em all paragraphs and stuff -->


        <!-- IMG -->
        <img src="<?php echo $profilePic; ?>" alt="" width="100" height="100">
        <!-- AUTHOR -->
        <p><a style="color: #6495ED;" href="index-p.php?id=<?php echo $userPostId; ?>"><?php echo $username; ?></a></p>
        <!-- POST LINK AND TITLE -->
        <h2><a style="color: #6495ED;" href="newspost.php?id=<?php echo $newsPost['id']; ?>"><?php echo $newsPost['newsposttitle']; ?></a></h2>
        <!-- POST CONTENT -->
        <p><?php
        $newsPostContent = str_replace(array("\r\n", "\r", "\n"), "", $newsPost['newspostcontent']);
        echo substr($newsPostContent, 0, 150) . "..."; ?></p>

        <!-- POST CATEGORIES -->
        <p><?php $categories = $query->selectAllWhere("postcategories", "postid", $newsPost['id']);
            foreach($categories as $category){
                $stmt = $conn->query('SELECT name FROM category WHERE id = ' . $category['categoryid']);
                $categoryLink = $stmt->fetchColumn();
                echo '<a style="display: inline; color: #6495ED;" href="index.php?category=' . $categoryLink . '">' . $categoryLink .'</a>/';
            }
            ?></p>
        <!-- COMMENTS MADE -->
        <p><?php echo $commentsMade . " comments"; ?></p>
        <!-- DATE -->
        <p><?php echo date('H:i:s A M/d', strtotime($newsPost['timeposted'])); ?></p>
        <hr>

    <?php endforeach; ?>




    <!-- Mul this is the pagination thats supposed to be down at the bottom so they can go to the next pages -->
    <ul>
        <?php 
            for($page=1;$page <= $numberOfPages; $page++){
                if(!isset($_GET['category'])): ?>
                    <?php if($currentPage == $page): ?>
                        <li><a href="index.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=<?php echo $page; ?>"><?php echo $page;?></a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if($currentPage == $page): ?>
                        <li><a href="index.php?page=<?php echo $page . "&category=" . $categoryPage; ?>"><?php echo $page; ?></a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=<?php echo $page . "&category=" . $categoryPage; ?>"><?php echo $page; ?></a></li>
                    <?php endif ?>
                <?php  endif;  ?>
        <?php } ?>
    </ul>
    <?php include "includes/footer.php"; ?>
</body>
</html>