<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();
$store_id = $_GET['store_id'];

// SELECT USER PROFILE
if(!isset($store_id) && isset($_GET['store_id'])){
    $store_id = $_GET['store_id'];
}
if (isset($store_id)) {
    // $query = $dbconn->prepare("SELECT p.THUMB_ID, p.IS_SHOW, s.CODE as STORE_CODE FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE p.SHOP_CODE = ? ORDER BY p.IS_SHOW, p.SCORE DESC, p.CREATED_DATE DESC");
    // $query->bind_param("s", $store_id);
}
else {
    // $query = $dbconn->prepare("SELECT p.THUMB_ID, p.IS_SHOW, s.CODE as STORE_CODE FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE ORDER BY p.IS_SHOW, p.SCORE DESC, p.CREATED_DATE DESC");

    $query = $dbconn->prepare("SELECT 	
	ul.F_PIN AS STORE_CODE,
	p.FILE_ID AS THUMB_ID, 
	1 AS `IS_SHOW` 
FROM 
	POST p 
    LEFT JOIN USER_LIST ul ON ul.F_PIN = p.F_PIN
        LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID 
        LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID 
WHERE c.EDUCATIONAL = 5
AND p.EC_DATE IS NULL
GROUP BY ul.F_PIN");

//     $query = $dbconn->prepare("SELECT 	
// 	s.CODE AS STORE_CODE,
// 	p.FILE_ID AS THUMB_ID, 
// 	1 AS `IS_SHOW` 
// FROM 
// 	POST p 
// LEFT JOIN SHOP s ON p.MERCHANT = s.CODE 
//         LEFT JOIN SHOP_POST sp ON sp.POST_CODE = p.POST_ID 
//         LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID 
//         LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID 
// WHERE c.EDUCATIONAL = 5
// GROUP BY s.CODE");
}
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

echo json_encode($rows);
?>