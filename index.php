<?php 
    include 'includes/Connection.php';
    include 'includes/QueryBuilder.php';
    session_start();;
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    var_dump($_SESSION);
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        include "includes/Navigation-bar.php";
    ?>
</body>
</html>