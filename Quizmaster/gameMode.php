<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
define("ResultPath", "$root" . "/Quizmaster/Ergebnisse/results.json");
define("QuestionPath", "$root" . "\Quizmaster\currentBlock");

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
}else{
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

    //echo json_decode(file_get_contents($file),true);
    //$array=file_get_contents($file);
    //var_dump(json_decode($array));
    //makeChoices($array);
    // JSON 1 Auslesen und die Fragen Aufteilen
    // erste Frage in neue JSON 2 packen
    // erste Frage aus JSON 1 löschen
}

function makeChoices($array)
{
    foreach ($array as $question) {
        $counter = 0;
        echo "<input type='radio' id='question' name='$counter' value='on'>
         <label for='html'>$question</label><br>";
    }
}

function checkQuestions(){
    $block = 1;
    while ($block<5){
        $file = QuestionPath . "\questionsB". "$block".".json";
        if(file_exists($file)){
            echo "ok";
            break;
        }
        $block++;
    }

    showQuestions($block);
    //nach Fragen im Ordner suchen
    //Fragen ausgeben
}

function showQuestions($block)
{
    $questionArray = [];
    array_push($questionArray, openJSON($block));
    foreach ($questionArray as $key => $value) {
        $newKey = $key + 1;
        echo "<br>Block $newKey: ";
        foreach ($value as $questionKey => $item) {
            $questionIndex = $questionKey+1;
            $questionName = $item['questionName'];
            if (isset($item['answer'])) {
                $questionAnswers = $item['answer'];
                echo "<br>==> Frage $questionIndex: $questionName => $questionAnswers";
            } elseif (isset($item['answerA'])){
                $questionAnswerA = $item['answerA'];
                $questionAnswerB = $item['answerB'];
                $questionAnswerC = $item['answerC'];
                $questionAnswerD = $item['answerD'];
                echo "<br>==> Frage $questionIndex: $questionName => A: $questionAnswerA,
                      B: $questionAnswerB, C: $questionAnswerC, D: $questionAnswerD";
            }else {
                echo "<br>==> Frage $questionIndex: $questionName";
            }
            checkImage($newKey, $questionName);
        }
    }
}

           /* if ($block == 1) {
                //Schätzfragen
            } elseif ($block == 2) {
                echo $questionName;
            } elseif ($block == 3) {

            } elseif ($block == 4) {

            }
        }
}*/

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
