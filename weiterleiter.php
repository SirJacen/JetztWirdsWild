<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";
session_start();
$_SESSION['wrongID'] = true;
$redirectionSide = "index";
if ($_POST["username"]==="Player1" && $_POST["psw"]==="Player1"){
    $redirectionSide = "Player/playerInterface";
    $_SESSION['wrongID'] = false;
    $_SESSION['player'] = 1;
}elseif ($_POST["username"]==="Player2" && $_POST["psw"]==="Player2"){
    $redirectionSide = "Player/playerInterface";
    $_SESSION['wrongID'] = false;
    $_SESSION['player'] = 2;
}elseif($_POST["username"]==="Admin" && $_POST["psw"]==="Admin") {
    $redirectionSide = "Admin/adminInterface";
    $_SESSION['wrongID'] = false;
}elseif ($_POST["username"]==="Leiter" && $_POST["psw"]==="Leiter"){
    $redirectionSide = "Quizmaster/quizMasterInterface";
    $_SESSION['wrongID'] = false;
}elseif ($_POST["username"]==="Points" && $_POST["psw"]==="Points") {
    $redirectionSide = "points";
    $_SESSION['wrongID'] = false;
}
header("Location:".$redirectionSide.".php");
debugging("ja");
die();