<?php 
    require "includes/Connection.php";
    include "includes/QueryBuilder.php";
    session_start();
    if(isset($_SESSION['logged_in'])){
        header("Location: index.php");
    }
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    if(isset($_POST['loginButton'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $userInformation = array($username, $password);
        $query->login($userInformation);
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="l-style.css">
    <title>Login - Register</title>
</head>
<body>
    <nav>
		<ul>
			<li><a href="#">Home</a></li>
			<li><a href="#">News</a></li>
			<li><a href="#">Tutorial</a></li>
			<li><a href="..\User\index.html">User</a></li>
			<li><a href="..\About Us\index.html" >About Us</a></li>
			<li><a href="#" class="active">Login - Register</a></li>
		</ul>
    </nav>

    <div class="container">
        <div class="form-box">
            <h1>Login</h1>
            <div class="input-group">
                <form method="POST">
                    <input type="text" id="email" name="username" class="email-field" placeholder="Enter your username..." required>
                    <input type="password" id="password" name="password" class="password-field" placeholder="Enter your password..." required>
                    <div>
                        <input type="checkbox" id="checkbox" class="check-box">
                        <label for="checkbox">Remember my password.</label>
                    </div>
                    <button class="btn" type="submit" name="loginButton">Login</button>
                </form>
                
            </div>
            <div class="message">
                <span>If you don't have an account click <a href="index-r.php">here</a> to register</span>
            </div>
        </div>
    </div>
</body>
</html>