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
    <link rel="stylesheet" href="css/style-admin-Messages.css">
    <title>Admin | Messages</title>
</head>
<body>
<div class="body-column">
    <?php include "includes/Navigation-bar.php"; ?>

    <main>
        <h1 id="my-message">My Messages</h1>

        <?php foreach($contactmessages as $contactmessage): ?>
        <div class="messages-main-container">
            <h3 class="email content" style="margin-bottom: 10px;"><strong>Email</strong><p> <?php echo $contactmessage['email']; ?> </p></h3>
            <h3 class="title content" style="margin-bottom: 10px;"><strong>Title</strong><p> <?php echo $contactmessage['subject']; ?> </p></h3>
            <h3 class="message content" style="margin-bottom: 10px;"><strong>Message</strong><p> <?php echo $contactmessage['message']; ?> </p></h3>
            <h3 class="date content" style="margin-bottom: 10px;"><strong>Date</strong><p> <?php echo date('h:i:s A M/d', strtotime($contactmessage['timesent'])); ?> </p></h3>
            <hr style="width: 100%; margin-bottom: 20px;">
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