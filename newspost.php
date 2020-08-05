<?php 
    require 'includes/Connection.php';
    require 'includes/QueryBuilder.php';
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    session_start();
    $newspostId = $_GET['id'];

    $newsPost = $query->selectAllWhere("newsposts", "id", $newspostId);
    
    foreach($newsPost as $newsPost){
        $title = $newsPost['newsposttitle'];
        $content = $newsPost['newspostcontent'];
        $date = date('H:i:s A M/d', strtotime($newsPost['timeposted']));
        $userId = $newsPost['userid'];
    }
    $userInfo = $query->selectAllWhere("users", "id", $userId);
    foreach($userInfo as $user){
        $username = $user['username'];
        $profilePicture = $user['profilepic'];
    }

    $comments = $query->selectAllWhere("newscomment","newspostid", $newspostId);
    $commentsMade = count($comments);

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
      


    <?php include "includes/footer.php"; ?>
</body>
</html>