<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";

openSide();
debugging("ja");
echo "<h1>Hi Quizmaster. Welches Spiel soll es sein? </h1>";
echo "<form><button method='post' type='submit' name='schäetzen' value='on' formaction='gameMode.php'>Schätzfragen</button>";
echo "<form><button method='post' type='submit' name='buzzer' value='on' formaction='gameMode.php'>Buzzerfragen</button>";
echo "<form><button method='post' type='submit' name='allgemein' value='on' formaction='gameMode.php'>Allgemeinfragen</button>";
echo "<form><button method='post' type='submit' name='aufzaehlen' value='on' formaction='gameMode.php'>Aufzählgame</button>";

closeSide();