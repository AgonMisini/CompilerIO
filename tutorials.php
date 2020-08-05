<?php 
    require "includes/Connection.php";
    require "includes/QueryBuilder.php";
    session_start();
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);

    $language = $_GET['language'];
    $numberOfTutorial = $_GET['numberOfTutorial'];

    $stmt = $conn->prepare("SELECT * FROM tutorials WHERE lang = :lang AND tutorialnumber = $numberOfTutorial;");
    $stmt->bindParam(":lang", $language);
    $stmt->execute();
    $tutorial = $stmt->fetchAll();
    foreach($tutorial as $tutorial){
        $title = $tutorial['title'];
        $content = $tutorial['content'];
    }
    
    $stmt = $conn->prepare("SELECT * FROM tutorials WHERE lang = :lang AND tutorialnumber = $numberOfTutorial-1;");
    $stmt->bindParam(":lang", $language);
    $stmt->execute();
    $previousTutorialPossible = count($stmt->fetchAll());


    $stmt = $conn->prepare("SELECT * FROM tutorials WHERE lang = :lang AND tutorialnumber = $numberOfTutorial+1;");
    $stmt->bindParam(":lang", $language);
    $stmt->execute();
    $nextTutorialPossible = count($stmt->fetchAll());
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.3/styles/darcula.min.css" />    
    <link rel="stylesheet" href="css/tutorials.css">
    <title>Document</title>
</head>
<body>
    <?php include "includes/Navigation-bar.php"; ?>
    <div class="go-row align-center">
        <h1 id="title1"><?php echo $title;?></h1>
    </div>
    <div class="main-content-wrapper go-column" style="width: 80vw; margin: 0 auto;"><?php  echo $content; ?> </div>
    
    <div class="go-row justify-center">
        <?php if($previousTutorialPossible == 1): ?>
            <li class="tutorial-jump-page align-center"><a  href="tutorials.php?numberOfTutorial=<?php echo $numberOfTutorial-1 . "&language=" . $_GET['language']; ?>">Previous tutorial</a></li>
        <?php endif ?>


        <?php if($nextTutorialPossible == 1): ?>
            <li class="tutorial-jump-page align-center"><a  href="tutorials.php?numberOfTutorial=<?php echo $numberOfTutorial+1 . "&language=" . $_GET['language']; ?>">Next tutorial</a></li>
        <?php endif ?>
    </div>
    <?php include "includes/footer.php"; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.3/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
</body>
</html>