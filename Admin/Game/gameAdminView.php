<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
debugging("nein");

openSide("..\..");
addQuicklinks("Admin", "..");
echo "<div><h1>Spielübersicht</h1>";
checkIfGameRunning();

closeSide();

function checkIfGameRunning(){
    $root = $_SERVER['DOCUMENT_ROOT'];
    $dir = $root ."/Quizmaster/currentBlock/";
    $fileArray = glob($dir."*.json");
    $playedGames = json_decode(file_get_contents($root."/Bloecke/runningGame/currentGame.json"));
    $indexCounter = 1;

    if(isset($fileArray['1'])){
        echo "Zu viele Dateien im Ordner. Kann nicht feststellen welches Spiel läuft!";
    } elseif (isset($fileArray['0'])) {
        $file = $fileArray['0'];
        if (file_exists($file)) {
            $name = str_replace($dir . "questionsB", '', $file);
            $name = str_replace(".json", '', $name);
            echo "Aktuell wird Block $name gespielt<br><br>";
            foreach ($playedGames as $game){
                if ($game !== $name){
                    echo "Das $indexCounter. Spiel war Block $game.<br>";
                    $indexCounter ++;
                }
            }
            include "../../checkForPoints.php";
            overwatchGame();
        }
    } else {
        echo "Es läuft kein Spiel<br><br>";
        startGame();
    }
}

function startGame(){
    if (isset($_POST['startGame'])) {
        chooseBlockType("./startGame.php", "../adminInterface.php", "Welcher Block wird zuerst gespielt?");
        echo "</div>";
    } else {
        echo "<form>
              <button class='btn btn-dark' formmethod='post' type='submit' name='startGame' value='start' formaction='gameAdminView.php'>Starte das Spiel</button>
              <button class='btn btn-dark' formmethod='post' type='submit' formaction='../adminInterface.php'>Zurück</button>
              </form></div>
               ";
    }
}

function overwatchGame(){
    echo "
         <form>
         <button class='btn btn-dark' formmethod='post' type='submit' name='bearbeiten' value='true' formaction='editGame.php'>Bearbeite das laufende Spiel</button>
         <button class='btn btn-dark' formmethod='post' type='submit' name='end' value='true' formaction='editGame.php'>Beende das laufende Spiel</button>
         <button class='btn btn-dark' formmethod='post' type='submit' formaction='../adminInterface.php'>Zurück</button>
         </form></div>
    ";
}
