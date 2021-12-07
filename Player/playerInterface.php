<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
session_start();
define("CurrentQuestionPath", "$root" . "/Player/Questions");
define("Root", $root);
debugging("no");

$jsonPath = CurrentQuestionPath."/currentQuestion.json";
$array = json_decode(file_get_contents($jsonPath), true);
$arrayIsEmpty = empty($array);

$running = isGameRunning();
$player = $_SESSION['player'];

openSide("..");

if(!$arrayIsEmpty) {
    pointsAjax("..");
    $currentArray = openFile($jsonPath);
    $name = $currentArray['Question'];
    $block = $currentArray['Block'];
    $currentNumber = $currentArray['Number'];
    echo "<div class='questionsBlock'><h1>Block $block: Frage $currentNumber</h1>
          </div>
          <div class='questions'><br>
          <p>$name?</p><form>";
    checkImage($block, $name, "..");
    echo "<input type='hidden' name='player' value='1'>";
    $rightAnswer = $currentArray['Answers']['0'];

    $_SESSION["rightAnswer"] = $currentArray['Answers']['0'];
    if ($block == 1 || $block == 3 || $block == 4){
        echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
        echo "<textarea name='answer' placeholder='Hier die Antwort eingeben'></textarea> 
              <br> 
              <button class='btn btn-dark' type='submit' formmethod='post' formaction='checkWinner.php'>Bestätigen</button>
              </div>";
    }
    elseif ($block == 2) {
        echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
        foreach ($currentArray['Answers'] as $key => $value) {
            echo "<button class='btn btn-dark answerButtons' type='submit' formmethod='post' formaction='checkWinner.php' name='answer' value='$value'>$value</button>";
        }
        echo "</form> </div>";
    }
}

elseif($running == true) {
    pointsAjax("..");
    echo "<h1>Der Spielleiter stellt die nächste Frage vor</h1>";
}

else {
    echo "<h1>Hi Player! Das Spiel startet gleich.</h1>";
}

closeSide();
