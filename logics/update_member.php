<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

$f_pin = $_POST['f_pin'];
$club_choice = $_POST['club_choice'];

// GET KTA
$query = $dbconn->prepare("SELECT * FROM CLUB_MEMBERSHIP WHERE STATUS = 1 AND CLUB_CHOICE = '".$club_choice."'");
$query->execute();
$countMembers = $query->get_result();
$query->close();

echo(mysqli_num_rows($countMembers));

?>