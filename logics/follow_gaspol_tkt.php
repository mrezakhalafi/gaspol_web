<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

$f_pin = $_POST['f_pin'];
$l_pin = $_POST['l_pin'];

// INSERT FOLLOW

$query = "INSERT INTO FOLLOW_TKT (F_PIN, TKT_ID, CREATED_DATE) VALUES ('".$f_pin."','".$l_pin."','".date("Y-m-d h:i:s")."')";

if (mysqli_query($dbconn, $query)){
    echo(0);
}else{
    echo(1);
}

?>