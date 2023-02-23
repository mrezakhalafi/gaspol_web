<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$sql = "SELECT * FROM IMI_PARTNERS";
$query = $dbconn->prepare($sql);
$query->execute();
$result = $query->get_result();
$query->close();

$partners = array();
while ($partner = $result->fetch_assoc()) {
    $partners[] = $partner;
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

echo json_encode(utf8ize($partners));

?>