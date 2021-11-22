<?php
/**
 * Jetzt wirds wild
 */

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
debugging("nein");
session_start();
if ($_SESSION['wrongID'] == true){
    function_alert("Falsche LogIn-Daten!");
}
openSide();
logInOverlay();
closeSide();

function logInOverlay(){
    echo '
<h2>JETZT WIRD\'S WILD!</h2>
<div id="id01" class="loginform">

    <form class="login" action="weiterleiter.php" method="post">
        <div class="imgcontainer">

            <div class="container">
                <label for="username"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="username" required><br>

                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="psw" required>

                <br><button type="submit">Login</button>

            </div>
        </div>
        </form>
</div>
';
}
