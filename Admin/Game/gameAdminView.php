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
    $dir = $root ."/Quizmaster/currentBlock/questionsCurrent.json";
    $fileArray = json_decode(file_get_contents($dir), true);

    if (empty($fileArray)) {
        echo "Es läuft kein Spiel<br><br>";
        startGame();
    } else {
        allInclusiveAJAX("../..", "true");
        overwatchGame();
        echo "<br>";
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
