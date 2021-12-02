<?php
//Might delete this and switch to AJAX
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
debugging("nein");

if ($_POST['answer'] == $_POST['rightAnswer']){
    $currentPoints = playerPoints();
    if($_POST['player'] == 1) {
        $currentPoints->Player1++;
    } elseif ($_POST['player'] == 2){
        $currentPoints->Player2++;
    }
    $playerDir = $root."\Player\playerPoints.json";
    file_put_contents($playerDir, json_encode($currentPoints));
}
header("Location:playerInterface.php");
