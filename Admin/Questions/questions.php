<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
debugging("nein");
if ($_SESSION['edited'] == true) {
    function_alert("Frage erfolgreich bearbeitet!");
    $_SESSION['edited'] = false;
}

session_destroy();
openSide("..\..");
addQuicklinks("Admin", "..");

echo "<div><h1>Hi Admin. Was willst du tun?</h1>";
echo "<h3>Bei laufendem Spiel können Fragen im aktuell gespielten Block nicht bearbeitet werden!</h3>";

echo "<form><button class='btn btn-dark' type='submit' formaction='newQuestion.php'>Neue Frage erstellen</button>
      <button class='btn btn-dark' type='submit' formaction='editQuestion.php'>Fragen bearbeiten</button>";
returnTo("../adminInterface.php");
echo "</form>";

questionOverview("..\..");
echo "</div>";
closeSide();