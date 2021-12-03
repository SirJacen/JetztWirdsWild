<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
define("ResultPath", "$root" . "\Quizmaster\Ergebnisse/results.json");
define("QuestionPath", "$root" . "\Quizmaster\currentBlock");
define("PlayerPath", "$root" . "\Player\Questions");
debugging("nein");

openSide("..");
addQuicklinks("Quizmaster");
pointsAjax("..");

if($_POST['result']=="on"){
    if(checkResult() != false) {
        winnerCalc(ResultPath);
        noGameOptions();
    }else {
        runGameOptions();
    }
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
    $addBlock = array("block"=>$block);
    $json = json_encode(array_merge($array, $addBlock));
    //Blocknummer mit in den Namen der question json speichern, Nummer der Frage mit übergeben also Index + 1
    $bytes = file_put_contents(PlayerPath."\currentQuestionsB$block.json", $json);
    if($bytes){
        echo"Fragen gesendet<br>";
    }
}

function splitQuestions() : array
{
    if(checkBlock()==null){
        function_alert("Keine Fragen gefunden");
    }else {
        $questionArray = [];
        array_push($questionArray, openJSON(checkBlock()));
        if(empty($questionArray)){
            function_alert("Es wurden alle Fragen gespielt");
        }else {
            foreach ($questionArray as $key => $value) {
                foreach ($value as $questionKey => $item) {
                    if ($questionKey == 0) {
                        if (isset($item['answer'])) {
                            $sendArray = array(
                                "questionName" => $item['questionName'],
                                "answer" => $item['answer']);
                        } elseif (isset($item["answerA"])) {
                            $sendArray = array(
                                "questionName" => $item['questionName'],
                                "answerA" => $item['answerA'],
                                "answerB" => $item['answerB'],
                                "answerC" => $item['answerC'],
                                "answerD" => $item['answerD']);
                        }
                    }
                }
                unset($value[0]);
                $keepArray = array_values($value);
                $dir = QuestionPath . "\questionsB" . checkBlock() . ".json";
                file_put_contents($dir, json_encode($keepArray));
            }
            }
        }

    return $sendArray;
}


function makeChoices($array)
{
    foreach ($array as $question) {
        $counter = 0;
        echo "<input type='radio' id='question' name='$counter' value='on'>
         <label for='html'>$question</label><br>";
    }
}

function checkBlock(): ?int{
    $block = 1;
    while ($block<4){
        $file = QuestionPath . "\questionsB". "$block".".json";
        if(file_exists($file)){
            return $block;
        }
        $block++;
    }
    return null;
}

function checkQuestions(){
    showQuestions(checkBlock());
}

function showQuestions($block)
{
    $questionArray = [];
    array_push($questionArray, openQuestion($block));
    foreach ($questionArray as $key => $value) {
        $newKey = $key + 1;
        echo "<br><div class='questionsBlock'>Block $block: </div><div class='questions'>";
        foreach ($value as $questionKey => $item) {
            loadQuestions($questionKey, $item, $newKey, "..");
        }
        echo "</div>";
    }
}

function openQuestion($block): mixed
{
    $root = $_SERVER['DOCUMENT_ROOT'];
    $dir = "$root"."/Bloecke/"."$block";

    $fileName = QuestionPath."/"."questionsB"."$block".".json";
    return json_decode(file_get_contents($fileName),true);
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

