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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="css/l-style.css">
    <link rel="stylesheet" href="css/style-login-sighnup.css">
    <title>Login - Register</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include "includes/Navigation-bar.php";?>
<h1>Login | Sign up</h1>
<div class="hero">
    <div id="form-box1" class="form-box1">
        <div class="button-box">
            <div id="btn1"></div>
            <button type="button" id="login-btn" class="toggle-btn" onclick="login()">Log In</button>
            <button type="button" id="sighnup-btn" class="toggle-btn" onclick="sighnup()">Sign Up</button>
        </div>
        <form method="POST" id="log-in-form" class="input-group1">
            <input type="text" class="input-field1" placeholder="Username" name="username">
            <input type="password" class="input-field1" placeholder="Password" name="password">
            <span class="go-row remember-me">
                <input type="checkbox" class="check-box1"> <span style="font-size:20px;">Keep me signed in</span>
            </span>
            <button type="submit" class=" btn submit-btn" name="loginButton">Log In</button>
        </form>
        <form method="POST" id="sign-up-form" class="input-group1">
            <input type="text"  id="fname" name="username" class="fname-field input-field1" placeholder="Enter your username...">
            <input type="email" id="email" name="email" class="email-field input-field1" placeholder="Enter your email..." required>
            <input type="password" id="password" name="password" class="password-field input-field1" placeholder="Enter your password...(8 to 26 chars)" required>
            <input type="password" id="password" name="confirmPassword" class="password-field input-field1" placeholder="Confirm password..." required>
            <div class="go-row">
                <input type="checkbox" id="checkbox" class="check-box">
                <label for="checkbox" id="agree-terms">I agree to <a  href="#">Terms & Conditions.</a></label>
            </div>
            <button class="submit-btn" type="submit" name="registerButton">Register</button>
        </form>
    </div>
</div>

<script>
    var x = document.getElementById('log-in-form');
    var y = document.getElementById('sign-up-form');
    var z = document.getElementById('btn1');
    var text1 = document.getElementById('login-btn');
    var text2 = document.getElementById('sighnup-btn');
    var alterForm = document.getElementById("form-box1");

    function sighnup() {
        x.style.left = "-400px";
        y.style.left = "50px";
        z.style.left = "106px";
        z.style.backgroundPosition = "100%";
        text1.style.color = "white";
        text2.style.color = "black";
        alterForm.style.height = "470px";
    }

    function login() {
        x.style.left = "50px";
        y.style.left = "450px";
        z.style.left = "2px";
        z.style.backgroundPosition = "0%";
        text1.style.color = "black";
        text2.style.color = "white";
        alterForm.style.height = "400px";
        
    }
</script>

<!-- EXPERIMENT -->



     <!-- FOOTER -->
     <?php include 'includes/footer.php'; ?>
</body>
</html>