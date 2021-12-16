<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
debugging("no");
// TODO
openSide("..");
addQuicklinks("Admin");
//Wenn Block == 4 dann einlesen der aktuellen Frage
//Auflisten der Antworten mit Percentage
//Unter oder neben jeder antwort einen senden Button
// Dieser packt Pos = [Most, Less, Often, etc], Name, Percentage, reveal = true in 1 Array
// Öffnet block4Hanler.json
//Inhalt in Array appends 1 Array von oben
// puts stuff in json again.
// send button changes color and reads already send
closeSide();