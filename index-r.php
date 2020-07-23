<?php
    require "includes/Connection.php";
    include "includes/QueryBuilder.php";
    session_start();
    if(isset($_SESSION['logged_in'])){
        header("Location: index.php");
    }
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    if(isset($_POST['registerButton'])){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        $userInformation = array($username, $email, $password, $confirmPassword);
        $query->insertUser($userInformation);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="r-style.css">
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
            <h1>Register</h1>
            <div class="input-group">
                <form method="POST">
                    <input type="text"  id="fname" name="username" class="fname-field" placeholder="Enter your username...">
                    <input type="email" id="email" name="email" class="email-field" placeholder="Enter your email..." required>
                    <input type="password" id="password" name="password" class="password-field" placeholder="Enter your password..." required>
                    <input type="password" id="password" name="confirmPassword" class="password-field" placeholder="Confirm password..." required>
                    <div>
                        <input type="checkbox" id="checkbox" class="check-box">
                        <label for="checkbox">I agree to <a href="#">Terms & Conditions</a>.</label>
                    </div>
                    <button class="btn" type="submit" name="registerButton">Register</button>
                </form>
                
            </div>
            <div class="message">
                <span>If you have an account click <a href="index-l.php">here</a> to login.</span>
            </div>
        </div>
    </div>
</body>
</html>