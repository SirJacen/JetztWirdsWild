<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
session_start();
debugging("no");

$result = $_GET["result"];
$player = $_SESSION["player"];

if ($result == "won") {
    getPoints($player, $root);
    $_SESSION['correct'] = true;
    header("Location:rightOrWrong.php");
} elseif ($result == "lost") {
    $_SESSION["correct"] = false;
    header("Location:rightOrWrong.php");
} else {
    echo "An Error Ocurred";
    debugging("ja");
}