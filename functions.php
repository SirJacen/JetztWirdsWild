<?php

use JetBrains\PhpStorm\ArrayShape;

function openSide($wayToRoot = ".", $noBanner = false){
    echo "<!DOCTYPE html>
          <html lang = 'de'>
          <head>
            <title>Jetzt wird's wild</title>
            <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' integrity='sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh' crossorigin='anonymous'>
            <link rel='stylesheet' href='$wayToRoot\stylesheet.css'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
           </head>
           <body>    
    ";
    if ($noBanner === false){
        addBanner($wayToRoot);
    }

}

function closeSide(){
    echo "</body> </html>";
}

function debugging($onOff){
    $debug = $onOff;
    if ($debug==="ja") {
        error_reporting (-1);
        echo "<pre>POST/GET <br>";
        print_r($_REQUEST);
        echo "<hr>";
        echo "SESSION <br>";
        print_r($_SESSION);
        echo "</pre>";
    } else {
        error_reporting (1);
    }
}

/**
 * Startet ein HTML Formular
 * @return void
 */
function startForm($method, $action)
{
    echo "<form method=\"$method\" action=\"$action\">";
}

/**
 * Erzeugt input Felder mit Label
 * @return void
 */
function writeInputField($label, $name, $value = false, $type="text"){
    if ($value == false) {
        echo "<label for=\"$name\">$label</label> 
          <input type=\"$type\" name=\"$name\" id=\"$name\" placeholder='Hier schreiben' required><br><br>";
    } else {
        echo "<label for=\"$name\">$label</label> 
          <input type=\"$type\" name=\"$name\" id=\"$name\" placeholder='Hier schreiben' value='$value' required><br><br>";
    }
}

/**
 * Schließt das Formular
 * Modifiziert zum simplen Einbau eines Zurück Buttons.
 * Wenn previousPage beim Aufruf nicht mitgegeben wird funktioniert die Form wie vorher,
 * ansonsten geht er zu der in previousPage genannten Seite zurück.
 * @return void
 */
function closeForm($name,$value,$previousPage = "false")
{
    if($previousPage == "false"){
        echo "<button class='btn btn-dark' type=\"submit\" name=\"$name\" value=\"$value\">$value</button>
              <button class='btn btn-dark' class='btn btn-dark' type=\"reset\" value=\"Abbrechen\">Abbrechen</button
              </form>";
    } else {
        echo "<button class='btn btn-dark' type=\"submit\" name=\"$name\" value=\"$value\">$value</button>
              <button class='btn btn-dark' type=\"reset\" value=\"Abbrechen\">Abbrechen</button>";
        returnTo($previousPage);
        echo  "</form>";
    }
}

/**
 * Erstellt eine Allert Box
 *@return void
 */
function function_alert($message){
    echo "<script>alert('$message');</script>";
}

/**
 * Erstellt einen Zurück Button
 * @return void
 */
function returnTo($previousPage, $form = false){
    if ($form == false ) {
        echo " <button class='btn btn-dark' type='submit' formaction='$previousPage' formnovalidate>Zurück</button>
        ";
    } else {
        echo " <form><button class='btn btn-dark' type='submit' formaction='$previousPage' formnovalidate>Zurück</button></form>
        ";
    }
}

/**
 * Erstellt eine abfrage zur Auswahl des Blocks
 * @return void
 */
function chooseBlockType($postTo, $previousPage = false, $customLabel = false) {
    echo "<div class='blockSelection'>";
    startForm("post","$postTo");

    if ($customLabel == false) {
        echo "<label>Für welchen Block ist die Frage?:<br>";
    } else {
        echo "<label>$customLabel</label><br>";
    }

    echo"<input type=\"radio\" name=\"block\" value=\"1\" required> Block 1 - Schätzfragen<br>"; // eine Antwort
    echo"<input type=\"radio\" name=\"block\" value=\"2\" required> Block 2 - Buzzerfragen<br> "; //
    echo"<input type=\"radio\" name=\"block\" value=\"3\" required> Block 3 - Allgemeinfragen<br>"; //
    echo"<input type=\"radio\" name=\"block\" value=\"4\" required> Block 4 - Aufzählgame<br>"; // chat

    closeForm("questionType","Weiter", "$previousPage");
    echo "</div>";
}

/**
 * Liest eine JSON Datei und gibt das Array zurück
 * @param $block
 * @param bool $quizmaster
 * @return mixed
 */
function openJSON($block, bool $quizmaster = false): array
{
    $root = $_SERVER['DOCUMENT_ROOT'];
    if ($quizmaster == false) {
        $dir = "$root" . "/Bloecke/" . "$block";
        $fileName = "$dir" . "/" . "questionsB" . "$block" . ".json";
    } else {
        $fileName  = $root."/Quizmaster/currentBlock/questionsCurrent.json";
    }

    return json_decode(file_get_contents($fileName), true);
}

function json($frage, $block, $overwrite = false)
{
    $root = $_SERVER['DOCUMENT_ROOT'];
    $dir = "$root" . "/Bloecke/" . "$block";

    $fileName = "$dir" . "/" . "questionsB" . "$block" . ".json";

    if ($overwrite == false) {
        if (file_exists($fileName)) {
            $jsonObject = json_decode(file_get_contents($fileName), true);
            array_push($jsonObject, $frage);
            $jsonString = json_encode($jsonObject);
        } else {
            $savingArray = [];
            array_push($savingArray, $frage);
            $jsonString = json_encode($savingArray);
        }
        file_put_contents($fileName, $jsonString);
    } elseif($overwrite == true){
        $jsonString = json_encode($frage);
        file_put_contents($fileName, $jsonString );
    } else {
        echo "Falsche Angaben!";
    }
}

function questionSchaetzAllgemein($array = false, $key = false){
    if ($array !== false && $key !== false) {
        $currentQuestion = $array[$key];
        $frage = $currentQuestion['questionName'];
        $antwort = $currentQuestion['answer'];
    } else {
        $frage = false;
        $antwort = false;
    }
    writeInputField("Wie lautet die Frage?","questionName", $frage);
    writeInputField("Wie lautet die Antwort?","answer", $antwort);
}

function questionBuzzerfrage($array = false, $key = false){
    if ($array !== false && $key !== false) {
        $currentQuestion = $array[$key];
        $frage = $currentQuestion['questionName'];
        $antwortA = $currentQuestion['answerA'];
        $antwortB = $currentQuestion['answerB'];
        $antwortC = $currentQuestion['answerC'];
        $antwortD = $currentQuestion['answerD'];
    } else {
        $frage = false;
        $antwortA = false;
        $antwortB = false;
        $antwortC = false;
        $antwortD = false;
    }
    writeInputField("Wie lautet die Frage?","questionName", $frage);
    writeInputField("Wie lautet die Antwortmöglichkeit A?","answerA", $antwortA);
    writeInputField("Wie lautet die Antwortmöglichkeit B?","answerB", $antwortB);
    writeInputField("Wie lautet die Antwortmöglichkeit C?","answerC", $antwortC);
    writeInputField("Wie lautet die Antwortmöglichkeit D?","answerD", $antwortD);
}

function questionAufzeahlfrage($array = false, $key = false){
    if ($array !== false && $key !== false) {
        $currentQuestion = $array[$key];
        $frage = $currentQuestion['questionName'];
    } else {
        $frage = false;
    }
    writeInputField("Wie lautet die Frage?","questionName", $frage);
    writeInputField("Was wurde am meisten genannt?","mostOftenAnswer", $frage);
    writeInputField("Percentage:","mostOftenAnswerPercentage", $frage, "number");
    writeInputField("Wie wurde fast am meisten genannt?","oftenAnswer", $frage);
    writeInputField("Percentage: ","oftenAnswerPercentage", $frage, "number");
    writeInputField("Was wurde mittel häufig genannt?","middleAnswer", $frage);
    writeInputField("Percentage: ","middleAnswerPercentage", $frage, "number");
    writeInputField("Was wurde fast am wenigsten genannt?","leastAnswer", $frage);
    writeInputField("Percentage:","leastAnswerPercentage", $frage, "number");
    writeInputField("Was wurde am wenigsten genannt?","leastOftenAnswer", $frage);
    writeInputField("Percentage: ","leastOftenAnswerPercentage", $frage, "number");
}

/**
 * Gibt alle aktuellen Fragen aus
 * @return void
 */
function questionOverview($pathToRoot)
{
    $questionArray = [];
    $blockarray = ["1","2","3","4"];
    foreach ($blockarray as $key) {
        array_push($questionArray,openJSON($key));
    }
    foreach ($questionArray as $key => $value) {
        $newKey = $key + 1;
        echo "<br><div class='questionsBlock'>Block $newKey: </div><div class='questions'>";
        foreach ($value as $questionKey => $item) {
            loadQuestions($questionKey, $item, $newKey, $pathToRoot);
        }
        echo "</div>";
    }
}

function loadQuestions($questionKey, $item, $blockKey, $pathToRoot){
    $questionIndex = $questionKey+1;
    $questionName = $item['questionName'];
    if (isset($item['answer'])) {
        $questionAnswers = $item['answer'];
        echo "<p> Frage $questionIndex: $questionName <br>=> $questionAnswers</p>";
    } elseif (isset($item['answerA'])){
        $questionAnswerA = $item['answerA'];
        $questionAnswerB = $item['answerB'];
        $questionAnswerC = $item['answerC'];
        $questionAnswerD = $item['answerD'];
        echo "<p> Frage $questionIndex: $questionName <br>=> Richtige Antwort : $questionAnswerA,
                      B: $questionAnswerB, C: $questionAnswerC, D: $questionAnswerD</p>";
    } elseif (isset($item['mostOftenAnswer'])) {
        //am häufigsten
        $questionMostOften =  $item['mostOftenAnswer'];
        $questionMostOftenPer = $item['mostOftenAnswerPercentage'];
        //am 2. häufigsten
        $questionOften = $item['oftenAnswer'];
        $questionOftenPer = $item['oftenAnswerPercentage'];
        // am 3. häufigsten
        $questionMiddle = $item['middleAnswer'];
        $questionMiddlePer = $item['middleAnswerPercentage'];
        // am 2. wenigsten
        $questionLeast = $item['leastAnswer'];
        $questionLeastPer = $item['leastAnswerPercentage'];
        // am wenigsten
        $questionLeastOften = $item['leastOftenAnswer'];
        $questionLeastOftenPer = $item['leastOftenAnswerPercentage'];

        echo "<p> Frage $questionIndex: $questionName 
              <br> => Am meisten genannt: $questionMostOften mit $questionMostOftenPer% 
              <br> => Am 2. meisten genannt: $questionOften mit $questionOftenPer%
              <br> => Am 3. meisten genannt: $questionMiddle mit $questionMiddlePer%
              <br> => Am 2. wenigsten genannt: $questionLeast mit $questionLeastPer%
              <br> => Am wenigsten genannt: $questionLeastOften mit $questionLeastOftenPer%                
              ";

    } else {
        echo "<p> Frage $questionIndex: $questionName</p>";
    }
    checkImage($blockKey, $questionName, $pathToRoot);
}

function checkImage($block, $nameQuestion, $pathToRoot){

    $dir = "$pathToRoot"."/Bloecke/"."$block";
    $array = glob($dir . "/*.*");
    $nameJson = "$dir" . "/" . "questionsB" . "$block" . ".json";
    $keyJSON = array_search( $nameJson, $array );
    unset($array[$keyJSON]);
    $workingArray = array_values($array);
    $namedArray = [];
    foreach ($workingArray as $value){
        $name = str_replace($dir."/", '',$value);
        $name = preg_replace("/[.].+/", '', $name);
        $name = str_replace(" ","", $name);// Klaut alle Leerzeichen
        $name = str_replace("?", "", $name);
        $name = str_replace("_", "", $name);
        $tmpArray = [$name => $value];
        array_push($namedArray, $tmpArray);
    }
    $counter = 1;
    $nameQuestion = str_replace(" ","", $nameQuestion);
    $nameQuestion = str_replace("?", "", $nameQuestion);
    foreach ($namedArray as $item){
        if (isset($item[$nameQuestion])){
            echo "<p><img class='questionImages' alt='$nameQuestion' src='$item[$nameQuestion]'></p>";
            $counter ++;
        }
    }
}

function addBanner($wayToRoot = ".") {
    echo "<div class='banner-container'>
			    <img class='banner' alt='Banner' src= '$wayToRoot\LOGO.jpeg'>
          </div>";

}

function addQuicklinks($user, $pathIndicator = ".") {
    echo "<section class='quicklinks'><div class='linkBox'>";
    if ($user == "Admin"){
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\adminInterface.php'>Interface</a></div>";
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\Questions\questions.php'>Questions</a></div>";
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\Game\gameAdminView.php'>Game</a></div>";
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\override.php'>Override</a></div>";
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\Chat\chat.php'>Chat</a></div>";
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\block4Transmitter.php'>Block 4</a></div>";
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\..\index.php'>Log Out</a></div>";
    } elseif ($user == "Quizmaster"){
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\gameMode.php'>Gamemode</a></div>";
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\..\index.php'>Log Out</a></div>";
    }
    echo "</div></section><br>";
}

function playerPoints (){
    $root = $_SERVER['DOCUMENT_ROOT'];
    $playerDir = $root."/Player/playerPoints.json";
    return json_decode(file_get_contents($playerDir));
}

function isGameRunning(): bool
{
    $root = $_SERVER['DOCUMENT_ROOT'];
    $dir = $root ."/Quizmaster/currentBlock/questionsCurrent.json";
    $fileArray = json_decode(file_get_contents($dir), true);
    if (isset($fileArray['0'])){
        return true;
    } else {
        return false;
    }
}


function checkQuestionNumber($block){
    $root = $_SERVER['DOCUMENT_ROOT'];
    $questionNumber = json_decode(file_get_contents($root. "/Bloecke/runningGame/questionNumber.json"));
    $index = "Block".$block;
    return $questionNumber -> $index; // retun zahl aus json
}

#[ArrayShape(["Block" => "mixed", "Question" => "mixed", "Answers" => "array|null", "Number" => "mixed"])]
function openFile($pfad) : array{

    $openedFile = json_decode(file_get_contents($pfad))['0'];
    $block = $openedFile -> block;
    $questionName = $openedFile -> questionName;
    if ($block == 1 || $block == 3){
        $answerArray = [$openedFile -> answer];
    }
    elseif ($block == 2) {
        $answerArray = [$openedFile -> answerA, $openedFile -> answerB, $openedFile -> answerC, $openedFile -> answerD];
    }
    else {
        $answerArray = [$openedFile -> questionMostOften, $openedFile -> questionOften, $openedFile -> questionMiddle,
                        $openedFile -> questionLeast, $openedFile -> questionLeastOften,
                        "Percentage" => [$openedFile -> questionMostOftenPer, $openedFile -> questionOftenPer,
                                         $openedFile -> questionMiddlePer, $openedFile -> questionLeastPer,
                                         $openedFile -> questionLeastOftenPer]];
    }
    $questionNumber = checkQuestionNumber($block);
    return ["Block" => $block, "Question" => $questionName, "Answers" => $answerArray, "Number" => $questionNumber];
}

/**
 * @param $pathToRoot
 */
function pointsAjax($pathToRoot){
    echo '<body onload="pointsAJAX(); setInterval(function(){pointsAJAX()}, 5000);">';
    internalPointsAJAX($pathToRoot);
}

/**
 * @param $pathToRoot
 *
 */
function internalPointsAJAX($pathToRoot){
    echo '
        <div class ="pointContainer">
        <div class ="points" id="player1">Spieler 1: 0 Punkte</div>
        <div class ="points" id="player2">Spieler 1: 0 Punkte</div>
        <br><br>
        </div>
        <script>
            function pointsAJAX () {
                let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function (){
                    if (this.readyState === 4 && this.status === 200){
                        let myArrayStr = JSON.parse(this.responseText);
                        let player1 = myArrayStr["Player1"];
                        let player2 = myArrayStr["Player2"];
                        document.getElementById("player1").innerHTML = "Spieler 1: " + player1 + " Punkte<br>";
                        document.getElementById("player2").innerHTML = "Spieler 2: " + player2 + " Punkte<br>";
                    }
                };
                xhttp.open("GET","'.$pathToRoot.'/Player/playerPoints.json", true);
                xhttp.send();
            }
        </script>
    ';
}

/**
 * @param $pathToRoot
 * UNUSED
 */
/**function showCurrentBlockAndQuestion($pathToRoot){
    echo '<body onload="infoAJAX(); setInterval(function(){infoAJAX()}, 5000);">';
    internalShowCurrentBlockAndQuestion($pathToRoot);
}*/

function internalShowCurrentBlockAndQuestion($pathToRoot){
    echo '
        <div class="questionsBlock" id="current"></div>
        <div class="questions">
        <div id="pre1"></div>
        <div id="pre2"></div>
        <div id="pre3"></div>
        </div>
        <br><br>
        <script>
            function infoAJAX () 
            {
                let infohttp = new XMLHttpRequest();
                infohttp.onreadystatechange = function (){
                    if (this.readyState === 4 && this.status === 200){
                        let myArrayStr = JSON.stringify(this.responseText).replace(/\D/g, ""); 
                        let block = myArrayStr.slice(-1);
                        checkCurrentQuestion(block);
                        let previousBlock1 = myArrayStr.slice(-2,-1);
                        if (previousBlock1 !== ""){
                            document.getElementById("pre1").innerHTML = "Das vorherige Spiel war Block " +previousBlock1;
                        }
                        
                        let previousBlock2 = myArrayStr.slice(-3,-2);
                        if (previousBlock2 !== ""){
                            document.getElementById("pre2").innerHTML = "Das Spiel vor dem vorherigem Spiel war Block " +previousBlock2;
                        }
                        
                        let previousBlock3 = myArrayStr.slice(-4,-3);
                        if (previousBlock3 !== ""){
                            document.getElementById("pre3").innerHTML = "Das 1. Spiel war Block " +previousBlock3;
                        }
                    }
                };
                infohttp.open("GET","'.$pathToRoot.'/Bloecke/runningGame/currentGame.json", true);
                infohttp.send();
            }
            
            function checkCurrentQuestion(block)
            {
                let questionhttp = new XMLHttpRequest();
                questionhttp.onreadystatechange = function (){
                    if (this.readyState === 4 && this.status === 200){
                        let questArray = JSON.parse(this.responseText);
                        let currentQuestion = questArray["Block"+block];
                        checkLastQuestion(block, currentQuestion);
                    }
                }
                questionhttp.open("GET","'.$pathToRoot.'/Bloecke/runningGame/questionNumber.json", true);
                questionhttp.send();
            }
            
            function checkLastQuestion(block, currentQuestion)
            {
                let lasthttp = new XMLHttpRequest();
                lasthttp.onreadystatechange = function (){
                    if (this.readyState === 4 && this.status === 200){
                        let thisArray = JSON.parse(this.responseText);
                        let length = Object.keys(thisArray).length;
                        if (currentQuestion === length)
                        {
                            document.getElementById("current").innerHTML = "Aktuell wird Block " +block+ " mit Frage " +currentQuestion+" !LETZTE FRAGE!";
                        } else {
                            document.getElementById("current").innerHTML = "Aktuell wird Block " +block+ " mit Frage " +currentQuestion;
                        }
                    }
                }
                lasthttp.open("GET", "'.$pathToRoot.'/Bloecke/"+block+"/questionsB"+block+".json", true)
                lasthttp.send();
            }
        </script>
    ';
}

function allInclusiveAJAX($pathToRoot, $blocked = false){
    if ($blocked == "true"){
        echo '<body onload="infoAJAX(); pointsAJAX(); blockingAJAX(); setInterval(function(){infoAJAX()}, 5000); 
              setInterval(function(){pointsAJAX()}, 5000); setInterval(function(){blockingAJAX()}, 1000);">';
        internalPointsAJAX($pathToRoot);
        internalShowCurrentBlockAndQuestion($pathToRoot);
        adminBlockingAJAX($pathToRoot);
    }else {
        echo '<body onload="infoAJAX(); pointsAJAX(); setInterval(function(){infoAJAX()}, 5000); 
              setInterval(function(){pointsAJAX()}, 5000);">';
              internalPointsAJAX($pathToRoot);
              internalShowCurrentBlockAndQuestion($pathToRoot);
    }
}


/**
 * @param $pathToRoot
 * UNUSED
 */
/**function waitForAJAX($pathToRoot){
    echo '<body onload="waitAJAX(); setInterval(function(){waitAJAX()}, 1000)">';
    internalWaitForAJAX($pathToRoot);
}*/

function internalWaitForAJAX($pathToRoot){
    echo '
        <script>
        function waitAJAX(){
            let waithttp = new XMLHttpRequest();
            waithttp.onreadystatechange = function(){
                if (this.readyState === 4 && this.status === 200){
                    let check = JSON.parse(this.responseText);
                    if (check === "true"){
                        window.location = "checkWinner.php"
                    }
                }
            }
            waithttp.open("GET", "' .$pathToRoot.'/Bloecke/runningGame/continue.json", true);
            waithttp.send();
        }
        
        </script>
    ';
}

/**
 * @param $pathToRoot
 * UNUSED
 */
/**function waitWithPoints($pathToRoot)
{
    echo '<body onload="waitAJAX(); pointsAJAX(); setInterval(function(){pointsAJAX()}, 5000); setInterval(function(){waitAJAX()}, 1000)">';
    internalPointsAJAX($pathToRoot);
    internalWaitForAJAX($pathToRoot);
}*/

function continueNextPageAJAX($pathToRoot, $playerID)
{
    echo '<body onload="nextAJAX();setInterval(function(){nextAJAX()}, 1000);">';
    internalNextPageAJAX($pathToRoot, $playerID);
}

function internalNextPageAJAX($pathToRoot, $playerID)
{
    echo '
        <script>
            function nextAJAX(){
                let nexthttp = new XMLHttpRequest();
                nexthttp.onreadystatechange = function(){
                    if (this.readyState === 4 && this.status === 200){
                        let check = JSON.parse(this.responseText);
                        if (check["Player'.$playerID.'"] === "true"){
                            window.location = "playerInterface.php";
                        }
                    }
                }
                nexthttp.open("GET", "'.$pathToRoot.'/Bloecke/runningGame/nextPage.json", true);
                nexthttp.send();
            }
        </script>
    ';
}

function nextPagePointsAJAX($pathToRoot, $playerID){
    echo '<body onload="nextAJAX(); pointsAJAX(); setInterval(function(){nextAJAX()}, 1000); 
          setInterval(function(){pointsAJAX()}, 5000);">';
    internalPointsAJAX($pathToRoot);
    internalNextPageAJAX($pathToRoot, $playerID);
}

/**
 * @param $pathToRoot
 * @param $playerID
 * UNUSED
 */
/**function checkIfAdminBlockedAJAX($pathToRoot, $playerID){
    echo '<body onload="blockedAJAX(); setInterval(function (){blockedAJAX()}, 1000);">';
    internalIfBlocked($pathToRoot, $playerID);
}*/

function internalIfBlocked($pathToRoot, $playerID)
{
    echo '
        <script>
        function blockedAJAX(){
            let blockedhttp = new XMLHttpRequest();
            blockedhttp.onreadystatechange = function (){
                if (this.readyState === 4 && this.status === 200){
                    let blocked = JSON.parse(this.responseText);
                    console.log(blocked);
                    if (blocked["Player'.$playerID.'"] === "true"){
                        let list = document.getElementsByClassName("btn");
                        for (index = 0; index < list.length; ++index) {
                            list[index].setAttribute("type", "reset");
                            list[index].classList.remove("btn-dark");
                            list[index].classList.add("btn-secondary");
                        }
                        document.getElementById("blockedIndicator").innerHTML = "<h3>BLOCKED</h3>";
                    } else {
                        let list = document.getElementsByClassName("btn");
                        for (index = 0; index < list.length; ++index) {
                            list[index].setAttribute("type", "submit");
                            list[index].classList.add("btn-dark");
                            list[index].classList.remove("btn-secondary");
                        }
                        document.getElementById("blockedIndicator").innerHTML = "";
                    }
                }
            }
            blockedhttp.open("GET", "'.$pathToRoot.'/Bloecke/runningGame/blocked.json", true);
            blockedhttp.send();
        }
        </script>
    ';
}

/**
 * @param $pathToRoot
 * @param $playerID
 * UNUSED
 */
/**function blockedPoints($pathToRoot, $playerID){
    echo '<body onload="blockedAJAX; pointsAJAX(); setInterval(function (){blockedAJAX()}, 1000);
          setInterval(function(){pointsAJAX()}, 5000);">';
    internalPointsAJAX($pathToRoot);
    internalIfBlocked($pathToRoot, $playerID);
}*/

function adminBlockingAJAX($pathToRoot){
    echo '
        <form>
        <p class="btnID">Player1</p><p class="btnID">Player 2</p><br>
        <button id="button1" type="submit" formmethod="post" formaction="'.$pathToRoot.'/Admin/blocker.php" name="player1" value="">ERROR</button>
        <button id="button2" type="submit" formmethod="post" formaction="'.$pathToRoot.'/Admin/blocker.php" name="player2" value="">ERROR</button>
        </form>
        <br><br>
        <script>
            function blockingAJAX(){
                let blockinghttp = new XMLHttpRequest();
                blockinghttp.onreadystatechange = function (){
                    if (this.readyState === 4 && this.status === 200){
                        let object = JSON.parse(this.responseText);
                        if (object["Player1"] === "false"){
                            let boolean = "true"
                            document.getElementById("button1").setAttribute("value", boolean)
                            document.getElementById("button1").className = "btn btn-success"
                            document.getElementById("button1").innerHTML = "Blockieren"
                        } else {
                            let boolean = "false"
                            document.getElementById("button1").setAttribute("value", boolean)
                            document.getElementById("button1").className = "btn btn-danger"
                            document.getElementById("button1").innerHTML = "BLOCKED"
                        }
                        
                        if (object["Player2"] === "false"){
                            let boolean = "true";
                            document.getElementById("button2").setAttribute("value", boolean)
                            document.getElementById("button2").className = "btn btn-success"
                            document.getElementById("button2").innerHTML = "Blockieren"
                        } else {
                            let boolean = "false"
                            document.getElementById("button2").setAttribute("value", boolean)
                            document.getElementById("button2").className = "btn btn-danger"
                            document.getElementById("button2").innerHTML = "BLOCKED"
                        }
                    }
                }
                blockinghttp.open("GET", "'.$pathToRoot.'/Bloecke/runningGame/blocked.json", true);
                blockinghttp.send();
            }
        </script>
    ';
}

function blockedNextPagePoints($pathToRoot, $playerID, $block){
    if ($block == 4) {
        echo '<body onload="blockedAJAX; pointsAJAX(); nextAJAX(); block4Ajax();
          setInterval(function (){blockedAJAX()}, 1000); 
          setInterval(function(){pointsAJAX()}, 5000); 
          setInterval(function(){nextAJAX()}, 1000);
          setInterval(function (){block4Ajax()}, 1000); ">';
        internalPointsAJAX($pathToRoot);
        internalIfBlocked($pathToRoot, $playerID);
        internalNextPageAJAX($pathToRoot, $playerID);
    } else {
        echo '<body onload="blockedAJAX; pointsAJAX(); nextAJAX();
          setInterval(function (){blockedAJAX()}, 1000); 
          setInterval(function(){pointsAJAX()}, 5000); 
          setInterval(function(){nextAJAX()}, 1000);">';
        internalPointsAJAX($pathToRoot);
        internalIfBlocked($pathToRoot, $playerID);
        internalNextPageAJAX($pathToRoot, $playerID);
    }
}

function waitPointsNextPage($pathToRoot, $playerID){
    echo '<body onload="waitAJAX();pointsAJAX(); nextAJAX();
          setInterval(function(){waitAJAX()}, 1000); 
          setInterval(function(){pointsAJAX()}, 5000); 
          setInterval(function(){nextAJAX()}, 1000);">';
    internalWaitForAJAX($pathToRoot);
    internalPointsAJAX($pathToRoot);
    internalNextPageAJAX($pathToRoot, $playerID);
}

function whoWonAJAX($pathToRoot, $beforePoints){
    $points1 = $beforePoints["Player1"];
    $points2 = $beforePoints["Player2"];
    echo '
        <p id="showWinner"></p>
        <script>
        function whoWon(){
            let whohttp = new XMLHttpRequest();
            whohttp.onreadystatechange = function(){
                if (this.readyState === 4 && this.status === 200){
                    let array = JSON.parse(this.responseText);
                    let player1 = array["Player1"];
                    let player2 = array["Player2"];
                    if (player1 > '.$points1.'){
                        if (player2 > '.$points2.'){
                            document.getElementById("showWinner").className = " winnerBoth";
                            document.getElementById("showWinner").innerHTML = "Beide haben gewonnen";
                        } else {
                            document.getElementById("showWinner").className = " winnerPlayer";
                            document.getElementById("showWinner").innerHTML = "Player 1 hat gewonnen";
                        }
                    }
                    if (player2 > '.$points2.'){
                        if (player1 > '.$points1.'){
                            document.getElementById("showWinner").className = " winnerBoth";
                            document.getElementById("showWinner").innerHTML = "Beide haben gewonnen";
                        } else {
                            document.getElementById("showWinner").className = " winnerPlayer";
                            document.getElementById("showWinner").innerHTML = "Player 2 hat gewonnen";
                        }
                    }
                    if (player2 === '.$points2.' && player1 === '.$points1.' ) {
                        document.getElementById("showWinner").className = " winnerNone";
                        document.getElementById("showWinner").innerHTML = "Keiner hat gewonnen";
                    }
                }
            }
            
            whohttp.open("GET", "'.$pathToRoot.'/Player/playerPoints.json", true);
            whohttp.send();
        }
        
        </script>
    ';
}

/**
 * @param $pathToRoot
 * @param array $beforePoints
 * UNUSED
 */
/**function pointsAndWhoWon($pathToRoot, array $beforePoints){
    echo '<body onload="pointsAJAX(); setInterval(function(){pointsAJAX()}, 5000); 
          setTimeout(function(){whoWon()}, 10000);">';
    internalPointsAJAX($pathToRoot);
    whoWonAJAX($pathToRoot, $beforePoints);
}*/

function getPoints($player, $root) {
    $currentPoints = playerPoints();
    if($player == 1) {
        $currentPoints->Player1++;
    }
    elseif ($player == 2) {
        $currentPoints->Player2++;
    }
    $playerDir = $root."/Player/playerPoints.json";
    file_put_contents($playerDir, json_encode($currentPoints));
}

function checkBlock(): ?int
{
    $array = json_decode(file_get_contents("../Bloecke/runningGame/currentGame.json"), true);
    return end($array);
}

function readChatAJAX($pathToRoot)
{
    echo '<body onload="readChat(); setInterval(function(){readChat()}, 1000);">';
    internalReadChat($pathToRoot);
}

function internalReadChat($pathToRoot){
    echo '
        <div id="msgBox" class="msgBox"></div>
        <script>
        let controlArray = [];
        console.log(controlArray);
        function readChat(){
            let chathttp = new XMLHttpRequest();
            chathttp.onreadystatechange = function(){
                if (this.readyState === 4 && this.status === 200){
                    let msgArray = JSON.parse(this.responseText);
                    console.log(msgArray);
                    controlArray.forEach(function removSent(item, msgArray){  
                        delete msgArray[item];
                    })
                    console.log(msgArray);
                    msgArray.forEach(function listingChat(element, index){
                        document.getElementById("msgBox").innerHTML += "<p class=chatMsg>"+ element[0] + "</p><br>";
                        controlArray.push(index);
                        let leChat = document.getElementById("msgBox");
                        leChat.scrollTop = leChat.scrollHeight;
                    })
                }
            }
            chathttp.open("GET","' . $pathToRoot . '/Admin/Chat/chatLog.json", true);
            chathttp.send();
        }
        </script>
    
    ';
}

function pointsWhoWonChat($pathToRoot, array $beforePoints){
    echo '<body onload="pointsAJAX(); readChat(); setInterval(function(){pointsAJAX()}, 5000); 
          setInterval(function(){readChat()}, 1000); setTimeout(function(){whoWon()}, 5000);">';
    internalPointsAJAX($pathToRoot);
    whoWonAJAX($pathToRoot, $beforePoints);
}

function pointsChat($pathToRoot){
    echo '<body onload="pointsAJAX(); readChat(); setInterval(function(){pointsAJAX()}, 5000); 
          setInterval(function(){readChat()}, 1000);">';
    internalPointsAJAX($pathToRoot);
}

function checkCloserAjax($pathToRoot, $player, $otherPlayer, $rightAnswer)
{
    echo '
        <body onload="setTimeout(function(){checkClose()}, 1000)">
        <script>
        function checkClose()
        {
            let checkhttp = new XMLHttpRequest();
            checkhttp.onreadystatechange = function()
            {
                if (this.readyState === 4 && this.status === 200)
                {
                    let response = JSON.parse(this.responseText);
                    let myAnswer = response["Answer"];
                    checkOther("'.$otherPlayer.'", myAnswer)
                }
            }
            checkhttp.open("GET", "'.$pathToRoot.'/Player/AnswerPlayer'.$player.'.json", true);
            checkhttp.send();
        }
        
        function checkOther(otherPlayer, myAnswer)
        {
            let otherhttp = new XMLHttpRequest();
            otherhttp.onreadystatechange = function()
            {
                if (this.readyState === 4 && this.status === 200)
                {
                    let rightAnswer = "'.$rightAnswer.'";
                    let otherResponse = JSON.parse(this.responseText);
                    let otherAnswer = otherResponse["Answer"];
                    let otherDiff;
                    let myDiff;
                    if (myAnswer >= rightAnswer)
                    {
                        myDiff = myAnswer - rightAnswer;
                    } 
                    else
                    {
                        myDiff = rightAnswer - myAnswer;
                    }
                    
                    if (otherAnswer >= rightAnswer)
                    {
                        otherDiff = otherAnswer - rightAnswer;
                    } 
                    else
                    {
                        otherDiff = rightAnswer - otherAnswer;
                    }
                    
                    if (myDiff > otherDiff) {
                        window.location.href="pointsGiver.php?result=lost"
                    } else if (otherDiff > myDiff) {
                        window.location.href="pointsGiver.php?result=won"
                    } else if (otherDiff === myDiff) {
                        window.location.href="pointsGiver.php?result=won"
                    } else {
                        window.location.href="pointsGiver.php?result=ERROR"
                    }
                  
                }
            }
            otherhttp.open("GET", "'.$pathToRoot.'/Player/AnswerPlayer"+otherPlayer+".json", true);
            otherhttp.send();
        }
        </script>
    ';
}

function block4Ajax($pathToRoot){
    echo '
        <p id = "most" class="hiddenText"></p><br>
        <p id = "often" class="hiddenText"></p><br>
        <p id = "middle" class="hiddenText"></p><br>
        <p id = "less" class="hiddenText"></p><br>
        <p id = "least" class="hiddenText"></p>
        <script>
        function block4Ajax()
        {
            let fourhttp = new XMLHttpRequest();
            fourhttp.onreadystatechange = function()
            {
                if (this.readyState === 4 && this.status === 200)
                {
                    let answers = JSON.parse(this.responseText);
                    console.log(answers);
                    answers.forEach(function writeTo(item){
                        if (item["Pos"] === "Most" && item["reveal"] === "true")
                        {
                            let name = item["Name"];
                            let percentage = item["Percentage"]
                            document.getElementById("most").className = "visbleText";
                            document.getElementById("most").innerHTML = name + " mit " + percentage +"% - 5 Punkte";
                        } 
                        else if (item["Pos"] === "Often" && item["reveal"] === "true")
                        {
                            let name = item["Name"];
                            let percentage = item["Percentage"]
                            document.getElementById("often").className = "visbleText";
                            document.getElementById("often").innerHTML = name + " mit " + percentage +"% - 4 Punkte";
                        }
                        else if (item["Pos"] === "Middle" && item["reveal"] === "true")
                        {
                            let name = item["Name"];
                            let percentage = item["Percentage"]
                            document.getElementById("middle").className = "visbleText";
                            document.getElementById("middle").innerHTML = name + " mit " + percentage +"% - 3 Punkte";
                        }
                        else if (item["Pos"] === "Less" && item["reveal"] === "true")
                        {
                            let name = item["Name"];
                            let percentage = item["Percentage"]
                            document.getElementById("less").className = "visbleText";
                            document.getElementById("less").innerHTML = name + " mit " + percentage +"% - 2 Punkte";
                        }
                        else if (item["Pos"] === "Least" && item["reveal"] === "true")
                        {
                            let name = item["Name"];
                            let percentage = item["Percentage"]
                            document.getElementById("least").className = "visbleText";
                            document.getElementById("least").innerHTML = name + " mit " + percentage +"% - 1 Punkte";
                        }
                    })
                }
            }
            fourhttp.open("GET", "'.$pathToRoot.'/Player/block4Handler.json",true)
            fourhttp.send();
        }
        </script>
    ';
}