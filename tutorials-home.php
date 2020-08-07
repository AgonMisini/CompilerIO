<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="css/style-tutorials-home.css">
    <title>Tutorials | Home</title>
</head>
<body>
<?php include  "includes/Navigation-bar.php"; ?>

<h1 class="title1">Tutorials</h1>

<div class="tutorials-home-main-container go-column">
    <div class="main-tutorial-list-wrapper align-center go-column">
        <div class="tutorial-list-wrapper go-row">
            <a class="tutorial-card-link1" href="">
                <div class="tutorial-card1 go-column ">
                    <h2  class="tutorial-subtitle subtitle1">HTML</h2>
                    <p class="hello-world">Hello World</p>
                    <p id="content-css-color" class="html5">&lt;HTML></p>
                    <img class="html-ico" src="pic\html-code.png" alt="html-logo">
                </div>
            </a>
            <a class="tutorial-card-link2" href="#">
                <div class="tutorial-card2 go-column justify-center align-center">
                    <h2 class="tutorial-subtitle subtitle2">CSS</h2>
                </div>
            </a>
        </div>
        <div class="tutorial-list-wrapper go-row ">
            <a class="tutorial-card-link3" href="#">
                <div class="tutorial-card3 go-column align-center">
                    <h2 id="php" class="tutorial-subtitle subtitle3">PHP</h2>
                    <div class="type_box">
                        <textarea id="typing-text" aria-label="typing text" readonly></textarea>
                    </div>
                </div>
            </a>
            <a class="tutorial-card-link4" href="#">
                <div class="tutorial-card4 go-column align-center">
                    <h2 class="tutorial-subtitle subtitle4">SQL</h2>
                    <img class="sql-ico" src="pic/sql-ico.png" alt="SQL Ico">
                </div>
            </a>
        </div>

    </div>
</div>


<script>
    (function () {
        var CharacterPos = 0;
        var MsgBuffer = "";
        var TypeDelay = 100; 
        var NxtMsgDelay = 1000;
        var MsgIndex = 0;
        var delay;
        var MsgArray = ['echo "Hello World!</h1>"'];

        function StartTyping() {
            var id = document.getElementById("typing-text");
            if (CharacterPos != MsgArray[MsgIndex].length) {
                MsgBuffer  = MsgBuffer + MsgArray[MsgIndex].charAt(CharacterPos);
                id.value = MsgBuffer+"_";
                delay = TypeDelay;
                id.scrollTop = id.scrollHeight; 
            } else {
                delay = NxtMsgDelay;
                MsgBuffer   = "";
                CharacterPos = -1;
                if (MsgIndex!=MsgArray.length-1){
                MsgIndex++;
                }else {
                MsgIndex = 0;
                }
            }
            CharacterPos++;
            setTimeout(StartTyping,delay);
        }
        StartTyping();
    })();
</script>

<?php include "includes/footer.php"; ?>
    
</body>
</html>