<?php 
    require "includes/Connection.php";
    require "includes/QueryBuilder.php";
    session_start();
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);

    $contactmessages = $query->selectAll("contactmessages");

    if(isset($_POST['deleteMessage'])){
        $contactId = $_POST['messageid'];

        $query->deleteContactMessage($contactId);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="css/Admin-Messages.css">
    <title>Admin | Messages</title>
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
            <form method="POST">
                <input type="hidden" name="messageid" value="<?php echo $contactmessage['id']; ?>">
                <button class="myButton" name="deleteMessage">Remove message</button>
            </form>
        </div>
        
        <?php endforeach; ?>
    </main>

    <?php include "includes/Footer.php"; ?>

</div>
</body>
</html>