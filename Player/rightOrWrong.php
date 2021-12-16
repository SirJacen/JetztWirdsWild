<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
session_start();
define("CurrentQuestionPath", "$root" . "/Player/Questions");
define("Root", $root);
debugging("no");

openSide("..");

//Debugging
/**
$array =json_decode(file_get_contents("AnswerPlayer1.json"), true);
$array2 =json_decode(file_get_contents("AnswerPlayer2.json"), true);
echo "1: ";
print_r($array);
echo "2: ";
print_r($array2);
 */
//--------------------------------------------

$jsonPath = CurrentQuestionPath."/currentQuestion.json";
$currentArray = openFile($jsonPath);

nextPagePointsAJAX("..", $_SESSION['player']);
echo "<br><br>";
$yourAnswer = $_SESSION['answer'];

$name = $currentArray['Question'];
$block = $currentArray['Block']; // Fragen zählen

// FRAGE RICHTIG
if ($_SESSION['correct'] == "true") {
    echo "<div class='questionsBlock'>
                <h1>Du hast die Frage richtig beantwortet!</h1>
              </div><div class='questions'>
              <br><p>$name</p>";
    checkImage($block, $name, "..");
    if ($block == 1 || $block == 3 || $block == 4) {
        echo "<div class='correctAnswer'><p>Deine Antwort: $yourAnswer ist richtig!</p></div></div>";
    }
    elseif ($block == 2) {
        $currentArray = $_SESSION['shuffledArray'];
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
}
// FRAGE FALSCH
else {
    echo "<div class='questionsBlock'><h1>Du hast die Frage leider falsch beantwortet!</h1>
              </div><div class='questions'><br>
              <p>$name</p>";
    checkImage($block, $name, "..");
    $rightAnswer = $currentArray['Answers']['0'];
    if ($block == 1 || $block == 3 || $block == 4) {
        echo "<div class='wrongAnswer'><p>Deine Antwort: $yourAnswer ist falsch!</p></div></div>";
    }
    elseif ($block == 2) {
        $currentArray = $_SESSION['shuffledArray'];
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

closeSide();
