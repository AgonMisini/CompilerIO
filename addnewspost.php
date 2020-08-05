<?php 
    require 'includes/Connection.php';
    require 'includes/QueryBuilder.php';
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    session_start();

    if(isset($_POST['submitNewsPost'])){
        $userId = $_SESSION['userId'];
        $newsPostTitle = $_POST['newsPostTitle'];
        $newsPostContent = $_POST['newsPostContent'];

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
    <title>Document</title>
</head>
<body>
    <?php include "includes/Navigation-bar.php"; ?>
    <form method="POST">
        <input type="text" name="newsPostTitle" placeholder="Put your title here">
        <textarea name="newsPostContent" id="" cols="30" rows="10"></textarea>
        <p>Categories</p>
        <?php 
            $categories = $query->selectAll('category');
            foreach($categories as $category){
                echo '<input type="checkbox" name="selected[]" value="' . $category['id'] . '"> ' . $category['name'];
                echo '<br>';
            }
        ?>
        <input type="submit" name="submitNewsPost">
    </form>
    <?php include "includes/footer.php"; ?>
</body>
</html>