<?php 
    include 'includes/Connection.php';
    include 'includes/QueryBuilder.php';
    session_start();;
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    // var_dump($_SESSION);
    $category = $_GET['category'];
    $page;
    if(!isset($_GET['page'])){
        $page = 1;
    }else{
        $page = $_GET['page'];
    }
    $forumPosts;
    $numberOfPages = $query->numberOfPagesForum($category);
    if(!isset($_GET['orderBy'])){
        $forumPosts = $query->getForumPosts($page, $category, "Newest");
    }else{
        switch($_GET['orderBy']){
            case 'Newest':
                $forumPosts = $query->getForumPosts($page, $category, $_GET['orderBy']);
                break;
            case 'Oldest':
                $forumPosts = $query->getForumPosts($page, $category, $_GET['orderBy']);
                break;
            case 'Most_popular':
                $forumPosts = $query->getForumPosts($page, $category, $_GET['orderBy']);
                break;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="css/style-subforum.css">
    <script type="text/javascript">
        var category=<?php echo json_encode($category); ?>;
    </script>
</head>
<body>
    <!-- NAVBAR -->
    <?php include "includes/Navigation-bar.php";?>

    <div class="container-forum-topic go-column">
        <h1 id="main-forum-category-title">HTML Topic</h1>
        <p id="main-forum-category-subtitle">In this topic we will discuss only about HTML</p>
        <select name="" id="" onchange="if(this.value != '') document.location = '/compilerio/index-subforum.php?orderBy=' + this.value + '&category=' + category ">
            <option value="">Sort by</option>
            <option value="Newest"<?php if(isset($_GET['orderBy']) && $_GET['orderBy'] == 'Newest') echo 'selected="selected"' ?>>Newest</option>
            <option value="Oldest" <?php if(isset($_GET['orderBy']) && $_GET['orderBy'] == 'Oldest') echo 'selected="selected"' ?>>Oldest</option>
            <option value="Most_popular"<?php if(isset($_GET['orderBy']) && $_GET['orderBy'] == 'Most_popular') echo 'selected="selected"' ?>>Most popular</option>
        </select>

        <hr>
        <section class="main-subforum-topics">
            <div class="main-subforum-page-list ">
                <ul class="subforum-page-list go-row">
                    <?php 
                        if($page == 1){
                            
                        }else{
                            echo '<li class="list-link go-last-page"><a href="index-subforum.php?category=' . $category . '&orderBy=' . $_GET['orderBy'] . '&page=1' . '"><i class="fa fa-angle-double-left"></i></a></li>';
                        }
                    ?>
                    
                    <!-- qeta Ardit deshta me ba si Firt page Jump link 
                    (nese je te faqja 5 psh me shku direkt te e para edhe mu ba butoni ose lista display: none;)-->

                    <?php 
                        for($page=1;$page <= $numberOfPages; $page++){
                            if($_GET['page'] == $page){
                                echo '<a style="background-color:white; color:black;" class="list-link" href="index-subforum.php?category=' . $category . '&orderBy=' . $_GET['orderBy']  . '&page=' . $page .'">' . $page . '</a>';
                            }else{
                                echo '<a class="list-link" href="index-subforum.php?category=' . $category . '&orderBy=' . $_GET['orderBy']  . '&page=' . $page .'">' . $page . '</a>';
                            }
                        }
                    ?>       
                    <?php if($_GET['page'] == $numberOfPages){

                    }else{
                        $pagenum = $_GET['page'] + 1;
                        echo '<li class="list-link go-next-page"><a href="index-subforum.php?category=' . $category . '&orderBy=' . $_GET['orderBy']  . '&page=' . $pagenum .'">NEXT</a></li>';
                        echo '<li class="list-link go-last-page" ><a href="index-subforum.php?category=' . $category . '&orderBy=' . $_GET['orderBy']  . '&page=' . $numberOfPages .'"><i class="fa fa-angle-double-right"></i></a></li>';
                    } ?>
                     <!-- last page Jump link (If this category has n topics me shku te faqja e fundit) -->

                    <?php if(isset($_SESSION['logged_in'])){
                        if($_SESSION['logged_in'] == 1){
                            echo '<li style="margin-left: auto;"><a class="list-link" href="create-post.php?category=' . $_GET['category'] . '&id=' . $_SESSION['userId'] . '&orderBy=' . $_GET['orderBy'] . '">Add Post</a></li>';
                            
                            echo '<li class="list-link go-last-paget" id="selectable-page-list"><a href="#">' . 'Page ' . $_GET['page'] . ' of ' . $numberOfPages . '</a></li>';
                        }else{
                            echo '<li class="list-link go-last-paget" id="selectable-page-list" style="margin-left: auto;"><a href="#">' . 'Page ' . $_GET['page'] . ' of ' . $numberOfPages . '</a></li>';
                        }
                    } ?>
                    
                </ul>
            </div>
            <hr>
            <?php foreach($forumPosts as $forumPost): ?>
                <ol class="forum-topics">
                    <li class="forum-topic-link go-row">
                        <div class="forum-topic-main align-center">
                            <h4><a href=<?php echo "index-forum-topic-posts.php?id=" . $forumPost['id'] . "&category=" . $_GET['category'];?> id="main-forum-topic-title"><?php echo $forumPost['forumposttitle']; ?></a></h4>
                        </div>

                        <div class="forum-posts-sum-container go-row align-center align-right">
                            <dl class="forum-posts-sum go-column">
                                <dt><?php 
                                    $stmt = $conn->query("SELECT * FROM forumcomment WHERE forumpostid = " . $forumPost['id']);
                                    echo count($stmt->fetchAll());
                                ?></dt>
                                <dd>comments</dd>
                            </dl>
                            <p style="margin-right: 15px;">Likes: <?php echo $forumPost['likes'] ?></p>
                            <span>By: <a href="index-p.php?id=<?php 
                            
                            echo $forumPost['userid'];
                            
                            ?>" style="color:#6495ED; display:inline;"><?php 
                            
                            $stmt = $conn->query("SELECT username FROM users WHERE id = " . $forumPost['userid']);
                            echo $stmt->fetchColumn();
                            ?></a><?php echo "  ". date('h:m:s A M/d', strtotime($forumPost['timeposted'])); ?></span>
                        </div>
                    </li>
                </ol>
            <?php endforeach; ?>
        </section>

    </div>

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>