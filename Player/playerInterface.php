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

$nextPageArray = json_decode(file_get_contents("../Bloecke/runningGame/nextPage.json"), true);
$nextPageArray["Player".$player] = "false";
file_put_contents("../Bloecke/runningGame/nextPage.json", json_encode($nextPageArray));

openSide("..");

if ($_POST['check'] == "true"){
    $_SESSION['answer'] = $_POST['answer'];
    $AnswerDir = $root."/Player/AnswerPlayer".$player.".json"; // check if fixed
    $jArray = ["Answer" => $_POST['answer']];
    file_put_contents($AnswerDir, json_encode($jArray));
    waitPointsNextPage("..", $player);
    echo "<div class='questionsBlock'>Warte auf den Quizmaster!</div>";
    $_POST['check'] = "false";
} else {

//Show Questions
    if (!$arrayIsEmpty) {
        $currentArray = openFile($jsonPath);
        $name = $currentArray['Question'];
        $block = $currentArray['Block'];
        blockedNextPagePoints("..", $player, $block);
        $currentNumber = $currentArray['Number'];
        $_SESSION['block'] = $block;
        echo "<div class='questionsBlock'><h1>Block $block: Frage $currentNumber</h1>
          </div>
          <div class='questions'><br><div id='blockedIndicator'></div>
          <p>$name</p><form><p id='formended'></p>";
        checkImage($block, $name, "..");
        echo "<br>";
        echo "<input type='hidden' name='player' value='1'>";
        $AnswerDir = $root."/Player/AnswerPlayer".$player.".json"; // check if fixed
        $jArray = [];
        file_put_contents($AnswerDir, json_encode($jArray));

        if ($block == 1) { // Schätzfragen
            $rightAnswer = $currentArray['Answers']['0'];
            $_SESSION['rightAnswer'] = $currentArray['Answers']['0'];
            echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
            echo "<input name='answer' type='number' required> 
              <br><br> 
              <button id='answerButton' class='btn btn-dark' type='submit' formmethod='post' formaction='playerInterface.php' name='check' value='true'>
              Bestätigen</button>
              </form></div>";

        } elseif ($block == 2) { //Buzzerfragen
            //blockfunktion
            $rightAnswer = $currentArray['Answers']['0'];
            $_SESSION['rightAnswer'] = $currentArray['Answers']['0'];
            echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
            shuffle($currentArray['Answers']);
            $_SESSION['shuffledArray'] = $currentArray['Answers'];
            echo "<input type='hidden' name='check' value='true'>";
            foreach ($currentArray['Answers'] as $key => $value) {
                echo "<button id='answerButton' class='btn btn-dark answerButtons' type='submit' formmethod='post' 
                      formaction='playerInterface.php' name='answer' value='$value'>$value</button>";
            }
            echo "</form> </div>";
            selfblocker();

        } elseif ($block == 3) { // Allgemeinfragen
            $rightAnswer = $currentArray['Answers']['0'];
            $_SESSION['rightAnswer'] = $currentArray['Answers']['0'];
            echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
            echo "<textarea name='answer' placeholder='Hier die Antwort eingeben' required></textarea> 
              <br> 
              <button id='answerButton' class='btn btn-dark' type='submit' formmethod='post' formaction='playerInterface.php' name='check' value='true'>Bestätigen</button>
              </form></div>";

        } elseif ($block == 4) { // Aufzählfragen -- still needs work
            block4Ajax(".."); // weiterleiten zu neuen Seite?
            /**
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
             */
            echo "</div>";
        }
    } // Wait for Questions
    elseif ($running == true) {
        nextPagePointsAJAX("..", $player);
        echo "<h1>Der Spielleiter stellt die nächste Frage vor</h1>";
    } // Wait for Game
    else {
        continueNextPageAJAX("..", $player);
        echo "<h1>Hi Player ".$player."! Das Spiel startet gleich.</h1>";
    }
}

closeSide();

/**
 * @param mixed $player
 * OLD
 */
/**
function selfblocker(mixed $player)
{
    $selfblockerArray = json_decode(file_get_contents("../Bloecke/runningGame/blocked.json"), true);
    $selfblockerArray["Player".$player] = "true";
    file_put_contents("../Bloecke/runningGame/blocked.json", json_encode($selfblockerArray));
}
*/

/**
 * TEST
 */
function selfblocker()
{
    $selfblockerArray = ["Player1" => "true", "Player2" => "true"];
    file_put_contents("../Bloecke/runningGame/blocked.json", json_encode($selfblockerArray));
}