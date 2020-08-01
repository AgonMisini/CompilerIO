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
    <link rel="stylesheet" href="css/l-style.css">
    <title>Login - Register</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include "includes/Navigation-bar.php";?>

    <div class="container">
        <div class="form-box">
            <h1>Login</h1>
            <div class="input-group">
                <form method="POST">
                    <input type="text" id="email" name="username" class="email-field" placeholder="Enter your username..." required style="color: black;">
                    <input type="password" id="password" name="password" class="password-field" placeholder="Enter your password..." required style="color: black;">
                    <div class="checkbox-style">
                        <input type="checkbox" id="checkbox" class="check-box">
                        <label for="checkbox">Remember my password</label>
                    </div>
                    <button class="btn" type="submit" name="loginButton">Login</button>
                </form>
                
            </div>
            <div class="message">
                <span>If you don't have an account click <a href="index-r.php">here</a> to register</span>
            </div>
        </div>
    </div>

     <!-- FOOTER -->
     <?php include 'includes/footer.php'; ?>
</body>
</html>