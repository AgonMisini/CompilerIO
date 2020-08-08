<?php 
    require 'includes/Connection.php';
    require 'includes/QueryBuilder.php';
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    session_start();

    if(isset($_POST['submitNewsPost'])){
        $userId = $_SESSION['userId'];
        $newsPostTitle = $_POST['newsPostTitle'];
        $newsPostContent = nl2br($_POST['newsPostContent']);

        $newsPostInformation = array($userId, $newsPostTitle, $newsPostContent);
        $categories = $_POST['selected'];
        if(empty($categories)){
            header("Location: addnewspost.php?error=noCategoriesSelected");
            exit();
        }
        $table = "newsposts";
        $query->insertNewsPost($table, $newsPostInformation, $categories);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="css/style-add-news-post.css">
    <title>Admin | Add News Post</title>
</head>
<body>
    <?php include "includes/Navigation-bar.php"; ?>
    <div class="title1">
        <h1 style="text-align: center; margin: 10px 0;">Add News Post</h1>
        <hr>
    </div>
    <div class="add-news-post-main-container go-column">
        <form class="go-column" method="POST">
            <label for="newsPostTitle">Add your News Post HERE</label>
            <input type="text" name="newsPostTitle" placeholder="Put your title here">
            <textarea name="newsPostContent" id="" cols="30" rows="10" placeholder="Write something.. :D"></textarea>
            <label for="selected[]">Categories</label>
            <div class="categories-box go-row align-center">
                <?php 
                    $categories = $query->selectAll('category');
                    foreach($categories as $category){
                        
                        echo '<input type="checkbox" name="selected[]" value="' . $category['id'] . '"> ' . $category['name'];  
                        echo ' | ';              
                    }
                ?>
            </div>

            <div class="btn-submit justify-center">
                <input type="submit" name="submitNewsPost">
            </div>
        </form>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>