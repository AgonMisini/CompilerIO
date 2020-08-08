<?php 
    require "../includes/Connection.php";
    if(isset($_GET['newsPostId']) && isset($_GET['newsCommentId'])){
        $newsPostId = $_GET['newsPostId'];
        $newsCommentId = $_GET['newsCommentId'];
    }
    if(isset($_POST['editNewsComment'])){
        $newsComment = $_POST['newsCommentText'];
        $conn = Connection::conn();

        $stmt = $conn->prepare("UPDATE newscomment SET newscommenttext = :newscomment WHERE id = :id");
        $stmt->bindParam(":newscomment", $newsComment);
        $stmt->bindParam(":id", $newsCommentId);
        $stmt->execute();
        header("Location: ../newspost.php?id=" . $newsPostId  . "&success=commentEdited");
    }
?>