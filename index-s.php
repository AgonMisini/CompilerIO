<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
  <link rel="stylesheet" href="css/style-profile.css">
  <title>Profile Settings</title>
</head>
<body>
<div class="body-column">
  <!-- NAVBAR -->
  <nav>
		<ul>
			<li class="user-links"><a href="#">Home</a></li>
			<li><a href="#">News</a></li>
			<li><a href="#">Tutorial</a></li>
			<li><a href="#" class="active">User</a></li>
			<li><a href="#">About Us</a></li>
			<li id="log-in-btn"><a href="#">Login - Register</a></li>
		</ul>
  </nav>
  <!-- PROFILE -->
  <div class="profile-card-column">
    <div class="profile-container">
      <div class="profile-info">
        <i class="fa fa-user-circle fa-5x" aria-hidden="true"></i><br>
        <h3 class="username">john_doe</h3>
        <p class="user-bio" style="font-family: 'Segoe UI';"><span style="font-family: monospace; font-size: 25px; font-weight: 600; display: inline-block; margin: 5px 0;">Bio</span><br>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo, vero ad corrupti beatae fuga labore nesciunt magni tempora voluptatum consequuntur.</p>
      <hr style="margin: 15px 0;">
  </div>
</div>
</div>
  <div class="tabs go-middle">
    <ul class="user-links">
      <li><a href="index-p.html">My Posts</a></li>
      <li><a class="active" href="index-s.html">Profile Settings</a></li>
    </ul>
  </div>

    <!-- USER FORM -->
    <div class="user-form-column go-middle">
      <form>
        <div class="picture-ch">
          <i class="fa fa-user-circle fa-4x" aria-hidden="true"></i>
          <input class="user-submit-btn" type="submit" name="submit-changes"></input>
        </div>
        <div class="username-ch user-form-input">
          <label class="centered-element" for="">Change your Username</label>
          <input type="text" id="username" placeholder="Change your username here">
          <input type="password" id="password" placeholder="Enter your password to save the change">
        </div>
        <div class="password-ch user-form-input">
          <label class="centered-element" for="">Change your Password</label>
          <input type="password" id="old" placeholder="Enter your old password here">
          <input type="password" id="new" placeholder="Enter your new password here">
          <input type="password" id="verify"  placeholder="Re-enter your new password to verify">
          <input class="user-submit-btn" type="submit" name="submit-changes"></input>
        </div>
      </form>
    </div>
</div>

</body>
</html>
