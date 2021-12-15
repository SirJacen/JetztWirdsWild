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
    echo "error";
}

$myAnswerData = $root . "/Player/AnswerPlayer" . $player . ".json";
$otherAnswerData = $root . "/Player/AnswerPlayer" . $otherPlayer . ".json";

if (filesize($myAnswerData) == 0 || filesize($otherAnswerData) == 0) {
    openSide("..");
    echo "Mind eine Antwort fehlt";
    header("Refresh:5");
    closeSide();
} elseif (filesize($myAnswerData) > 0 && filesize($otherAnswerData) > 0) {
    $myAnswer = returnAnswer($myAnswerData);
    $otherAnswer = returnAnswer($otherAnswerData);

    $myDiff = checkDiff($myAnswer, $rightAnswer);
    $otherDiff = checkDiff($otherAnswer, $rightAnswer);

    $_SESSION['myDiff'] = $myDiff;
    $_SESSION['otherDiff'] = $otherDiff;


    if ($myDiff > $otherDiff) {
        $_SESSION["correct"] = false;
        header("Location:rightOrWrong.php");
    } elseif ($otherDiff > $myDiff) {
        getPoints($player, $root);
        $_SESSION['correct'] = true;
        header("Location:rightOrWrong.php");
    } elseif ($otherDiff == $myDiff) {
        getPoints($player, $root);
        header("Location:rightOrWrong.php");
    } else {
        echo "error";
    }

} else {
    echo("error");
}

function returnAnswer($path): float
{
    $array = json_decode(file_get_contents($path), true);
    $answer = $array["Answer"];
    return floatval($answer);
}

function checkDiff($d, $rightA): float
{
    if ($d >= $rightA) {
        return $d - $rightA;
    } else {
        return $rightA - $d;
    }
}
