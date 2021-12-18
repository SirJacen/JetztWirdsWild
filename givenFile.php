<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
debugging("no");

openSide(".", true);
addQuicklinks("Points" );
$AnswerPlayer1 = json_decode(file_get_contents("./Player/AnswerPlayer1.json"), true);
$AnswerPlayer2 = json_decode(file_get_contents("./Player/AnswerPlayer2.json"), true);

echo "<br>Player1: ";
print_r($AnswerPlayer1);
echo "<br>Player2: ";
print_r($AnswerPlayer2);

echo "<form><button class='btn btn-dark' type='submit' formmethod='post' formaction='givenFile.php'>Refresh</button></form>";
closeSide();
