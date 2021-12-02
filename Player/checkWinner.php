<?php
//Might delete this and switch to AJAX
$root = $_SERVER['DOCUMENT_ROOT'];
debugging("ja");
if ($_POST['answer'] == $_POST['rightAnswer']){
    $currentPoints = playerPoints();
    $currentPoints -> Player.$_POST['player'] ++;
    $playerDir = $root."\Player\playerPoints.json";
    file_put_contents($playerDir, json_encode($currentPoints));
}
header("Location:playerInterface.php");
