<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
session_start();
$dbconn = newnus();

$f_pin = $_SESSION['f_pin'];

$_SESSION['web_login'] = null;
$_SESSION['is_scanned'] = null;
$_SESSION['f_pin'] = null;
$_SESSION['F_PIN'] = null;
$_SESSION['ADMIN_PROVINCE'] = null;

$query = "DELETE FROM WEB_LOGIN WHERE F_PIN = '".$f_pin."'";

if (mysqli_query($dbconn, $query)){
    echo(1);
}else{
    echo(0);
}

?>
