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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
  <link rel="stylesheet" href="css/contact-us.css">
  <title>Contact Us</title>
</head>
<body>
<div class="body-column">
  <!-- NAVBAR -->
  <?php include 'includes/Navigation-Bar.php'; ?>

<main>
  <div class="go-middle">
    <h3 id="">Contact Us</h3>
  </div>
  <?php 
     if(isset($_GET['error'])){
      echo "<p class='error' style='margin-left: 20px; color:white;'>You cant send a message with an empty field</p>";
      }
  ?>

  

  <div class="form-container">
    <form method="POST">
      <label for="fname">Name</label>
      <input type="text" id="fname" name="name" placeholder="Input your Name">

      <label for="fname">Email</label>
      <input type="email" id="fname" name="email" placeholder="Input your Email">

      <label for="fname">Subject</label>
      <input type="text" id="fname" name="subject" placeholder="Input the Subject">

      <label for="subject">Message</label>
      <textarea id="subject" name="message" placeholder="Write something..." style="height:200px"></textarea>
      <div class="btn">
        <input type="submit" value="Submit" name="submitButton">
      </div>
    </form>
  </div>
</div>
</main>

    <!-- FOOTER -->
    <?php 
        include 'includes/footer.php';
    ?>
</body>
</html>