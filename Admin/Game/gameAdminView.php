<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
debugging("no");

openSide("..\..");
addQuicklinks("Admin", "..");
echo "<div class='questionsBlock'>
      <h1>Spielübersicht</h1>
      </div>
      <div class='questions'>";

checkIfGameRunning();

closeSide();

function checkIfGameRunning(){
    $root = $_SERVER['DOCUMENT_ROOT'];
    $dir = $root ."/Quizmaster/currentBlock/";
    $fileArray = glob($dir."*.json");


    if(isset($fileArray['1'])){
        echo "Zu viele Dateien im Ordner. Kann nicht feststellen welches Spiel läuft!";
    }
    elseif (isset($fileArray['0'])) {
        $file = $fileArray['0'];
        if (file_exists($file)) {
            showCurrentBlockandQuestion("../..");
            pointsAjax("../..");
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
    }
    else {
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
         <button class='btn btn-dark' formmethod='post' type='submit' name='bearbeiten' value='true' formaction='editGame.php'>Wähle den nächsten Block aus</button>
         <button class='btn btn-dark' formmethod='post' type='submit' name='end' value='true' formaction='editGame.php'>Beende das laufende Spiel</button>
         <button class='btn btn-dark' formmethod='post' type='submit' formaction='../adminInterface.php'>Zurück</button>
         </form></div>
    ";
}
