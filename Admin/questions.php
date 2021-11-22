<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
debugging("nein");
if ($_SESSION['edited'] == true) {
    function_alert("Frage erfolgreich bearbeitet!");
    $_SESSION['edited'] = false;
}

session_destroy();
openSide();

echo "<h1>Hi Admin. Was willst du tun?</h1>";


echo "<form><button type='submit' formaction='newQuestion.php'>Neue Frage erstellen</button>
      <button type='submit' formaction='editQuestion.php'>Fragen bearbeiten</button>";
returnTo("adminInterface.php");
echo "</form>";

questionOverview();
closeSide();