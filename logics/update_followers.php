<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

$f_pin = $_POST['f_pin'];
$l_pin = $_POST['l_pin'];

// GET KTA
$query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE L_PIN = '".$l_pin."'");
$query->execute();
$countFollowers = $query->get_result();
$query->close();

echo(mysqli_num_rows($countFollowers));

?>