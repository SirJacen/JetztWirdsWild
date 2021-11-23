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

header("Location: ../gameAdminView.php");