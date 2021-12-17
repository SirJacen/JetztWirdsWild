<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
session_start();
debugging("no");

$player = $_SESSION["player"];
$rightAnswer = $_SESSION["rightAnswer"];

if ($player == 1) {
    $otherPlayer = 2;
} elseif ($player == 2) {
    $otherPlayer = 1;
} else {
    $otherPlayer = NULL;
    echo "error - No Player Set";
}

checkCloserAjax("..", $player, $otherPlayer, $rightAnswer);
