<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
define("ResultPath", "$root" . "\Quizmaster\Ergebnisse/results.json");
define("QuestionPath", "$root" . "\Quizmaster\currentBlock");
define("PlayerPath", "$root" . "\Player\Questions");
debugging("nein");

openSide("..");
addQuicklinks("Quizmaster");


if($_POST['result']=="on"){
    if(checkResult()==false){
        runGameOptions();
    }
    winnerCalc(ResultPath);
    //Stats aufrufen ??
    noGameOptions();
}elseif($_POST['senden']=="on"){
    checkQuestions();
    sendQuestions(splitQuestions(), checkBlock());
    runGameOptions();
}elseif($_POST['check']=="on"){
    checkQuestions();
    sendGameOptions();
}else{
    noGameOptions();
}
closeSide();

//-------------------------------------------------

function checkResult() :bool{
    $ret = false;
    if(file_exists(ResultPath)){
        $ret = true;
    }
    return $ret;
}

function sendQuestions($array, $block){
    $json = json_encode($array);
    //Blocknummer mit in den Namen der question json speichern, Nummer der Frage mit übergeben also Index + 1
    $bytes = file_put_contents(PlayerPath."\currentQuestionsB$block.json", $json);
    if($bytes){
        echo"Fragen gesendet";
    }
}

function splitQuestions() : array
{
    // JSON 1 Auslesen und die Fragen Aufteilen
    // erste Frage in neue JSON 2 packen
    // erste Frage aus JSON 1 löschen
    $questionArray = [];
    array_push($questionArray, openJSON(checkBlock()));
    foreach($questionArray as $key => $value){
        echo "TO Do";
    }
    return $questionArray;
}

function makeChoices($array)
{
    foreach ($array as $question) {
        $counter = 0;
        echo "<input type='radio' id='question' name='$counter' value='on'>
         <label for='html'>$question</label><br>";
    }
}

function checkBlock(): int{
    $block = 1;
    while ($block<4){
        $file = QuestionPath . "\questionsB". "$block".".json";
        if(file_exists($file)){
            echo "ok";
            return $block;
        }
        $block++;
    }
    return 0;
}

function checkQuestions(){
    showQuestions(checkBlock());
}

function showQuestions($block)
{
    $questionArray = [];
    array_push($questionArray, openJSON($block));
    foreach ($questionArray as $key => $value) {
        $newKey = $key + 1;
        echo "<br><div class='questionsBlock'>Block $block: </div><div class='questions'>";
        foreach ($value as $questionKey => $item) {
            loadQuestions($questionKey, $item, $newKey, "..");
        }
        echo "</div>";
    }
}

function noGameOptions(){
    echo"
        <form>
              <button class='btn btn-dark' formmethod='post' type='submit' name='check' value='on' formaction='gameMode.php'>Fragen checken</button>
              </form>
    ";
}

function sendGameOptions(){
    echo "
        <form>
              <button class='btn btn-dark' formmethod='post' type='submit' name='senden' value='on' formaction='gameMode.php'>Fragen senden</button>
              <button class='btn btn-dark' formmethod='post' type='submit' name='check' value='on' formaction='gameMode.php'>Fragen checken</button>
              </form>
    ";
}

function runGameOptions(){
    echo "Das Spiel läuft !<br>
        <form>
              <button class='btn btn-dark' formmethod='post' type='submit' name='result' value='on' formaction='gameMode.php'>Ergebnisse</button>
              </form>
    ";
}

