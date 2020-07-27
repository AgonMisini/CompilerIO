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
  <nav>
		<ul class="user-links">
			<li><a href="#">Home</a></li>
			<li><a href="#">News</a></li>
			<li><a href="#">Tutorial</a></li>
			<li><a href="#" class="active">User</a></li>
			<li><a href="#">About Us</a></li>
			<li id="log-in-btn"><a href="#" id="log-in-btn">Login / Register</a></li>
		</ul>
  </nav>
  <!-- PROFILE -->
  <div class="profile-card-column">
    <div class="profile-container">
      <div class="profile-info">
        <i class="fa fa-user-circle fa-5x" aria-hidden="true"></i><br>
        <h3 class="username">john_doe</h3>
        <p class="user-bio" style="font-family: 'Segoe UI';"><span style="font-family: monospace; font-size: 25px; font-weight: 600; display: inline-block; margin: 5px 0;">Bio</span>
          <br>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo, vero ad corrupti beatae fuga labore nesciunt magni tempora voluptatum consequuntur.</p>
          <hr>
        <div class="user-contacts">
          <p style="font-family: monospace; font-size: 25px; font-weight: 600; display: inline-block; margin: 5px 0;">Contacts<br></p>
              <ul class="user-social-ico go-middle">
                <li>
                  <a href="" class="social-ico"><i class="fab fa-twitter"></i></a>
                  <a href=""><i class="fab fa-facebook-square"></i></a>
                  <a href=""><i class="fab fa-git-alt"></i></a>
                </li> 
              </ul>
        </div>
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
        <li><a class="active" href="index-p.html">My Posts</a></li>
        <li><a href="index-s.html">Profile Settings</a></li>
      </ul>
    </div>
    
    <div class="user-posts go-middle">
      <div class="user-post-left">
        <i class="fa fa-user-circle fa-5x" aria-hidden="true"></i>
        <h4 style="align-self: center; padding: 0 10px;">Lorem Ipsum</h4>
      </div>
      <hr style="margin: 10px 0;">
      <p style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: left;">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Corporis, saepe dolorem adipisci quo, perspiciatis a quia accusantium fugiat ab eligendi similique. Dignissimos placeat porro cupiditate dolorem provident! Quod, numquam neque?</p>
   
        <ul class="post-list-row">
          <li>
            <a href="#"><i class="fa fa-heart" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-comment" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-share" aria-hidden="true"></i></a>
          </li>
        </ul>
      </div>
    </div>

    
  </div>
  </div>
</div>
</body>
</html>
