<?php 
  require "includes/Connection.php";
  require "includes/QueryBuilder.php";
  session_start();
  $conn = Connection::conn();
  $query = new QueryBuilder($conn);

  if(isset($_POST['submitButton'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $messageInformation = array($name, $email, $subject, $message);
    $query->insertContactUsMessage($messageInformation);
  }

 

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="contact-us.css">
  <title>Contact Us</title>
</head>
<body>
  <nav>
		<ul>
			<li><a href="#">Home</a></li>
			<li><a href="#">News</a></li>
			<li><a href="#">Tutorial</a></li>
      <li><a href="about-us.php">About Us</a></li>
      <li><a href="#" class="active">Contact Us</a></li>
			<li><a href="#">Login - Register</a></li>
		</ul>
    </nav>

  <h3>Contact Us</h3>
  <?php 
     if(isset($_GET['error'])){
      echo "<p class='error' style='margin-left: 20px; color:white;'>You cant send a message with an empty field</p>";
      }
  ?>

  

<div class="container">
  <form method="POST">
    <label for="fname">Name</label>
    <input type="text" id="fname" name="name" placeholder="">

    <label for="fname">Email</label>
    <input type="email" id="fname" name="email" placeholder="">

    <label for="fname">Subject</label>
    <input type="text" id="fname" name="subject" placeholder="">

    <label for="subject">Message</label>
    <textarea id="subject" name="message" placeholder="Write something.." style="height:200px"></textarea>
    <div class="btn">
      <input type="submit" value="Submit" name="submitButton">
    </div>
    
  </form>
</div>
</body>
</html>