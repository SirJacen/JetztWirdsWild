<?php
//Might delete this and switch to AJAX
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
session_start();
debugging("no");

$_SESSION['played'] = true;
$_SESSION['answer'] = $_POST['answer'];

if($_SESSION['guess']){
    $twoAnswers=false;
    $data=Array($_SESSION['player'] => $_SESSION['answer']);
    file_put_contents("guesses.json", json_encode($data));
    while ($twoAnswers==false){
        $twoAnswers=checkGuesses($_SESSION['player']);
    }
    $array = json_decode(file_get_contents($root."/Player/guesses.json"));
    $answerPlayer1=$array['1'];
    $answerPlayer2=$array['2'];
    $diff1=abs($_SESSION['rightAnswer']-$answerPlayer1);
    $diff2=abs($_SESSION['rightAnswer']-$answerPlayer2);
    $currentPoints = playerPoints();
    if($diff1<$diff2){
        $currentPoints->Player1++;
    }elseif($diff2<$diff1){
        $currentPoints->Player2++;
    }elseif($diff1=$diff2){
        $currentPoints->Player1++;
        $currentPoints->Player2++;
    }
    $playerDir = $root."/Player/playerPoints.json";
    file_put_contents($playerDir, json_encode($currentPoints));
    $_SESSION['correct'] = true;
}else {
    //Gross - Kleinschreibung toleriert
    $answer = strtolower($_POST['answer']);
    $rightAnswer = strtolower($_POST['rightAnswer']);
    if ($answer == $rightAnswer) {
        $currentPoints = playerPoints();
        if ($_SESSION['player'] == 1) {
            $currentPoints->Player1++;
        } elseif ($_SESSION['player'] == 2) {
            $currentPoints->Player2++;
        }
        $playerDir = $root . "/Player/playerPoints.json";
        file_put_contents($playerDir, json_encode($currentPoints));
        $_SESSION['correct'] = true;
    } else {
        $_SESSION['correct'] = false;
    }
}
//Return to either Playsite 1 or 2
//header("Location:playerInterface.php");
header("Location:rightOrWrong.php");

function checkGuesses($player):bool {
    $root = $_SERVER['DOCUMENT_ROOT'];
    $array = json_decode(file_get_contents($root."/Player/guesses.json"));
    if(isset($array[partner($player)])){
        return true;
    }else{
        return false;
    }
}

function partner($player):int {
    if($player==1){
        return 2;
    }else{
        return 1;
    }
}