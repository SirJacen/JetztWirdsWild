<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";

openSide();

echo "<h1>Hi Admin. Was willst du tun?</h1>";

echo "<form><button type='submit' formaction='gameAdminView.php'>Spiel überwachen</button>
      <button type='submit' formaction='questions.php'>Fragen bearbeiten</button></form>";

closeSide();
