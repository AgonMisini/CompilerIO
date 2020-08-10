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
    <h1 class="title1">Home</h1>

    <div class="home-categories go-row align-center">
        <ul class="home-category-main-container go-row align-center">
            <label for="home-category-name">Categories : </label>
            <?php if(isset($_GET['category'])): ?>
                <li class="home-category-name"><a href="index.php">All</a></li>
            <?php else: ?>
                <li class="home-category-name"><a style="background-color:white; color:black; " href="index.php">All</a></li>
            <?php endif; ?>
            
            <?php $allCategories = $query->selectAll("category");
                foreach($allCategories as $categoryList):
            ?>
                <?php if(isset($_GET['category'])): ?>
                    <?php if($_GET['category'] == $categoryList['name']): ?>
                        <li class="home-category-name"><a style="background-color:white; color:black; "href="index.php?category=<?php echo $categoryList['name'] ;?>"><?php echo $categoryList['name']; ?></a></li>
                    <?php else: ?>
                        <li class="home-category-name"><a href="index.php?category=<?php echo $categoryList['name'] ;?>"><?php echo $categoryList['name']; ?></a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="home-category-name"><a href="index.php?category=<?php echo $categoryList['name'] ;?>"><?php echo $categoryList['name']; ?></a></li>
                <?php endif; ?>
                
            <?php endforeach; ?>
        </ul> 
        <?php if(isset($_SESSION['logged_in'])): ?>
            <?php if($_SESSION['admin'] == 1): ?>
                <ul class="home-create-post-container">
                    <li class="home-create-post"><a href="addnewspost.php">Create Post</a></li>
                </ul> 
            <?php endif; ?>
        <?php endif; ?>
       
    </div>
        <?php if(count($newsPosts) == 0): ?>
            <h4 style="text-align: center !important; margin-top: 50px;">No news posts have been made in this category.</h4>
        <?php endif; ?>
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

        
        <div class="main-newspost-container">
            <div class="newspost-top go-row align-center">
                <!-- IMG -->
                <img src="<?php echo $profilePic; ?>" alt="Admin-Profile-Pic">
                <!-- AUTHOR -->
                <p id="admin-name"><a style="color: #fff;" href="index-p.php?id=<?php echo $userPostId; ?>"><?php echo $username; ?></a></p><i class="fas fa-angle-right"></i>

                <span style="margin: 0 5px; font-size: 17px; "><?php echo "Replies : " . $commentsMade . " "; ?></span>
                <!-- POST CATEGORIES -->
                <p style="margin: 0 5px;font-size: 17px;"><?php $categories = $query->selectAllWhere("postcategories", "postid", $newsPost['id']);
                foreach($categories as $category){
                    $stmt = $conn->query('SELECT name FROM category WHERE id = ' . $category['categoryid']);
                    $categoryLink = $stmt->fetchColumn();
                    echo '<a style="display: inline; color: #6495ED;" href="index.php?category=' . $categoryLink . '">' . $categoryLink .'</a>/';
                } ?></p>
                            <!-- DATE -->
                <p style="margin: 0 5px;font-size: 17px;"><?php echo date('H:i:s A M/d', strtotime($newsPost['timeposted'])); ?></p>
                <div class="admin-dropdown-post-menu-container">

                <?php if(isset($_SESSION['logged_in'])): ?>
                    <button class="admin-dropdown-post-menu-button">···</button>
                    <ul>
                        <li class="button-remove admin-list-button"><a href="action/deleteCommentPost.php?newspostid=<?php echo $newsPost['id']; ?>">Remove</a></li>
                    </ul>
                <?php endif; ?>
                </div>
            </div>
            <!-- POST LINK AND TITLE -->
            <div class="newspost-middle">

            <hr class="admin-hr1">
            <h3 id="admin-title-post"><a style="color: #fff;" href="newspost.php?id=<?php echo $newsPost['id']; ?>"><?php echo $newsPost['newsposttitle']; ?></a></h3>

            <!-- POST CONTENT -->
            <p><?php $newsPostContent = str_replace("<br />", "", $newsPost['newspostcontent']);
            echo substr($newsPostContent, 0, 150) . "..."; ?></p>
            </div>
            
            <div class="category-links">
            <?php 
                foreach($categories as $category): ?>
                    <?php $stmt = $conn->query('SELECT name FROM category WHERE id = ' . $category['categoryid']);
                        $categoryLink = $stmt->fetchColumn(); ?>
                <a href="index.php?category=<?php echo $categoryLink;  ?>"><?php echo $categoryLink; ?></a>
            <?php endforeach; ?>        
            </div>
            <!-- COMMENTS MADE -->
        </div>
    <?php endforeach; ?>

     <!-- PAGINATION-BOTTOM -->
     <div class="home-pagination-main-container">
        <?php if(count($newsPosts) > 0): ?>
        <?php if($numberOfPages == 1): ?>

        <?php else: ?>
            <div class="jump-arrow-page go-row align-center">
                <?php if($currentPage == 1): ?>
                    
                <?php else: ?>
                    <div class="arrow arrow-left">
                        <?php if(isset($_GET['category'])): ?>
                            <a href="index.php?category=<?php 
                                $prevPage = $currentPage - 1;
                                echo $_GET['category'] . "&page=" . $prevPage; ?>">
                                <i class="fa arrow1 fa-angle-double-left"></i>
                            </a>
                        <?php else: ?>
                            <a href="index.php?page=<?php $prevPage = $currentPage - 1;
                                echo $prevPage; 
                                ?>">
                                <i class="fa arrow1 fa-angle-double-left"></i>
                            </a>
                        <?php endif; ?>
                        
                    </div>
                <?php endif; ?>
                

                <p class="arrow-seperator">|</p>
                <?php if($currentPage == $numberOfPages): ?>
                <?php else: ?>
                    <?php if(isset($_GET['category'])): ?>
                        <div class="arrow arrow-right">
                            <a href="index.php?category=<?php $nextPage = $currentPage + 1; echo $_GET['category'] . "&page=" . $nextPage; ?>">
                                <i class="fa arrow2 fa-angle-double-right"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="arrow arrow-right">
                            <a href="index.php?page=<?php $nextPage = $currentPage + 1; echo $nextPage; ?>">
                                <i class="fa arrow2 fa-angle-double-right"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <ul class="align-center go-row pagination-list-link">
            <?php 
                for($page=1;$page <= $numberOfPages; $page++){
                    if(!isset($_GET['category'])): ?>
                        <?php if($currentPage == $page): ?>
                            <li class="list-link"><a href="index.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                        <?php else: ?>
                            <li class="list-link"><a href="index.php?page=<?php echo $page; ?>"><?php echo $page;?></a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if($currentPage == $page): ?>
                            <li class="list-link"><a href="index.php?page=<?php echo $page . "&category=" . $categoryPage; ?>"><?php echo $page; ?></a></li>
                        <?php else: ?>
                            <li class="list-link"><a href="index.php?page=<?php echo $page . "&category=" . $categoryPage; ?>"><?php echo $page; ?></a></li>
                        <?php endif ?>
                    <?php  endif;  ?>
            <?php } ?>
        </ul>
        <?php endif; ?>      
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>