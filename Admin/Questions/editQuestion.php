<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";

debugging("nein");

if($_POST['questionSelection'] == "Weiter"){ // Bearbeiten
    openSide("..\..");
    $jsonObject = openJSON($_POST['block']);
    switchBoard($jsonObject);

} elseif ($_POST['questionSelection'] == "Delete") { //Löschen
    $jsonObject = openJSON($_POST['block']);
    $key = $_POST['selectedQuestion'];
    unset($jsonObject[$key]);
    $newArray = array_values($jsonObject);
    json($newArray, $_POST['block'], true);
    $_SESSION['edited'] = true;
    header("Location: questions.php");


} elseif ($_POST['questionType'] == "Weiter"){ //Auswählen
    openSide("..\..");
    echo "Question Selection";
    createQuestionSelection(openJSON($_POST['block']));

} else {
    openSide("..\..");
    echo "Choose Block";
    chooseBlockType("editQuestion.php", "questions.php");
}
closeSide();

function createQuestionSelection($array){
    startForm("post", "editQuestion.php");
    foreach ( $array as $key => $item) {
        $name = $item['questionName'];
        $numberQuestion = $key + 1;
        echo "<input type=\"radio\" name=\"selectedQuestion\" value=\"$key\" required>Frage $numberQuestion: $name<br>";
    }
    echo "</label>";
    $block = $_POST['block'];
    echo "<input type='hidden' name='block' value='$block'>";
    deleteForm();
    closeForm("questionSelection", "Weiter", "editQuestion.php");
}

function deleteForm()
{
    echo "
        <button class='btn btn-dark' type='submit' name='questionSelection' value='Delete'>Delete</button>
    ";
}

function switchBoard($array){
    startForm("post", "editorJSON.php");
    $block = $_POST['block'];
    $selectedKey = $_POST['selectedQuestion'];

    switch($block) {
        case "1": // schätzen
            editSchaetzfrage($array, $selectedKey);
            break;
        case "2": // buzzer
            editBuzzerfrage($array, $selectedKey);
            break;
        case "3": //allgemein
            editAllgemeinfrage($array, $selectedKey);
            break;
        case "4": //aufzählgame
            editAufzeahlgame($array, $selectedKey);
            break;
    }

    echo "<input type='hidden' name='block' value='$block'>
          <input type='hidden' name='selectedQuestion' value='$selectedKey'>";

    closeForm("question","Speichern" );
    returnInSite("questionType", "Weiter", "questionSelection", "Zurück", $block);
}

function returnInSite(mixed $name1, $value1, mixed $name2, $value2, $block)
{
        echo "
        <form method='post' action='editQuestion.php'>
        <input type='hidden' name='$name1' value='$value1'>
        <input type='hidden' name='$name2' value='$value2'>
        <input type='hidden' name='block' value='$block'>
        <input type='submit' name='goBack' value='Zurück'>
        </form>
        ";
}

function editSchaetzfrage($array, $key)
{

    echo "<h3>Schätzfrage bearbeiten</h3><br>";
    questionSchaetzAllgemein($array, $key);

}

function editBuzzerfrage($array, $key)
{

    echo "<h3>Buzzerfrage bearbeiten</h3>";
    questionBuzzerfrage($array,$key);
}

function editAllgemeinfrage($array, $key)
{
    echo "<h3>Allgemeinfrage bearbeiten</h3><br>";
    questionSchaetzAllgemein($array, $key);

}

function editAufzeahlgame($array, $key)
{
    echo "<h3>Schätzfrage bearbeiten</h3><br>";
    questionAufzeahlfrage($array,$key);
}








