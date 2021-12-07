<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
session_start();
define("CurrentQuestionPath", "$root" . "/Player/Questions");
define("Root", $root);
debugging("no");

openSide("..");

$jsonPath = CurrentQuestionPath."/currentQuestion.json";
$currentArray = openFile($jsonPath);

pointsAjax("..");
$yourAnswer = $_SESSION['answer'];

$name = $currentArray['Question'];
$block = $currentArray['Block']; // Fragen zählen

// FRAGE RICHTIG
if ($_SESSION['correct'] == "true") {
    echo "<div class='questionsBlock'>
                <h1>Du hast die Frage richtig beantwortet</h1>
              </div><div class='questions'>
              <br><p>$name?</p>";
    checkImage($block, $name, "..");
    if ($block == 1 || $block == 3 || $block == 4) {
        echo "<div class='correctAnswer'><p>Deine Antwort: $yourAnswer ist richtig!</p></div></div>";
    }
    elseif ($block == 2) {
        foreach ($currentArray['Answers'] as $key => $value) {
            if($value == $yourAnswer) {
                echo "<button class='btn btn-success answerButtons' type='submit' formmethod='post' formaction='checkWinner.php' name='answer' value='$value'>$value</button>";
            }
            else {
                echo "<button class='btn btn-dark answerButtons' type='submit' formmethod='post' formaction='checkWinner.php' name='answer' value='$value'>$value</button>";
            }
        }
        echo "</div>";
    }
    $_SESSION['played'] = false; // DIRTY FIX
    // $_SESSION['correct'] = false;
}
// FRAGE FALSCH
else {
    echo "<div class='questionsBlock'><h1>Du hast die Frage leider falsch beantwortet!</h1>
              </div><div class='questions'><br>
              <p>$name?</p>";
    checkImage($block, $name, "..");
    $rightAnswer = $currentArray['Answers']['0'];
    if ($block == 1 || $block == 3 || $block == 4) {
        echo "<div class='wrongAnswer'><p>Deine Antwort: $yourAnswer ist falsch!</p></div></div>";
    }
    elseif ($block == 2) {
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
echo "<form><button class='btn btn-dark' type='submit' formmethod='post' formaction='playerInterface.php'>Nächste Frage</button></form>";

closeSide();
