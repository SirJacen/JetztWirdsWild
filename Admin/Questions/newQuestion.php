<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
openSide("..\..");
debugging("nein");

if ($_SESSION['message'] == "Finished!") {
    function_alert($_SESSION["message"]);
    $_SESSION['message'] = "Working";
}

If($_POST['questionType']==="Weiter") {

    startform("post","encoderJSON.php");

    $block = $_POST['block'];

    switch($block) {
        case "1": // schätzen
           newSchaetzfrage();
            break;
        case "2": // buzzer
            newBuzzerfrage();
            break;
        case "3": //allgemein
            newAllgemeinfrage();
            break;
        case "4": //aufzählgame
            newAufzeahlgame();
            break;
    }

    echo "<input type='hidden' name='block' value='$block'>";

    closeForm("question","Speichern", "newQuestion.php");
    session_destroy();
}

else {
    echo "<h1>Neue Frage hinzufügen</h1>";
    chooseBlockType("newQuestion.php", "questions.php");
}

closeSide();

function newSchaetzfrage() {
    echo "<h3>Neue Schätzfrage hinzufügen</h3><br>";
    questionSchaetzAllgemein();
}

function newBuzzerfrage() {
    echo "<h3>Neue Buzzerfrage hinzufügen</h3><br>";
    questionBuzzerfrage();
}

function newAllgemeinfrage() {
    echo "<h3>Neue Allgemeinfrage hinzufügen</h3><br>";
    questionSchaetzAllgemein();
}

function newAufzeahlgame() {
    echo "<h3>Neue Aufzählgamefrage hinzufügen</h3><br>";
    questionAufzeahlfrage();
}