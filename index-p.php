<?php 
  require "includes/Connection.php";
  require "includes/QueryBuilder.php";
  session_start();
  $conn = Connection::conn();
  $query = new QueryBuilder($conn);

  $userId = $_GET['id'];

  $userInfo = $query->selectAllWhere("users", "id", $userId);
  $username;
  foreach($userInfo as $user){
    $username = $user['username'];
  }
  
  if(!isset($_GET['page'])){
    $page = 1;
  }else{
    $page = $_GET['page'];
  }
  $forumPosts = $query->getUserForumPosts($page, $userId);
  $numberOfPosts = $query->numberOfPagesUser($userId);
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
  <?php 
    include "includes/Navigation-Bar.php";
  ?>
  <!-- PROFILE -->
<main>
  <div class="profile-card-column">
    <div class="profile-container">
      <div class="profile-info">
        <i class="fa fa-user-circle fa-5x" aria-hidden="true"></i><br>
        <h3 class="username"><?php echo $username;  ?></h3>
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

        <?php 
          if(isset($_SESSION['logged_in']) && $_SESSION['userId'] == $_GET['id']){
            echo '<li><a class="active" href="javascript:function1()" id="postsLink">My Posts</a></li>';
            echo '<li><a href="javascript:function2()" id="profileSettingsLink">Profile Settings</a></li>';
          }else{
            echo '<li><a class="active" id="postsLink">Users Posts</a></li>';
          }
        ?>
        
      </ul>
    </div>
    
    <div class="user-posts go-middle" id="user-post-info">
      <?php 
        if(count($forumPosts) == 0){
          echo "<h4 style='font-size: 30px; padding: 0 10px; margin-bottom: 30px;' >User hasn't made any forum posts yet</h4>";
        }
      ?>
      <?php foreach($forumPosts as $forumPost): ?>
        <div class="user-post-left">
        <i class="fa fa-user-circle fa-5x" aria-hidden="true"></i>
        <h4 style="align-self: center; padding: 0 10px;"><a href="<?= "forumpost.php?category=" . $forumPost['category'] . "&id=" . $forumPost['id']; ?>"><?= $forumPost['forumposttitle'] ?></a></h4>
      </div>
      <div class="content-user-post" >
        <?php 
          echo substr($forumPost['forumpostcontent'], 0, 30) . '...';
        ?>
      </div>
        <ul class="post-list-row">
          <li>
            <a href="#"><i class="fa fa-heart" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-comment" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-share" aria-hidden="true"></i></a>
          </li>
        </ul>

      <?php endforeach; ?>

      <ul>
        <?php 
              $numberOfPages = $query->numberOfPagesUser($_GET['id']);
							for($page=1;$page <= $numberOfPages; $page++){
								if(isset($_GET['page'])){
                    echo '<a href="index-p.php?id=' . $_GET['id'] .'&page=' . $page .'">' . '<li>' . $page . '</li>' . '</a>';	
							  }else{
                    echo '<a href="index-p.php?id=' . $_GET['id'] .'&page=' . $page .'">' . '<li>' . $page . '</li>' . '</a>';	
                }
              }
        ?>
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
