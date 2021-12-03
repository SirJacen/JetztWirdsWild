<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";

openSide("..");
addQuicklinks("Quizmaster");
echo "<form>
              <button class='btn btn-dark' formmethod='post' type='submit' formaction='gameMode.php'>Spielen</button>
              </form>";
closeSide();