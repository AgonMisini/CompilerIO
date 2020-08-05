<?php 
    require "includes/Connection.php";
    require "includes/QueryBuilder.php";
    session_start();
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);

    if(isset($_POST['submit'])){
        $title = $_POST['title'];
        $content = $_POST['post-content'];
        $orderBy = $_GET['orderBy'];
        
        $forumPostInformation = array($_GET['id'], $title, $content, $_GET['category']);
        $query->insertForumPost($forumPostInformation, $orderBy);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="css/style-create-post.css">
    <title>Create a post</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include "includes/Navigation-bar.php";?>

    <div class="create-post-form">
        <form class="create-post go-column" action="" method="POST">
            <h1 id="create-post-text">Create a Post</h1>
            <input type="text" name="title" placeholder="Put your title here">
            <textarea name="post-content" rows="4" cols="50" placeholder="Write something... :)-"> </textarea>
            <input type="submit" name="submit">
        </form>
    </div>

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>

</body>
</html>