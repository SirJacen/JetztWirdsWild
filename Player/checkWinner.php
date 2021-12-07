<?php
//Might delete this and switch to AJAX
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
session_start();
debugging("no");

$_SESSION['played'] = true;
$_SESSION['answer'] = $_POST['answer'];

if ($_POST['answer'] == $_POST['rightAnswer']){
    $currentPoints = playerPoints();
    if($_SESSION['player'] == 1) {
        $currentPoints->Player1++;
    }
    elseif ($_SESSION['player'] == 2){
        $currentPoints->Player2++;
    }
    $playerDir = $root."/Player/playerPoints.json";
    file_put_contents($playerDir, json_encode($currentPoints));
    $_SESSION['correct'] = true;
} else {
    $_SESSION['correct'] = false;
}
//Return to either Playsite 1 or 2
//header("Location:playerInterface.php");
header("Location:rightOrWrong.php");
