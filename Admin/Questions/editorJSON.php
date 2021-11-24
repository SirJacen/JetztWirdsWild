<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";

$block = $_POST['block'];
$key = $_POST['selectedQuestion'];
$tmpArray = openJSON($block);
$array = $tmpArray[$key];

if($block == 1 || $block == 3) {
    $array['questionName'] = $_POST['questionName'];
    $array['answer'] = $_POST['answer'];
} elseif($block == 2) {
        $array['questionName'] = $_POST['questionName'];
        $array['answerA'] = $_POST['answerA'];
        $array['answerB'] = $_POST['answerB'];
        $array['answerC'] = $_POST['answerC'];
        $array['answerD'] = $_POST['answerD'];
} elseif($block == 4){
        $array['questionName'] = $_POST['questionName'];
}else {
    echo "<h1>OH OH</h1>";
    $frage = [
        "questionName" => "ERROR",
        "blockSession" => $_SESSION['block'],
        "date" => date("d-m-Y"),
        "time" => date("h:i:sa"),
        "site" => "editorJSON.php"
    ];
    $block = "ERROR";
}
$tmpArray[$key] = $array;
json($tmpArray, $block, true);
$_SESSION['edited'] = true;
header("Location:editQuestion.php");
