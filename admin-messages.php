<?php 
    require "includes/Connection.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Messages</title>
    <link rel="stylesheet" href="css/Admin-Messages.css">
</head>
<body>
<div class="body-column">
    <?php include "includes/Admin-Navigation-Bar.php"; ?>

    <main>
        <h1 id="my-message">My Messages</h1>

        <?php// foreach($users as $user): ?>
        <div class="messages-container">
            <img src="" alt="User-Pic">

            <p style="margin-bottom: 10px;"><strong>Email:</strong> <?php//echo $user['email']; ?></p>
            <p style="margin-bottom: 10px;"><strong>Title:</strong> <?php// echo $user['title']; ?></p>
            <p style="margin-bottom: 10px;"><strong>Message:</strong> <?php// echo $user['messages']; ?></p>
        </div>
        <?// endforeach; ?>
    </main>

    <?php include "includes/Footer.php"; ?>

</div>
</body>
</html>