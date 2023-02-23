<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

$f_pin = $_POST['f_pin'];
$type = $_POST['type'];

// INSERT FOLLOW

// $query = "INSERT INTO  () VALUES ('')";

if (mysqli_query($dbconn, $query)){
    echo(0);
}else{
    echo(1);
}

?>