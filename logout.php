<?php
session_start();

// Unset semua session variables
$_SESSION = array();

// Hancurkan session
session_destroy();

// Redirect ke login page
header("Location: login.php");
exit;
?>