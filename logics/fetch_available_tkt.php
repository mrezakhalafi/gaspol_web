<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$f_pin = $_GET['f_pin'];
// SELECT USER PROFILE
if(!isset($f_pin) && isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}
$rows = array();
if (isset($f_pin)) {
    $sql = "SELECT t.*,
    (SELECT COUNT(ft.F_PIN) FROM FOLLOW_TKT ft WHERE ft.TKT_ID = t.ID) AS FOLLOWERS,    
    (SELECT COUNT(cm.F_PIN) FROM CLUB_MEMBERSHIP cm WHERE cm.CLUB_CHOICE = t.ID) AS MEMBERS
    FROM TKT t
    WHERE NOT EXISTS
    (SELECT ft.* FROM FOLLOW_TKT ft WHERE t.ID = ft.TKT_ID AND ft.F_PIN = '$f_pin')
    ORDER BY RAND() LIMIT 10";
    $query = $dbconn->prepare($sql);
    $query->bind_param("s", $f_pin);
    // SELECT USER PROFILE
    $query->execute();
    $groups  = $query->get_result();
    $query->close();
    
    while ($group = $groups->fetch_assoc()) {
        $rows[] = $group;
    };
};
echo json_encode($rows);
?>