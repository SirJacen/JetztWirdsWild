<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
debugging("nein");

openSide("..");

echo "<h1>Hi Player! Das Spiel startet gleich. Die Zuschauer wählen den ersten Fragenblock.</h1>";

closeSide();