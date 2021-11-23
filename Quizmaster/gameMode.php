<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";

openSide();


echo "Hallo1";
echo $_POST["schaetzen"];

if($_POST['schaetzen']==='on'){
    echo "Hallo";
}elseif($_POST['buzzer']==="on"){
    echo "Hallo";
}elseif($_POST['allgemein']==='on'){
    echo "Hallo";
}elseif($_POST['aufzaehlen']==='on'){
    echo "Hallo";
}
echo "Hallo2";
closeSide();
