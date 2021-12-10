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

if ($_POST['check'] == "true"){
    $_SESSION['answer'] = $_POST['answer'];
    waitWithPoints("..");
    echo "<div class='questionsBlock'>Warte auf den Quizmaster!</div>";
    $_POST['check'] = "false";
} else {

//Show Questions
    if (!$arrayIsEmpty) {
        blockedPoints("..", $player);
        $currentArray = openFile($jsonPath);
        $name = $currentArray['Question'];
        $block = $currentArray['Block'];
        $currentNumber = $currentArray['Number'];
        echo "<div class='questionsBlock'><h1>Block $block: Frage $currentNumber</h1>
          </div>
          <div class='questions'><br><div id='blockedIndicator'></div>
          <p>$name</p><form><p id='formended'></p>";
        checkImage($block, $name, "..");
        echo "<input type='hidden' name='player' value='1'>";

        if ($block == 1) { // Schätzfragen
            $rightAnswer = $currentArray['Answers']['0'];
            $_SESSION['rightAnswer'] = $currentArray['Answers']['0'];
            echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
            echo "<input name='answer' type='number' required> 
              <br><br> 
              <button id='answerButton' class='btn btn-dark' type='submit' formmethod='post' formaction='playerInterface.php' name='check' value='true'>
              Bestätigen</button>
              </form></div>";
        } elseif ($block == 3) { // Allgemeinfragen
            $rightAnswer = $currentArray['Answers']['0'];
            $_SESSION['rightAnswer'] = $currentArray['Answers']['0'];
            echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
            echo "<textarea name='answer' placeholder='Hier die Antwort eingeben' required></textarea> 
              <br> 
              <button id='answerButton' class='btn btn-dark' type='submit' formmethod='post' formaction='playerInterface.php' name='check' value='true'>Bestätigen</button>
              </form></div>";
        } elseif ($block == 2) { //Buzzerfragen
            $rightAnswer = $currentArray['Answers']['0'];
            $_SESSION['rightAnswer'] = $currentArray['Answers']['0'];
            echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
            shuffle($currentArray['Answers']);
            $_SESSION['shuffledArray'] = $currentArray['Answers'];
            echo "<input type='hidden' name='check' value='true'>";
            foreach ($currentArray['Answers'] as $key => $value) {
                echo "<button id='answerButton' class='btn btn-dark answerButtons' type='submit' formmethod='post' formaction='playerInterface.php' name='answer' value='$value'>$value</button>";
            }
            echo "</form> </div>";
        } elseif ($block == 4) { // Aufzählfragen -- still needs work
            $rightAnswer = $currentArray['Answers'];
            $_SESSION['rightAnswer'] = $currentArray['Answers'];
            foreach ($_SESSION['rightAnswer'] as $key => $value) {
                if ($key == "Percentage") {
                    foreach ($_SESSION['rightAnswer']['Percentage'] as $PerKey => $item) {
                        echo "<input type='hidden' name='perOf$PerKey' value='$item'>";
                    }
                } else {
                    echo "<input type='hidden' name='pos$key' value='$value'>";
                }
            }
            echo "<p class='hiddenText'></p><br><p class='hiddenText'></p><br>
              <p class='hiddenText'></p><br><p class='hiddenText'></p><br>
              <p class='hiddenText'></p><br>";
            echo "<textarea name='answer' placeholder='Hier die Antwort eingeben' required></textarea> 
              <br> 
              <button id='answerButton' class='btn btn-dark' type='submit' formmethod='post' formaction='playerInterface.php' name='check' value='true'>Bestätigen</button>
              </form></div>";
        }
    } // Wait for Questions
    elseif ($running == true) {
        nextPagePointsAJAX("..");
        echo "<h1>Der Spielleiter stellt die nächste Frage vor</h1>";
    } // Wait for Game
    else {
        continueNextPageAJAX("..");
        echo "<h1>Hi Player! Das Spiel startet gleich.</h1>";
    }
}

closeSide();
