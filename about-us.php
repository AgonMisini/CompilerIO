<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style-about-us.css">
	<title>About Us</title>
</head>
<body>
    <header>
        <?php include 'includes/Navigation-Bar.php'; ?>
    </header>
    <!-- CONTENT -->
<main>
    <div class="container-column">
        <h1>About Us</h1>
        <div class="container-2">
            <div class="project">
                
                <div class="title">
                    <h2>Our Project</h2>
                </div>
                <hr style="margin: 10px 0;">
                <div class="description">
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus quisquam expedita, laudantium magnam sed animi iusto impedit repudiandae est. Dolores, nostrum nobis! Veniam facilis, suscipit fugiat odit ipsam aut culpa?<br>
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Delectus dolores odio impedit corrupti neque consequuntur ipsam quos quas, deleniti modi tempore? Veritatis quis ducimus libero quos debitis. Iusto, non labore?<br>
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Iure tenetur, error voluptatem accusantium, rem illo obcaecati sed impedit unde nulla vitae laborum vel id veniam doloribus. Sed aut nam officia. <br>
                        Lorem ipsum dolor, sit amet consectetur quisquam vel ipsam illum exercitationem sunt adipisci, voluptas cum enim explicabo officia ex nihil, soluta, rerum repudiandae commodi laboriosam?<br>
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dolorum perspiciatis, explicabo modi perferendis saepe nobis! Quia et alias cumque ipsam iure quis facilis. Impedit ab quod delectus earum voluptate veniam.
                    </p>
                </div>
            </div>
            <hr style="margin: 10px 0;">

            <div class="team">
                <div class="title-1">
                    <h2>Our Team</h2>
                </div>
                <div class="users">
                    <div class="user-1">
                        <h4 style="font-family: monospace;">Agon Misini</h4>
                        <i class="fa fa-user fa-5x" aria-hidden="true"></i>
                        <hr>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Error sunt corrupti, hic iusto facere mollitia eveniet iure labore inventore recusandae!
                        </p>
                    </div>
                    <div class="user-2">
                        <h4 style="font-family: monospace;">Ardit Islami</h4>
                        <i class="fa fa-user fa-5x" aria-hidden="true"></i>
                        <hr>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam accusantium, in recusandae illum dignissimos voluptate cumque quo! Minima, laboriosam blanditiis.
                        </p>
                    </div>
                    <div class="user-3">
                        <h4 style="font-family: monospace;">Flamur Fazliu</h4>
                        <i class="fa fa-user fa-5x" aria-hidden="true"></i>
                        <hr>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto dolor modi doloribus officiis explicabo sapiente quam aliquam!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    
    <!-- FOOTER -->
    <?php 
        include 'includes/footer.php';
    ?>

    </body>
    </html>