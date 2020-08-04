<?php 
    require "includes/Connection.php";
    require "includes/QueryBuilder.php";
    session_start();
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);

    $contactmessages = $query->selectAll("contactmessages");


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
    <?php include "includes/Navigation-bar.php"; ?>

    <main>
        <h1 id="my-message">My Messages</h1>

        <?php foreach($contactmessages as $contactmessage): ?>
        <div class="messages-container">
            <p style="margin-bottom: 10px;"><strong>Email:</strong> <?php echo $contactmessage['email']; ?></p>
            <p style="margin-bottom: 10px;"><strong>Title:</strong> <?php echo $contactmessage['subject']; ?></p>
            <p style="margin-bottom: 10px;"><strong>Message:</strong> <?php echo $contactmessage['message']; ?></p>
            <p style="margin-bottom: 10px;"><strong>Date:</strong> <?php echo date('h:i:s A M/d', strtotime($contactmessage['timesent'])); ?></p>
        </div>
        <?php endforeach; ?>
    </main>

    <?php include "includes/Footer.php"; ?>

</div>
</body>
</html>