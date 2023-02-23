<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();


// follow + visitor visibility

if (isset($_REQUEST['param'])) {
    if ($_REQUEST['param'] == "greet") {
        $query = $dbconn->prepare("SELECT `VALUE_TEXT` FROM `SHOP_SETTINGS` WHERE `PROPERTY` = 'CHAT_GREETING'");
        $query->execute();
        $geoloc = $query->get_result()->fetch_assoc();
        $geolocSts = $geoloc['VALUE_TEXT'];
        $query->close();
    
        echo $geolocSts;
    }
    if ($_REQUEST['param'] == "url_gaspol") {
        $query = $dbconn->prepare("SELECT `PROPERTY`,`VALUE_TEXT` FROM `SHOP_SETTINGS` WHERE `PROPERTY` in ('GASPOL_TAB1_URL','GASPOL_TAB2_URL')");
        $query->execute();
        $groups = $query->get_result();
        $query->close();
        
        $rows = array();
        while ($group = $groups->fetch_assoc()) {
            $rows[] = $group;
        };
    
        echo json_encode($rows);
    }
}

?>