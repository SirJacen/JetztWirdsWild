<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root" . "/functions.php";
openSide(".", true);
addQuicklinks("Points" );
allInclusiveAJAX(".", false, true);
closeSide();
