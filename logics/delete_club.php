<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

$f_pin = $_POST['f_pin'];
$club = $_POST['club'];

// UPDATE PURCHASE

$query = "UPDATE CLUB_MEMBERSHIP SET STATUS = 3 WHERE F_PIN = '".$f_pin."' AND CLUB_CHOICE = '".$club."'";

if (mysqli_query($dbconn, $query)){
    echo(0);
}else{
    echo(1);
}

?>