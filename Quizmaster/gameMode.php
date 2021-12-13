<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
define("ResultPath", "$root" . "/Quizmaster/Ergebnisse/results.json");
define("QuestionPath", "$root" . "/Quizmaster/currentBlock");
define("PlayerPath", "$root" . "/Player/Questions");
define("Root", $root);
debugging("no");

openSide("..");
addQuicklinks("Quizmaster");
pointsAjax("..");

if ($_POST['aufloesen']=="true"){
    $points = logPoints();
    file_put_contents("../Bloecke/runningGame/continue.json", json_encode("true"));
    whoWon($points);
    checkQuestions();
    sendGameOptions();
}
elseif($_POST['senden']=="on"){
    checkQuestions();
    file_put_contents("../Bloecke/runningGame/continue.json", json_encode("false"));
    sendQuestion(splitQuestions(), checkBlock());
    runGameOptions();
}
else{
    checkQuestions();
    sendGameOptions();
}
closeSide();

//-------------------------------------------------

function sendQuestion($array, $block){
    $addBlock = array("block"=>$block);
    $json = json_encode(array_merge($array, $addBlock));
    //Blocknummer mit in den Namen der question json speichern, Nummer der Frage mit übergeben also Index + 1
    $bytes = file_put_contents(PlayerPath."/currentQuestion.json", "[".$json."]");
    if($bytes){
        echo"Fragen gesendet<br>";
    }
}

function splitQuestions() : array
{
    $sendArray = Null;
    $block = "Block".checkBlock();
    $numberDir = Root."/Bloecke/runningGame/questionNumber.json";
    $questionNumber = json_decode(file_get_contents($numberDir));
    $currentNumber = $questionNumber -> $block;
    $currentNumber++;
    if(checkBlock()==null) {
        function_alert("Keine Fragen gefunden");
    }
    else {
        $questionArray = [];
        array_push($questionArray, openJSON(checkBlock(),true));

        if(empty($questionArray)) {
            function_alert("Es wurden alle Fragen gespielt");
        }
        else {
            foreach ($questionArray as $value) {
                foreach ($value as $questionKey => $item) {
                    if ($questionKey == 0) {
                        if (isset($item['answer'])) {
                            $sendArray = array(
                                "questionName" => $item['questionName'],
                                "answer" => $item['answer']);
                        } elseif (isset($item['answerA'])) {
                            $sendArray = array(
                                "questionName" => $item['questionName'],
                                "answerA" => $item['answerA'],
                                "answerB" => $item['answerB'],
                                "answerC" => $item['answerC'],
                                "answerD" => $item['answerD']);
                        } elseif (isset($item['mostOftenAnswer'])){
                            $sendArray = array(
                                "questionName" => $item['questionName'],
                                //am häufigsten
                                "questionMostOften" =>  $item['mostOftenAnswer'],
                                "questionMostOftenPer" => $item['mostOftenAnswerPercentage'],
                                //am 2. häufigsten
                                "questionOften" => $item['oftenAnswer'],
                                "questionOftenPer" => $item['oftenAnswerPercentage'],
                                // am 3. häufigsten
                                "questionMiddle" => $item['middleAnswer'],
                                "questionMiddlePer" => $item['middleAnswerPercentage'],
                                // am 2. wenigsten
                                "questionLeast" => $item['leastAnswer'],
                                "questionLeastPer" => $item['leastAnswerPercentage'],
                                // am wenigsten
                                "questionLeastOften" => $item['leastOftenAnswer'],
                                "questionLeastOftenPer" => $item['leastOftenAnswerPercentage']
                            );
                        }
                    }
                }
                unset($value[0]);
                $keepArray = array_values($value);
                $dir = QuestionPath . "/questionsB" . checkBlock() . ".json";
                file_put_contents($dir, json_encode($keepArray));
                $questionNumber -> $block = $currentNumber;
                file_put_contents($numberDir, json_encode($questionNumber));
            }
        }
    }
    return $sendArray;
}

function checkBlock(): ?int{
    $block = 1;
    while ($block<5){
        $file = QuestionPath . "/questionsB". "$block".".json";
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

function showQuestions($block) // int oder null
{
    $questionArray = [];
    array_push($questionArray, openQuestion($block));

    foreach ($questionArray as $key => $value) {
        $newKey = $key + 1;
        echo "<br><div class='questionsBlock'>Block $block:</div><div class='questions'>";
        foreach ($value as $questionKey => $item) {
            if ($questionKey == 0) {
                echo "<div class='currentQuestion'>";
                loadQuestions($questionKey, $item, $newKey, "..");  // functions.php
                echo "</div>";
            }else {
                loadQuestions($questionKey, $item, $newKey, "..");  // functions.php
            }
        }
        echo "</div>";
    }
}

function openQuestion($block): array
{
    $fileName = QuestionPath."/"."questionsB"."$block".".json";
    if (file_exists($fileName)) {
        return json_decode(file_get_contents($fileName), true);
    } else {
        return [];
    }

}

function sendGameOptions(){
    if (file_exists(glob("./currentBlock/questionsB*.json")['0'])) {
        $array = json_decode(file_get_contents(glob("./currentBlock/questionsB*.json")['0']), true);
    } else {
        $array = [];
    }
    if (empty($array)){
        echo "Die Letzte Frage wurde gespielt <br>
        <form>
              <button class='btn btn-dark' formmethod='post' type='submit' formaction='gameMode.php'>Nächsten Block wählen</button>
              </form>
        ";
    } else {
        echo "
        <form>
              <button class='btn btn-dark' formmethod='post' type='submit' name='senden' value='on' formaction='gameMode.php'>Frage senden</button>
              </form>
    ";
    }
}

function runGameOptions(){
    if (file_exists(glob("./currentBlock/questionsB*.json")['0'])) {
        $array = json_decode(file_get_contents(glob("./currentBlock/questionsB*.json")['0']), true);
    } else {
        $array = [];
    }
    if (empty($array)){
        echo "Die Letzte Frage wurde gespielt<br>
        <form>
              <button class='btn btn-dark' formmethod='post' type='submit' name='aufloesen' value='true' formaction='gameMode.php'>Auflösen</button>
              </form>
        ";
    } else {
        echo "Das Spiel läuft !<br>
        <form>
              <button class='btn btn-dark' formmethod='post' type='submit' name='aufloesen' value='true' formaction='gameMode.php'>Auflösen</button>
              </form>
        ";
    }
}

function logPoints() : array{
   return json_decode(file_get_contents("../Player/playerPoints.json"), true);
}

/**
 * @param $beforePoints
 * TODO
 */
function whoWon($beforePoints){ //Verzögern, wird zu früh ausgeführt - SWITCH TO AJAX
    $currentPoints = logPoints();
    if ($beforePoints['Player1'] == $currentPoints['Player1']){
        if ($beforePoints['Player2'] == $currentPoints['Player2']){
            echo "<div class='winnerNone'><h3>KEINER GEWINNT</h3></div>";
        } else {
            echo "<div class='winnerPlayer'><h3>PLAYER 2 WINS</h3></div>";
        }
    } else {
        if ($beforePoints['Player2'] !== $currentPoints['Player2']){
            echo "<div class='winnerBoth'><h3>BEIDE GEWINNEN</h3></div>";
        } else {
            echo "<div class='winnerPlayer'><h3>PLAYER 1 WINS</h3></div>";
        }
    }
}