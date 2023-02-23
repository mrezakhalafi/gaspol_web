<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

session_start();

// GET FROM COLLECTION JS

$f_pin = $_POST['f_pin'];
$l_pin = $_POST['l_pin'];

// INSERT REPORT

$query = "INSERT INTO BLOCK_USER (F_PIN, L_PIN) VALUES ('".$f_pin."','".$l_pin."')";

$queryUnfollow = "DELETE FROM FOLLOW_LIST WHERE F_PIN = '$f_pin' AND L_PIN = '$l_pin'";

if (mysqli_query($dbconn, $query) && mysqli_query($dbconn, $queryUnfollow)){
    echo ("Berhasil");
}else{
    echo("Gagal dari Query");
}

?>