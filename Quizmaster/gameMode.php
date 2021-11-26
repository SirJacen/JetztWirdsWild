<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
define("ResultPath", $root . "/Quimaster/Ergebnisse/results.json");
define("QuestionPath", $root . "/Quimaster/currentBlock/");

openSide();
debugging("ja");

if($_POST['result']=="on"){
    if(checkResult()==false){
        runGameOptions();
    }
    winnerCalc(ResultPath);
    //Stats aufrufen ??
    noGameOptions();
}elseif($_POST['send']=="on"){
    checkQuestions();
    sendQuestions(splitQuestions());
    runGameOptions();
}elseif($_POST['check']=="on"){
    checkQuestions();
    sendGameOptions();
}elseif{
    noGameOptions();
}

function checkResult() :bool{
    $ret = false;
    if(file_exists(ResultPath)){
        $ret = true;
    }
    return $ret;
}

function splitQuestions(){
    // JSON 1 Auslesen und die Fragen Aufteilen
    // erste Frage in neue JSON 2 packen
    // erste Frage aus JSON 1 löschen
}

function checkQuestions(): String {
    $ret = "";
    //nach Fragen im Ordner suchen
    //Fragen ausgeben
    return $ret;
}

function sendQuestions($path){

}

function noGameOptions(){
    echo"
        <form>
              <button formmethod='post' type='submit' name='check' value='on' formaction='gameMode.php'>Fragen checken</button>
              </form>
    ";
}

function sendGameOptions(){
    echo "
        <form>
              <button formmethod='post' type='submit' name='senden' value='on' formaction='gameMode.php'>Fragen senden</button>
              <button formmethod='post' type='submit' name='check' value='on' formaction='gameMode.php'>Fragen checken</button>
              </form>
    ";
}

function runGameOptions(){
    echo "Das Spiel läuft !<br>
        <form>
              <button formmethod='post' type='submit' name='result' value='on' formaction='gameMode.php'>Ergebnisse</button>
              </form>
    ";
}
closeSide();
