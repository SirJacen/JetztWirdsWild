<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";

openSide("..");
addQuicklinks("Admin");

echo "<div><h1>Hi Admin. Was willst du tun?</h1>";

echo "<form><button class='btn btn-dark' type='submit' formaction='./Game/gameAdminView.php'>Spiel überwachen</button>
      <button class='btn btn-dark' type='submit' formaction='./Questions/questions.php'>Fragen bearbeiten</button></form></div>";

closeSide();
