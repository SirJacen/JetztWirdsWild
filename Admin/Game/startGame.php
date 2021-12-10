<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
debugging("nein");

$chosenBlock = $_POST['block'];

$playedBlocksFile = $root."/Bloecke/runningGame/currentGame.json";
$dir = $root."/Bloecke/".$chosenBlock."/";
$finalDir = $root."/Quizmaster/currentBlock/questionsB".$chosenBlock.".json";
$file = glob($dir."questionsB".$chosenBlock.".json")['0'];
copy ($file,$finalDir);
$playedBlocks = [];
array_push($playedBlocks, $chosenBlock);
file_put_contents($playedBlocksFile,json_encode($playedBlocks));

$playerPoints = ["Player1" => 0, "Player2" => 0];
$playerDir = $root."\Player\playerPoints.json";
file_put_contents($playerDir, json_encode($playerPoints));

$questionNumber = ["Block1" => 0, "Block2" => 0, "Block3" => 0, "Block4" => 0];
$numberDir = $root."/Bloecke/runningGame/questionNumber.json";
file_put_contents($numberDir, json_encode($questionNumber));

$continue = "true";
$conDir = $root."/Bloecke/runningGame/continue.json";
file_put_contents($conDir, json_encode($continue));

$blocked = ["Player1" => "true", "Player2" => "false"];
$blockDir = $root."/Bloecke/runningGame/blocked.json";
file_put_contents($blockDir, json_encode($blocked));

header("Location: gameAdminView.php");