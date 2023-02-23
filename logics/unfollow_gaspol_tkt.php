<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

$f_pin = $_POST['f_pin'];
$id_unfollow = $_POST['id_unfollow'];

// UPDATE PURCHASE

$query = "DELETE FROM FOLLOW_TKT WHERE F_PIN = '".$f_pin."' AND TKT_ID = '".$id_unfollow."'";

if (mysqli_query($dbconn, $query)){
    echo(0);
}else{
    echo(1);
}

?>