
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
            <li class="user-links"><a href="index.php" class="<?= ($activePage == 'index') ? 'active':''; ?>">News</a></li>
            <li class="user-links"><a href="tutorials.php" class="<?= ($activePage == 'tutorials') ? 'active':''; ?>">Tutorial</a></li>
            <li class="user-links"><a href="about-us.php" class="<?= ($activePage == 'about-us') ? 'active':''; ?>">About Us</a></li>
            <li class="user-links"><a href="contact-us.php" class="<?= ($activePage == 'contact-us') ? 'active':''; ?>">Contact Us</a></li>

        <?php
            if(isset($_SESSION['admin'])){
                if($admin){
                    echo '<li><a href="messages.php" class="<?= ($activePage == "messages.php") ? "active":""; ?>Messages</a></li>';
                }
            }
            
        ?>
        
        <?php 
            if(isset($_SESSION['logged_in'])){
                if($activePage == "index-p"){
                    echo '<li id="log-in-btn"><a href="index-p.php?id=' . $userId . '" class="active">'  . 'Welcome ' . $username . '</a></li>';
                }else{
                    echo '<li id="log-in-btn"><a href="index-p.php?id=' . $userId . '">'  . 'Welcome ' . $username . '</a></li>';
                }
                echo "<form method='POST'>";
                echo '<button class="myLogoutButton" name="logoutButton">Logout</button>';
                echo "</form>";
            }else{
                echo '<li id="log-in-btn"><a href="index-l.php" class="<?= ($activePage == "index-r.php" || $activePage == "index-l.php") ? "active":""; ?>Login - Register</a></li>';
            }
        ?>
    </ul>
</nav>