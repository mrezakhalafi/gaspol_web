<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

session_start();
$f_pin = $_POST['f_pin'];
$l_pin = $_POST['shop_id'];

// DELETE BLOCK

$query = "DELETE FROM BLOCK_USER WHERE F_PIN = '".$f_pin."' AND L_PIN = '".$l_pin."'";

if (mysqli_query($dbconn, $query)){
    echo('Unblock success');
    // header("Location: ../pages/blocked_list?f_pin=".$f_pin."");
}else{
    echo("ERROR: Data gagal diubah. $sql. " . mysqli_error($dbconn));
}

?>