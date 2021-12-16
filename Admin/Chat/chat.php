<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
debugging("no");

openSide("../..");
addQuicklinks("Admin", "..");
writeToJson();
echo "<div class='questionsBlock'><h1>Chat</h1></div><div class='questions'>";
readChatAJAX("../..");
writeMsg();
echo "</div>";
closeSide();

function writeToJson()
{
    if (isset($_POST['msgBtn']) && $_POST['msgBtn'] == "true")
    {
        $jsonArray = json_decode(file_get_contents("chatLog.json"), true);
        $message = $_POST['message'];
        $time = date("H:i:s");
        $msgArray = [$message, $time];
        array_push($jsonArray, $msgArray);
        file_put_contents("chatLog.json", json_encode($jsonArray));
    }
}

function writeMsg()
{
    echo "
        <form>
        <br>
        <textarea name='message' placeholder='Hier die Nachricht eingeben' required></textarea>
        <br>
        <button id='msgButton' class='btn btn-dark' type='submit' formmethod='post' 
                formaction='chat.php' name='msgBtn' value='true'>Bestätigen</button> 
        </form>
    ";
}