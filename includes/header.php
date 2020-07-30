<?php
    require "Connection.php";
    $conn = Connection::conn();
    session_start();
    if(isset($_SESSION['logged_in'])){
        $userInformation = $conn->query("SELECT * FROM users WHERE id = " . $_SESSION['userId']);
        foreach($userInformation as $user){
            $userId = $user['id'];
            $username = $user['username'];
        }
    }
    $currPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
    echo $currPageName;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
</head>
<body>
    <nav>
		<ul class="user-links">
			<li><a href="#">Home</a></li>
			<li><a href="#">News</a></li>
			<li><a href="#">Tutorial</a></li>
			<li><a href="about-us.php">About Us</a></li>
            <li><a href="contact-us.php">Contact Us</a></li>
            <?php if(isset($_SESSION['logged_in'])){
                echo '<li id="log-in-btn"><a href="index-p.php?id=' . $userId . '">Welcome ' . $username . '</a></li>';
                echo '<button class="myLogoutButton">Logout</button>';
            }else{
                echo '<li id="log-in-btn"><a href="#">Login</a></li>';
                echo '<li id="log-in-btn"><a href="#">Register</a></li>';
            }?>
		</ul>
  </nav>
</body>
</html>