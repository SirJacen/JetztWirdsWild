<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
session_start();
$block = $_POST['block'];

if($block == 1 || $block == 3) {
    $frage = [
        "questionName" => $_POST['questionName'],
        "answer" => $_POST['answer']
    ];
} elseif($block == 2) {
    $frage = [
        "questionName" => $_POST['questionName'],
        "answerA" => $_POST['answerA'],
        "answerB" => $_POST['answerB'],
        "answerC" => $_POST['answerC'],
        "answerD" => $_POST['answerD']
    ];
} elseif($block == 4){
    $frage = [
        "questionName" => $_POST['questionName'],
        "mostOftenAnswer" => $_POST['mostOftenAnswer'],
        "mostOftenAnswerPercentage" => $_POST['mostOftenAnswerPercentage'],
        "oftenAnswer" => $_POST['oftenAnswer'],
        "oftenAnswerPercentage" => $_POST['oftenAnswerPercentage'],
        "middleAnswer" => $_POST['middleAnswer'],
        "middleAnswerPercentage" => $_POST['middleAnswerPercentage'],
        "leastAnswer" => $_POST['leastAnswer'],
        "leastAnswerPercentage" => $_POST['leastAnswerPercentage'],
        "leastOftenAnswer" => $_POST['leastOftenAnswer'],
        "leastOftenAnswerPercentage" => $_POST['leastOftenAnswerPercentage']
    ];
}else {
    echo "<h1>OH OH</h1>";
    $frage = [
        "questionName" => "ERROR",
        "blockSession" => $_SESSION['block'],
        "date" => date("d-m-Y"),
        "time" => date("h:i:sa"),
        "site" => "encoderJSON.php"
    ];
    $block = "ERROR";
}

json($frage, $block);

$_SESSION['message'] = "Finished!";
header("Location:newQuestion.php");