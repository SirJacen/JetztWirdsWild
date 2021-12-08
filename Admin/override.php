<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
debugging("no");
openSide("..");
if (isset($_POST['player1']) && isset($_POST['player2'])){
    $decodedArray = ["Player1" => $_POST['player1'], "Player2" => $_POST['player2']];
    file_put_contents("../Player/playerPoints.json", json_encode($decodedArray));
}
addQuicklinks("Admin");
pointsOverride();
closeSide();

function pointsOverride(){
    $dir = "../Player/";
    $currentPoints= json_decode(file_get_contents($dir."playerPoints.json"),true);
    $player1 = $currentPoints['Player1'];
    $player2 = $currentPoints['Player2'];
    echo "<div class='questionsBlock'>Punkte bearbeiten:</div><div class='questions'><form>
          Punkte Spieler 1: <input name='player1' type='number' value='$player1' max='99' required><br>
          Punkte Spieler 2: <input name='player2' type='number' value='$player2' max='99' required><br><br>
          <button class='btn btn-dark' type='submit' formmethod='post' formaction='override.php'>Bestätigen</button>
          <button class='btn btn-dark' type='reset' formmethod='post' formaction='override.php'>Abbrechen</button>
          </form></div>";
}
