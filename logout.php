<?php
require_once 'includes/functions.php';


$_SESSION = array();


session_destroy();


header("Location: index.php");
exit;
?>