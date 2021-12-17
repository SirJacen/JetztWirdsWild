<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
debugging("no");

$initialArray = json_decode(file_get_contents("../Player/Questions/currentQuestion.json"), true);
$questionArray = $initialArray["0"];

if(isset($_POST["go"]))
{
    $index = array_search($_POST["Name"], $questionArray);
    $newIndex = $index."Per";
    $percentage = $questionArray[$newIndex];
    $sendArray = ["Pos" => $_POST["Pos"], "Name" => $_POST["Name"], "Percentage" => $percentage, "reveal" => true];
    $beforeArray = json_decode(file_get_contents("../Player/block4Handler.json"),true);
    array_push($beforeArray, $sendArray);
    file_put_contents("../Player/block4Handler.json", json_encode($beforeArray));
    postToChat("Die '".$_POST["Pos"]."' Frage wird aufgedeckt. Antwort: ".$_POST["Name"]."");
    Header("Location: block4Transmitter.php");
}
elseif (checkBlock() == "4")
{
    openSide("..");
    addQuicklinks("Admin");
    $beforeArray = json_decode(file_get_contents("../Player/block4Handler.json"),true);
    $name = $questionArray["questionName"];
    $answerArray = ["Most" => $questionArray["questionMostOften"], "Often" => $questionArray["questionOften"],
                    "Middle" => $questionArray["questionMiddle"], "Less" => $questionArray["questionLeast"],
                    "Least" => $questionArray["questionLeastOften"]];
    foreach ($beforeArray as $index)
    {
        $tmpArray = $index;
        $tmpPos = $tmpArray["Pos"];
        $tmpName = $answerArray[$tmpPos];
        $answerArray[$tmpPos] = ["Name" => $tmpName, "Set" => true];
    }
    echo "<div class='questionsBlock'><h1>Frage:<br> $name</h1></div><div class='questions'>
         ";
    foreach ($answerArray as $key => $value) {
        if (is_array($value)){
            $name = $value["Name"];
            echo "<form>
                  <button type='reset' class='btn btn-secondary'>$name</button>  
                  </form>";
        } else {
            echo "<form><input type='hidden' name='go' value='true'>
                  <input type='hidden' name='Name' value='$value'><input type='hidden' name='Pos' value='$key'>
                  <button class='btn btn-dark' type='submit' formmethod='post' 
                  formaction='block4Transmitter.php'>$value</button></form>";
        }
    }
    echo "<form><button class='btn btn-dark' type='submit' formmethod='post' 
          formaction='block4Transmitter.php'>Refresh</button></form></div>";
}
else
{
    openSide("..");
    addQuicklinks("Admin");
    echo "<h1>Block 4 wird noch nicht gespielt</h1>";
}
closeSide();

function postToChat($msg){
    $jsonArray = json_decode(file_get_contents("./Chat/chatLog.json"), true);
    $time = date("H:i:s");
    $msgArray = [$msg, $time];
    array_push($jsonArray, $msgArray);
    file_put_contents("./Chat/chatLog.json", json_encode($jsonArray));
}
