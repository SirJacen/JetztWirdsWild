<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root"."/functions.php";

$currentlyBlocked = json_decode(file_get_contents("../Bloecke/runningGame/blocked.json"),true);

if (isset($_POST['player1'])){
    $currentlyBlocked['Player1'] = $_POST['player1'];
    file_put_contents("../Bloecke/runningGame/blocked.json",json_encode($currentlyBlocked));
} elseif (isset($_POST['player2'])){
    $currentlyBlocked['Player2'] = $_POST['player2'];
    file_put_contents("../Bloecke/runningGame/blocked.json",json_encode($currentlyBlocked));
}else {
    echo "An Error occurred";
    print_r($currentlyBlocked);
}
header("Location:Game/gameAdminView.php");