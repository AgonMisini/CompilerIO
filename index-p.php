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
    $profileUsername = $user['username'];
    $bio = $user['bio'];
    $email = $user['email'];
    $profilePicture = $user['profilepic'];
  }
  if(!isset($_GET['page'])){
    $page = 1;
  }else{
    $page = $_GET['page'];
  }
  $forumPosts = $query->getUserForumPosts($page, $userId);
  $numberOfPosts = $query->numberOfPagesUser($userId);

  //Add bio
  if(isset($_POST['submitBio'])){
    $bio = $_POST['bio'];

    $query->insertBio($userId, $bio);
  }
  //Deactivate Account
  if(isset($_POST['deactivateAccount'])){
    $query->deleteUser($_GET['id']);
  }
  //Change profile information
  if(isset($_POST['submit-changes'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password= $_POST['password'];
    $confirmPassword= $_POST['confirmPassword'];

    $userInformation = array($username, $email, $password, $confirmPassword, $_GET['id']);
    $query->changeProfileInformation($userInformation);
  }

  //Change profile picture
  if(isset($_POST['changePicture'])){
    $query->changeProfilePicture($_GET['id']);
  }


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
      <?php if(isset($_GET['error'])){
              if($_GET['error'] == "emptyFields"){
                echo "<p>Empty fields were submitted</p>";
              }else if($_GET['error'] == 'usernameTaken'){
                echo "<p>Username is already taken</p>";
              }else if($_GET['error'] == 'emailTaken'){
                echo "<p>Email is already taken</p>";
              }else if($_GET['error'] == 'passwordDoesntMatch'){
                echo "<p>Passwords written don't match</p>";
              }else if($_GET['error'] == 'passwordTooShortOrTooLong'){
                echo"<p>Password is  shorter than 8 characters or longer than 26 characters</p>";
              }else if($_GET['error'] == 'samePasswordAsBefore'){
                echo"<p>Password is the same as the old one</p>";
              }else if($_GET['error'] == 'failedToUploadImage'){
                echo"<p>Failed to change profile image</p>";
              }
            }else if(isset($_GET['success'])){
                if($_GET['success'] == 'successfulChange'){
                  echo"<p>Profile information successfully changed</p>";
                }else if($_GET['success'] == 'profilePicChanged'){
                  echo"<p>Profile picture successfully changed</p>";
                }
               
            }
            
            ?>
        <!-- <i class="fa fa-user-circle fa-5x" aria-hidden="true"></i><br> -->
        <img src="<?php echo $profilePicture; ?>" alt="" width="100" height="100" style="display: block; margin: 0 auto; margin-bottom: 10px; border-radius: 100px;">
        <h3 class="username"><?php echo $profileUsername;  ?></h3>
        <p class="user-bio" style="font-family: 'Segoe UI';"><span style="font-family: monospace; font-size: 25px; font-weight: 600; display: inline-block; margin: 5px 0;">Bio</span>
          <br><?php 
            if(isset($_SESSION['logged_in'])){
              if($_SESSION['userId'] == $_GET['id']){
                if(empty($bio)){
                  echo '<form method="POST">';
                  echo '<textarea name="bio" id="" cols="30" rows="10"></textarea>';
                  echo ' <input type="submit" name="submitBio" style="display:block; margin: 0 auto; padding: 5px;">';
                  echo '</form>';
                }else{
                  echo $bio;
                }
              }else{
                if(empty($bio)){
                  echo "User doesn't have a bio yet";
                }else{
                  echo $bio;
                }
              }
            }
          ?></p>
        <div class="user-buttons">
          <form method="POST">
            
            <?php if(isset($_SESSION)){
              if($_SESSION['userId'] == $_GET['id']){
                echo '<hr style="margin: 15px 0 20px">';
                echo "<button class='myButton' onClick=\"javascript: return confirm('Please confirm the deactivation of your account');\" name='deactivateAccount'>Deactivate Account</button>";
              }
            } ?>
          </form>
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
        <img src="<?php echo $profilePicture; ?>" alt="" width="100" height="100" style="display: block; margin-bottom: 10px; border-radius: 100px;">
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
            <h3 class="username">Edit profile</h3>
            <input type="text" name="username" id="username" placeholder="Enter your Username" value="<?php echo $username; ?>">
            <input type="email" name="email" id="email" placeholder="Enter your Email" value="<?php echo $email; ?>">
            <input type="password" name="password" id="new" placeholder="Enter your new Password">
            <input type="password" name="confirmPassword" id="old" placeholder="Confirm Password">
            <input class="user-submit-btn" type="submit" name="submit-changes"></input>
        </div>
      </form>
      <div class="username-ch user-form-input" style="margin-top: 10px;">
          <form method="POST" enctype="multipart/form-data">
            <h3 class="username">Change profile picture</h3>
            <input type="file" style="margin: 10px; auto; margin-left: 100px;" name="photo">
            <input type="submit" class="user-submit-btn" name="changePicture">
          </form>
      </div>
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
