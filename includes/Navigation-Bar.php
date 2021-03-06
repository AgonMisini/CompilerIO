
<?php
    require_once "Connection.php";
    require_once "QueryBuilder.php";
    $conn = Connection::conn();
    $query = new QueryBuilder($conn);
    if(isset($_SESSION['logged_in'])){
        $userInformation = $conn->query("SELECT * FROM users WHERE id = " . $_SESSION['userId']);
        foreach($userInformation as $user){
            $userId = $user['id'];
            $username = $user['username'];
            $admin = $user['admin'];
        }
    }
    $activePage = basename($_SERVER['PHP_SELF'], ".php");

    if(isset($_POST['logoutButton'])){
        $query->logout();
    }
?>
<nav>
    <ul class="user-list-links">
            <li class="user-links"><a href="index.php" class="<?= ($activePage == 'index') ? 'active':''; ?>">Home</a></li>
            <li class="user-links"><a href="forums.php" class="<?= ($activePage == 'forums') ? 'active':''; ?>">Forums</a></li>   

            <li  class="dropdown"><a href="tutorials-home.php"class="<?= ($activePage == 'tutorials' || $activePage == 'tutorials-home') ? 'active':''; ?>">Tutorials &nbsp; <i class="fa fa-sort-down"></i></a>
                <ul >
                    <li><a href="tutorials.php?numberOfTutorial=1&language=HTML">HTML</a></li>
                    <li><a href="tutorials.php?numberOfTutorial=1&language=CSS">CSS</a></li>
                </ul>
            </li>
            <li class="user-links"><a href="about-us.php" class="<?= ($activePage == 'about-us') ? 'active':''; ?>">About Us</a></li>
            <li class="user-links"><a href="contact-us.php" class="<?= ($activePage == 'contact-us') ? 'active':''; ?>">Contact Us</a></li>

        <?php
            if(isset($_SESSION['admin'])){
                if($admin){
                    if($activePage == "admin-messages"){
                        echo '<li class="user-links"><a href="admin-messages.php?id=" class="active">'  . 'Messages' . '</a></li>';
                    }else{
                        echo '<li class="user-links"><a href="admin-messages.php?id=">'  . 'Messages' . '</a></li>';
                    }
                }
            }
            
        ?>
        
        <?php 
            if(isset($_SESSION['logged_in'])){
                if($activePage == "index-p"){
                    echo '<li id="log-in-btn"><a href="index-p.php?id=' . $userId . '" style="background-color:white; color:black;">'  . 'Welcome ' . $username . '</a></li>';
                }else{
                    echo '<li id="log-in-btn"><a href="index-p.php?id=' . $userId . '">'  . 'Welcome ' . $username . '</a></li>';
                }
                echo "<form method='POST'>";
                echo '<button class="myLogoutButton" name="logoutButton">Logout</button>';
                echo "</form>";
            }else{
                echo '<li id="log-in-btn"><a href="index-l.php" class="<?= ($activePage == "index-r.php" || $activePage == "index-l.php") ? "active":""; ?>Login | Sign Up</a></li>';
            }
        ?>
    </ul>
</nav>