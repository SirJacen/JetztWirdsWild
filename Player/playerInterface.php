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

//Show Questions
if(!$arrayIsEmpty) {
    pointsAjax("..");
    $currentArray = openFile($jsonPath);
    $name = $currentArray['Question'];
    $block = $currentArray['Block'];
    $currentNumber = $currentArray['Number'];
    echo "<div class='questionsBlock'><h1>Block $block: Frage $currentNumber</h1>
          </div>
          <div class='questions'><br>
          <p>$name</p><form>";
    checkImage($block, $name, "..");
    echo "<input type='hidden' name='player' value='1'>";

    if ($block == 1 ){ // Schätzfragen
        $rightAnswer = $currentArray['Answers']['0'];
        $_SESSION['rightAnswer'] = $currentArray['Answers']['0'];
        echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
        echo "<input name='answer' type='number' required> 
              <br><br> 
              <button class='btn btn-dark' type='submit' formmethod='post' formaction='checkWinner.php'>Bestätigen</button>
              </div>";
    }
    elseif ($block == 3 ){ // Allgemeinfragen
        $rightAnswer = $currentArray['Answers']['0'];
        $_SESSION['rightAnswer'] = $currentArray['Answers']['0'];
        echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
        echo "<textarea name='answer' placeholder='Hier die Antwort eingeben' required></textarea> 
              <br> 
              <button class='btn btn-dark' type='submit' formmethod='post' formaction='checkWinner.php'>Bestätigen</button>
              </div>";
    }
    elseif ($block == 2) { //Buzzerfragen
        $rightAnswer = $currentArray['Answers']['0'];
        $_SESSION['rightAnswer'] = $currentArray['Answers']['0'];
        echo "<input type='hidden' name='rightAnswer' value='$rightAnswer'>";
        shuffle($currentArray['Answers']);
        $_SESSION['shuffledArray'] = $currentArray['Answers'];
        foreach ($currentArray['Answers'] as $key => $value) {
            echo "<button class='btn btn-dark answerButtons' type='submit' formmethod='post' formaction='checkWinner.php' name='answer' value='$value'>$value</button>";
        }
        echo "</form> </div>";
        print_r($currentArray);
    }
    elseif ($block == 4){ // Aufzählfragen
        $rightAnswer = $currentArray['Answers'];
        $_SESSION['rightAnswer'] = $currentArray['Answers'];
        foreach ($_SESSION['rightAnswer'] as $key => $value){
            if ($key == "Percentage") {
                foreach ($_SESSION['rightAnswer']['Percentage'] as $PerKey => $item){
                    echo "<input type='hidden' name='perOf$PerKey' value='$item'>";
                }
            } else {
                echo "<input type='hidden' name='pos$key' value='$value'>";
            }
        }
        echo "<p class='hiddenText'>Antwort 1</p><br><p class='hiddenText'>Antwort 2</p><br>
              <p class='hiddenText'>Antwort 3</p><br><p class='hiddenText'>Antwort 4</p><br>
              <p class='hiddenText'>Antwort 5</p><br>";
        echo "<textarea name='answer' placeholder='Hier die Antwort eingeben' required></textarea> 
              <br> 
              <button class='btn btn-dark' type='submit' formmethod='post' formaction='checkWinner.php'>Bestätigen</button>
              </div>";
    }
}

// Wait for Questions
elseif($running == true) {
    pointsAjax("..");
    echo "<h1>Der Spielleiter stellt die nächste Frage vor</h1>";
}

// Wait for Game
else {
    echo "<h1>Hi Player! Das Spiel startet gleich.</h1>";
}

closeSide();
