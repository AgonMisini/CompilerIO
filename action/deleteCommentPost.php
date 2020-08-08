<?php
    require "../includes/Connection.php";
    
    if(isset($_GET['commentid']) && isset($_GET['newspostidcomment'])){
        deleteNewsComment($_GET['commentid'], $_GET['newspostidcomment']);
    }
    if(isset($_GET['newspostid'])){
        deleteNewsPost($_GET['newspostid']);
    }

    function deleteNewsComment($newsCommentId, $newsPostId){
        $conn = Connection::conn();
        $stmt = $conn->query("DELETE FROM newscomment WHERE id = " . $newsCommentId);

        header("Location: ../newspost.php?success=commentDeleted&id=" . $newsPostId);
    }
    function deleteNewsPost($newsPostId){
        $conn = Connection::conn();
        $stmt = $conn->query("DELETE FROM newscomment WHERE newspostid = " . $newsPostId);
        $stmt = $conn->query("DELETE FROM postcategories WHERE postid = " . $newsPostId);
        $stmt = $conn->query("DELETE FROM newsposts WHERE id = " . $newsPostId);

        header("Location: ../index.php?success=newsPostDeleted&id=" . $newsPostId);
    }
?>