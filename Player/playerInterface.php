<?php

use JetBrains\PhpStorm\ArrayShape;

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
define("CurrentQuestionPath", "$root" . "\Player\Questions");
debugging("nein");

$pfadArray = glob(CurrentQuestionPath."\currentQuestionB*.json");
openSide("..");
if($pfadArray['0']){
    //print_r($pfadArray);
    $currentArray = openFile($pfadArray['0']);
    $name = $currentArray['Question'];
    $block = $currentArray['Block']; //Fragen zählen
    echo "<div class='questionsBlock'><h1>Block $block</h1></div><div class='questions'><br><p>$name?</p>";
    checkImage($block, $name, "..");
    if ($block == 1 || $block == 3 || $block == 4){
        echo "<textarea>Answerfield</textarea><br><button class='btn btn-dark' type='submit'>Bestätigen</button></div>";
    } elseif ($block == 2) {
        foreach ($currentArray['Answers'] as $key => $value) {
            echo "<button class='btn btn-dark answerButtons' formmethod='post' formaction='playInterface.php' value='$value'>$value</button>";
        }
        echo "</div>";
    }
} else {
    echo "<h1>Hi Player! Das Spiel startet gleich.</h1>";
}

closeSide();

#[ArrayShape(["Block" => "mixed", "Question" => "mixed", "Answers" => "array|null"])]
function openFile($pfad) : array{
    $block = str_replace(CurrentQuestionPath."\currentQuestionB", '', $pfad);
    $block = preg_replace("/[.].+/", '', $block);
    $openedFile = json_decode(file_get_contents($pfad))['0'];
    $questionName = $openedFile -> questionName;
    if ($block == 1 || $block == 3){
        $answerArray = [$openedFile -> answer];
    } elseif ($block == 2) {
        $answerArray = [$openedFile -> answerA, $openedFile -> answerB, $openedFile -> answerC, $openedFile -> answerD];
    } else {
        $answerArray = NULL;
    }
    return ["Block" => $block, "Question" => $questionName, "Answers" => $answerArray];
}