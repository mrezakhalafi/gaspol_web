<?php


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

// SELECT USER PROFILE
if (isset($_GET['store_id'])) {
    $store_id = $_GET['store_id'];
} else {
    die();
}

// $query = $dbconn->prepare("SELECT p.*, s.CODE as STORE_CODE, s.NAME as STORE_NAME, s.THUMB_ID as STORE_THUMB_ID, s.LINK as STORE_LINK, s.TOTAL_FOLLOWER as TOTAL_FOLLOWER FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE p.SHOP_CODE = '$store_id' AND p.IS_DELETED = 0 ORDER BY p.SCORE DESC, p.CREATED_DATE DESC");

// $str = '(
//     SELECT 
//       p.CODE, 
//       p.NAME AS TITLE, 
//       p.DESCRIPTION, 
//       p.CREATED_DATE, 
//       p.CATEGORY AS CATEGORY, 
//       s.CREATED_BY AS CREATED_BY, 
//       CONCAT(u.FIRST_NAME, " ", u.LAST_NAME) AS CREATOR_NAME, 
//       NULL AS TAGGED_PRODUCT, 
//       p.THUMB_ID, 
//       s.CODE AS SHOP_CODE, 
//       s.NAME AS STORE_NAME, 
//       s.LINK AS STORE_LINK, 
//       s.THUMB_ID STORE_THUMB_ID, 
//       s.TOTAL_FOLLOWER AS TOTAL_FOLLOWER, 
//       s.IS_VERIFIED AS IS_VERIFIED, 
//       1 AS `IS_PRODUCT`
//     FROM 
//       SHOP s 
//       LEFT JOIN PRODUCT p ON p.SHOP_CODE = s.CODE 
//       LEFT JOIN CONTENT_CATEGORY cc ON p.CATEGORY = cc.CATEGORY 
//       LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID 
//       LEFT JOIN USER_LIST u ON s.CREATED_BY = u.F_PIN 
//     WHERE 
//       c.EDUCATIONAL = 5      
//       AND p.IS_POST = 1
//       AND s.CODE = ' . $store_id . ') 
//   UNION 
//     (
//       SELECT 
//         p.POST_ID, 
//         p.TITLE, 
//         p.DESCRIPTION, 
//         p.CREATED_DATE, 
//         c.ID, 
//         p.F_PIN, 
//         CONCAT(u.FIRST_NAME, " ", u.LAST_NAME), 
//         sp.TAGGED_PRODUCT, 
//         p.FILE_ID, 
//         s.CODE, 
//         s.NAME, 
//         s.LINK, 
//         s.THUMB_ID, 
//         p.TOTAL_LIKES, 
//         s.IS_VERIFIED, 
//         0 AS `IS_PRODUCT`
//       FROM 
//         SHOP s 
//         LEFT JOIN POST p ON p.MERCHANT = s.CODE 
//         LEFT JOIN SHOP_POST sp ON sp.POST_CODE = p.POST_ID 
//         LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID 
//         LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID 
//         LEFT JOIN USER_LIST u ON p.F_PIN = u.F_PIN 
//         LEFT JOIN USER_LIST_EXTENDED ul ON p.F_PIN = ul.F_PIN 
//       WHERE 
//         c.EDUCATIONAL = 5
//         AND s.CODE = ' . $store_id . ')';



// UNION 
//   (
//     SELECT 
//       p.CODE, 
//       p.NAME AS TITLE, 
//       p.DESCRIPTION, 
//       p.CREATED_DATE, 
//       p.SHOP_CODE AS CREATED_BY, 
//       CONCAT(u.FIRST_NAME, " ", u.LAST_NAME) AS NAME, 
//       NULL AS TAGGED_PRODUCT, 
//       p.THUMB_ID, 
//       1 AS `IS_PRODUCT` 
//     FROM 
//       PRODUCT p 
//       LEFT JOIN USER_LIST u ON p.SHOP_CODE = u.F_PIN 
//     WHERE 
//       p.SHOP_CODE = "' . $store_id . '"
//   )

$str = '
(
  SELECT 
    p.POST_ID AS CODE, 
    p.TITLE, 
    p.DESCRIPTION, 
    p.CREATED_DATE, 
    p.F_PIN AS CREATED_BY, 
    CONCAT(u.FIRST_NAME, " ", u.LAST_NAME) AS NAME, 
    NULL AS TAGGED_PRODUCT, 
    CONCAT(p.THUMB_ID, "|", p.FILE_ID) AS THUMB_ID, 
    0 AS `IS_PRODUCT` 
  FROM 
    POST p 
    LEFT JOIN USER_LIST u ON p.F_PIN = u.F_PIN 
  WHERE 
    p.EC_DATE IS NULL
    AND
    p.F_PIN = "' . $store_id . '"
) 
';
        

$query = $dbconn->prepare($str);
$query->execute();
$groups = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

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

echo json_encode(utf8ize($rows));
