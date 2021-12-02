<?php

function openSide($wayToRoot = "."){
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
    addBanner($wayToRoot);
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
 * @return mixed
 */
function openJSON($block): mixed
{
    $root = $_SERVER['DOCUMENT_ROOT'];
    $dir = "$root"."/Bloecke/"."$block";

    $fileName = "$dir"."/"."questionsB"."$block".".json";
    return json_decode(file_get_contents($fileName),true);
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
        echo "<p> Frage $questionIndex: $questionName <br>=> A: $questionAnswerA,
                      B: $questionAnswerB, C: $questionAnswerC, D: $questionAnswerD</p>";
    }else {
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
        $tmpArray = [$name => $value];
        array_push($namedArray, $tmpArray);
    }
    $counter = 1;
    foreach ($namedArray as $item){
        if (isset($item[$nameQuestion])){
            echo "<p>Bild $nameQuestion $counter:<br><img class='questionImages' alt='$nameQuestion' src='$item[$nameQuestion]'></p>";
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
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\..\index.php'>Log Out</a></div>";
    } elseif ($user == "Quizmaster"){
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\quizMasterInterface.php'>Interface</a></div>";
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\gameMode.php'>Gamemode</a></div>";
        echo "<div class='link'><a class='btn btn-dark' href='$pathIndicator\..\index.php'>Log Out</a></div>";
    }
    echo "</div></section><br>";
}

function winnerResult($winner1,$winner2 = false ){
    if($winner2==false){
        if($winner1==1) {
            echo"<h1 style = 'background-color:MediumSeaGreen;' > Player 1 </h1 >
                 <h1 style = 'background-color:Tomato;' > Player 2 </h1 >";
        }elseif($winner1==2) {
            echo"<h1 style = 'background-color:Tomato;' > Player 1 </h1 >
                 <h1 style = 'background-color:MediumSeaGreen;' > Player 2 </h1 >";
        }
    }else {
        echo "<h1 style = 'background-color:MediumSeaGreen;' > Player 1 </h1 >
         <h1 style = 'background-color:MediumSeaGreen;' > Player 2 </h1 >";
    }
}

function winnerCalc($answerFile){
    $answers = json_decode($answerFile);
    $anwersPlayer1 = $answers["Player1"];
    $answersPlayer2 = $answers["Player2"];
    if($anwersPlayer1==$answers["Answer"] && $answersPlayer2==$answers["Answer"]){
        winnerResult(1,2);
    }elseif($anwersPlayer1==$answers["Answer"]){
        winnerResult(1);
    }elseif($answersPlayer2==$answers["Answer"]){
        winnerResult(2);
    }
}

function playerPoints (){
    $root = $_SERVER['DOCUMENT_ROOT'];
    $playerDir = $root."\Player\playerPoints.json";
    return json_decode(file_get_contents($playerDir));
}

function isGameRunning(): bool
{
    $root = $_SERVER['DOCUMENT_ROOT'];
    $dir = $root ."/Quizmaster/currentBlock/";
    $fileArray = glob($dir."*.json");
    if (isset($fileArray['0'])){
        return true;
    } else {
        return false;
    }
}


function pointsAjax($pathToRoot){
    echo '
       <body onload="myFunction(); setInterval(function(){myFunction()}, 5000);">
        <p id="test"></p>
        <script>
            function myFunction () {
                let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function (){
                    if (this.readyState === 4 && this.status === 200){
                        var myArrayStr = JSON.stringify(this.responseText).replace(/\D/g, "");
                        var player1 = myArrayStr.charAt(1);
                        var player2 = myArrayStr.charAt(3)
                        document.getElementById("test").innerHTML = "Spieler 1: " + player1 + " Punkte<br>Spieler 2: " + player2 + " Punkte";
                    }
                };
                xhttp.open("GET","'.$pathToRoot.'/Player/playerPoints.json", true);
                xhttp.send();
            }
        </script>
    ';
}