<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";

openSide("..");
addQuicklinks("Quizmaster");
echo "<form><button class='btn btn-dark' type='submit' formmethod='post' formaction='gameMode.php'>Weiter</button></form>";
closeSide();