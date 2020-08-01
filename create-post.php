<?php 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a post</title>
    <link rel="stylesheet" href="css/style-create-post.css">
</head>
<body>
    <!-- NAVBAR -->
    <?php include "includes/Navigation-bar.php";?>

    <div class="create-post-form">
        <form class="create-post go-column" action="" method="POST">
            <h1 id="create-post-text">Create a Post</h1>
            <input type="text" name="title" placeholder="Put your title here">
            <textarea name="post-content" rows="4" cols="50"> </textarea>
            <input type="submit" name="submit">
        </form>
    </div>

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>

</body>
</html>