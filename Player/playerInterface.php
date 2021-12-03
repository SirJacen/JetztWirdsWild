<?php

use JetBrains\PhpStorm\ArrayShape;

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
session_start();
define("CurrentQuestionPath", "$root" . "\Player\Questions");
define("Root", $root);
debugging("nein");

$pfadArray = glob(CurrentQuestionPath."\currentQuestionsB*.json");

$running = isGameRunning();
$player = $_SESSION['player'];

openSide("..");

if ($_SESSION['played'] == true){
    pointsAjax("..");
    $yourAnswer = $_SESSION['answer'];
    $currentArray = openFile($pfadArray['0']);
    $name = $currentArray['Question'];
    $block = $currentArray['Block']; //Fragen zählen
    if ($_SESSION['correct'] == true) {
        echo "<div class='questionsBlock'><h1>Du hast die Frage richtig beantwortet</h1></div><div class='questions'><br><p>$name?</p>";
        checkImage($block, $name, "..");
        if ($block == 1 || $block == 3 || $block == 4) {
            echo "<div class='correctAnswer'><p>Deine Antwort: $yourAnswer ist richtg!</p></div></div>";
        } elseif ($block == 2) {
            foreach ($currentArray['Answers'] as $key => $value) {
                if($value == $yourAnswer) {
                    echo "<button class='btn btn-success answerButtons' type='submit' formmethod='post' formaction='checkWinner.php' name='answer' value='$value'>$value</button>";
                } else {
                    echo "<button class='btn btn-dark answerButtons' type='submit' formmethod='post' formaction='checkWinner.php' name='answer' value='$value'>$value</button>";
                }
            }
            echo "</div>";
        }
    } else {
        echo "<div class='questionsBlock'><h1>Du hast die Frage leider Falsch beantwortet!</h1></div><div class='questions'><br><p>$name?</p>";
        checkImage($block, $name, "..");
        $rightAnswer = $currentArray['Answers']['0'];
        if ($block == 1 || $block == 3 || $block == 4) {
            echo "<div class='wrongAnswer'><p>Deine Antwort: $yourAnswer ist faslch!</p></div></div>";
        } elseif ($block == 2) {
            foreach ($currentArray['Answers'] as $key => $value) {
                if($value == $rightAnswer) {
                    echo "<button class='btn btn-success answerButtons' type='submit' formmethod='post' formaction='checkWinner.php' name='answer' value='$value'>$value</button>";
                } elseif ($value == $yourAnswer){
                    echo "<button class='btn btn-danger answerButtons' type='submit' formmethod='post' formaction='checkWinner.php' name='answer' value='$value'>$value</button>";
                } else {
                    echo "<button class='btn btn-dark answerButtons' type='submit' formmethod='post' formaction='checkWinner.php' name='answer' value='$value'>$value</button>";
                }
            }
            echo "</div>";
        }
    }

} elseif($pfadArray['0']){
    pointsAjax("..");
    $currentArray = openFile($pfadArray['0']);
    $name = $currentArray['Question'];
    $block = $currentArray['Block'];
    $currentNumber = $currentArray['Number'];
    echo "<div class='questionsBlock'><h1>Block $block: Frage $currentNumber</h1></div><div class='questions'><br><p>$name?</p><form>";
    checkImage($block, $name, "..");
    echo "<input type='hidden' name='player' value='1'>";
    $rightAnswer = $currentArray['Answers']['0'];
    if ($block == 1 || $block == 3 || $block == 4){
        echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
        echo "<textarea name='answer' placeholder='Hier die Antwort eingeben'></textarea><br><button class='btn btn-dark' type='submit' formmethod='post' formaction='checkWinner.php'>Bestätigen</button></div>";
    } elseif ($block == 2) {
        echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
        foreach ($currentArray['Answers'] as $key => $value) {
            echo "<button class='btn btn-dark answerButtons' type='submit' formmethod='post' formaction='checkWinner.php' name='answer' value='$value'>$value</button>";
        }
        echo "</form></div>";
    }
} elseif($running == true) {
    pointsAjax("..");
    echo "<h1>Der Spielleiter stellt die nächste Frage vor</h1>";
}
else {
    echo "<h1>Hi Player! Das Spiel startet gleich.</h1>";
}

closeSide();

#[ArrayShape(["Block" => "mixed", "Question" => "mixed", "Answers" => "array|null", "Number" => "mixed"])]
function openFile($pfad) : array{
    $block = str_replace(CurrentQuestionPath."\currentQuestionsB", '', $pfad);
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
    $questionNumber = checkQuestionNumber($block);
    return ["Block" => $block, "Question" => $questionName, "Answers" => $answerArray, "Number" => $questionNumber];
}
