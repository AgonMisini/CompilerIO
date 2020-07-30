<?php 
  // require "includes/Connection.php";
  // require "includes/QueryBuilder.php";
  // $conn = Connection::conn();
  // $query = new QueryBuilder($conn);

  // $userId = 4;

  // $userInfo = $query->selectAllWhere("users", "id", $userId);
  
  // if(!isset($_GET['page'])){
  //   $page = 1;
  // }else{
  //   $page = $_GET['page'];
  // }
  // $forumPosts = $query->getUserForumPosts($page, $userId);

  // $numberOfPosts = $query->numberOfPagesUser($userId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Document</title>
</head>
<body>
<div class="body-column">
  <!-- NAVBAR -->
  <?php include '';
  
  // if($_SESSION['admin'] == 1){
  //     include "includes/Admin-Navigation-Bar.php";
  // }else{
  //     include "includes/Navigation-Bar.php" ;
  // }

  ?>

  <!-- PROFILE -->
<main>
  <div class="profile-card-column">
    <div class="profile-container">
      <div class="profile-info">
        <i class="fa fa-user-circle fa-5x" aria-hidden="true"></i><br>
        <h3 class="username">john_doe</h3>
        <p class="user-bio" style="font-family: 'Segoe UI';"><span style="font-family: monospace; font-size: 25px; font-weight: 600; display: inline-block; margin: 5px 0;">Bio</span>
          <br>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo, vero ad corrupti beatae fuga labore nesciunt magni tempora voluptatum consequuntur.</p>
          <hr style="margin: 15px 0 20px">
        <div class="user-buttons"> 
          <a href="#" class="myButton">Report User</a>
          <a href="#" class="myButton">Add Friend</a>
        </div>
      </div>
    </div>
    <!-- USER POSTS -->
  <div class="user-posts-tabs-column">
    <div class="tabs go-middle">
      <ul class="user-links">
        <li><a class="active" href="javascript:function1()" id="postsLink">My Posts</a></li>
        <li><a href="javascript:function2()" id="profileSettingsLink">Profile Settings</a></li>
      </ul>
    </div>
    
    <div class="user-posts go-middle" id="user-post-info">
      <div class="user-post-left">
        <i class="fa fa-user-circle fa-5x" aria-hidden="true"></i>
        <h4 style="align-self: center; padding: 0 10px;">Lorem Ipsum</h4>
      </div>
      <div class="content-user-post" >
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Corporis, saepe dolorem adipisci quo, perspiciatis a quia accusantium fugiat ab eligendi similique. Dignissimos placeat porro cupiditate dolorem provident! Quod, numquam neque?</p>
      </div>
        <ul class="post-list-row">
          <li>
            <a href="#"><i class="fa fa-heart" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-comment" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-share" aria-hidden="true"></i></a>
          </li>
        </ul>
      </div>
    </div>
    
    <div class="user-form-column go-middle" id="profile-settings" style="display: none;">
      <hr id="user-hr">
      <form class="user-change-settings" method="POST">
        <div class="username-ch user-form-input">
            <input type="text name="username" id="username" placeholder="Enter your Username">
            <input type="email" name="email" id="email" placeholder="Enter your Email">
            <input type="password" name="password" id="new" placeholder="Enter your new Password">
            <input type="password" name="password" id="old" placeholder="Confirm Password">
            <input class="user-submit-btn" type="submit" name="submit-changes"></input>
        </div>
      </form>
    </div>
  </div>
  </div>
</main>
</div>


<script>
    function function1(){
        var x = document.getElementById("user-post-info");
        var y = document.getElementById("profile-settings");
        var btn = document.getElementById("user-buttons");


        x.style.display = "block";
        y.style.display = "none";


        var z = document.getElementById("postsLink");
        var v = document.getElementById("profileSettingsLink");
        z.setAttribute("class", "active");
        v.setAttribute("class","");
    }

    function function2(){
        var x = document.getElementById("user-post-info");
        var y = document.getElementById("profile-settings");

        x.style.display = "none";
        y.style.display = "block";

        var z = document.getElementById("profileSettingsLink");
        var v = document.getElementById("postsLink");

        z.setAttribute("class", "active");
        v.setAttribute("class","");


    }
</script>

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>

</body>
</html>
