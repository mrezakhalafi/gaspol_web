<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');


if (isset($_GET['query'])) {
    $dbconn = paliolite();

    $que = "%" .$_GET['query'] . "%";

    $query = $dbconn->prepare("SELECT u.* FROM USER_LIST u left join BUSINESS_ENTITY be on u.BE = be.ID WHERE (u.BE = 282) AND CONCAT(u.FIRST_NAME,' ',u.LAST_NAME) LIKE '%".$que."%'");
    $query->execute();
    $users  = $query->get_result();
    $query->close();

    $rows = array();
    while ($shop = $users->fetch_assoc()) {
        $rows[] = $shop;
    };

    return $rows;
}
