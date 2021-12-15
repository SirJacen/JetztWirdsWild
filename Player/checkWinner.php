<?php
$root = $_SERVER['DOCUMENT_ROOT'];
define("Root", $root);
require_once "$root"."/functions.php";
session_start();
debugging("no");

$block = $_SESSION['block'];
$player = $_SESSION['player'];
$answer = $_SESSION['answer'];

$_SESSION['played'] = true;

if ($block === 1){
    $AnswerDir = $root."/Player/AnswerPlayer".$player.".json";
    $jArray = ["Answer" => $answer];
    file_put_contents($AnswerDir, json_encode($jArray));
    header("Location:checkCloser.php");
} elseif ($block === 2){
    checkAnswer();
    header("Location:rightOrWrong.php");
} elseif ($block === 3){
    checkAnswer();
    header("Location:rightOrWrong.php");
} elseif ($block === 4){
    echo "geht nicht";
} else{
    print_r("ERROR");
    print_r($block);
}

function checkAnswer(){
    $answer = strtolower($_SESSION['answer']);
    $rightAnswer = strtolower($_SESSION['rightAnswer']);
    if ($answer == $rightAnswer) {
        getPoints($_SESSION['player'], Root);
        $_SESSION['correct'] = true;
    } else {
        $_SESSION['correct'] = false;
    }
}