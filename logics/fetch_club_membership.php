<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if($_POST['f_pin'] != ''){
    $f_pin = $_POST['f_pin'];

    $query = $dbconn->prepare("SELECT * FROM CLUB_MEMBERSHIP LEFT JOIN TKT ON TKT.ID =  CLUB_MEMBERSHIP.CLUB_CHOICE WHERE CLUB_MEMBERSHIP.F_PIN = '$f_pin' AND CLUB_MEMBERSHIP.STATUS = 1");
    $query->execute();
    $results = $query->get_result();
    $query->close();

    $clubs = array();
    while ($result = $results->fetch_assoc()) {
        $clubs[] = $result;
    };

    echo json_encode($clubs);
}

?>