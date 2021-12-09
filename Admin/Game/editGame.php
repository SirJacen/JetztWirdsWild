<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
debugging("no");

if (isset($_POST['end'])) {
    openSide("..\..");
        echo "<div><h1>Sie sind dabei das laufende Spiel zu beenden.<br>
              Sind Sie sicher?</h1>
              <form>
              <button class='btn btn-dark' formmethod='post' name='delete' value='true' formaction='editGame.php'>JA, Spiel beenden!</button>
              <button class='btn btn-dark' formmethod='post' formaction='gameAdminView.php'>NEIN, Zurück!</button>
              </form><div>";
    closeSide();
}

elseif (isset($_POST['delete'])) {
    $_SESSION["test"] = true;
    $playerPoints = ["Player1" => 0, "Player2" => 0];
    $playerDir = $root."/Player/playerPoints.json";
    file_put_contents($playerDir, json_encode($playerPoints));

    file_put_contents($root."/Bloecke/runningGame/currentGame.json", "");
    file_put_contents($root."/Bloecke/runningGame/questionNumber.json","");
    file_put_contents($root."/Player/Questions/currentQuestion.json","");
    foreach (glob($root."/Quizmaster/currentBlock/*.*") as $value){
        unlink($value);
    }
    header("Location:gameAdminView.php");
}

elseif (isset($_POST['bearbeiten'])) {
    openSide("..\..");
    echo "<div><h1>Laufendes Spiel bearbeiten</h1>";
    queueNextBlock();
    echo "</div>";
    closeSide();
}

if (isset($_POST['block'])) {
    $dir = $root."/Bloecke/".$_POST['block']."/";
    $finalDir = $root."/Quizmaster/currentBlock/questionsB".$_POST['block'].".json";
    $file = glob($dir."questionsB".$_POST['block'].".json")['0'];
    unlink(glob($root."/Quizmaster/currentBlock/*.*")['0']);
    copy ($file,$finalDir);
    $tmpArray = [];
    $tmpArray = json_decode(file_get_contents($root."/Bloecke/runningGame/currentGame.json"),true);
    array_push($tmpArray, $_POST['block']);
    file_put_contents($root."/Bloecke/runningGame/currentGame.json",json_encode($tmpArray));
    header("Location: gameAdminView.php");
}

function queueNextBlock(){
    $root = $_SERVER['DOCUMENT_ROOT'];
    $compareArray = ["1","2","3","4"];
    $runningGameDir = $root."/Bloecke/runningGame/currentGame.json";
    $alreadyPlayed = json_decode(file_get_contents($runningGameDir));
    foreach ($alreadyPlayed as $value) {
        foreach ($compareArray as $compare) {
            if ($value == $compare) {
                $key = array_search($compare, $compareArray);
                unset($compareArray[$key]);
            }
        }
    }

    if (empty($compareArray)){
        echo "<h2>Alle Blöcke wurden gespielt! Beende das Spiel, um von vorne zu starten.</h2><br>
              <form>
              <button class='btn btn-dark' formmethod='post' name='end' value='true' formaction='editGame.php'>Spiel beenden</button>
              <button class='btn btn-dark' type='submit' formaction='gameAdminView.php'>Zurück</button>
              </form>";
    }
    else {
        echo "<h2>Nächsten Block wählen</h2>";
        startForm("post", "editGame.php");
        foreach ($compareArray as $index){
            if ($index == "1"){
                $question = "Schätzfragen";
            }elseif ($index == "2"){
                $question = "Buzzerfragen";
            }elseif ($index == "3"){
                $question = "Allgemeinfragen";
            }elseif ($index == "4"){
                $question = "Aufzählgame";
            }else {
                $question = "Fehler! Index ist Falsch!";
            }
            echo "<input type='radio' name='block' value='$index' required> Block $index - $question<br>";
        }
        closeForm("bearbeitet", "Speichern", "gameAdminView.php");
    }

}

