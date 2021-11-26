<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";

openSide("..");
debugging("ja");

if($_POST['schaetzen']==='on'){
    echo "Ik schätz dir";
}elseif($_POST['buzzer']==="on"){
    echo "Hallo";
}elseif($_POST['allgemein']==='on'){
    echo "Wer ist der Opa von deinem Urgroßonkel?";
}elseif($_POST['aufzaehlen']==='on'){
    echo "1,2,3,4,5,6,7,8,9";
}