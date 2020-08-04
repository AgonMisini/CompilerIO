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
        $category = $_GET['category'];
        $newsPosts = $query->getPagesForSpecificCategory($currentPage, $category);
        $numberOfPages= $query->getNumberOfPagesForSpecificCategory($category);
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
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    <?php include "includes/Navigation-bar.php"; ?>






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
                        <li><a href="index.php?page=<?php echo $page . "&category=" . $category; ?>"><?php echo $page; ?></a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=<?php echo $page . "&category=" . $category; ?>"><?php echo $page; ?></a></li>
                    <?php endif ?>
                <?php  endif;  ?>
        <?php } ?>
    </ul>
    <?php include "includes/footer.php"; ?>
</body>
</html>