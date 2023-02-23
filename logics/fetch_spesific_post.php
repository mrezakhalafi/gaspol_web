<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if(isset($_REQUEST['product_code'])){
    $product_code = $_REQUEST['product_code'];
}

// $query = $dbconn->prepare("SELECT s.NAME, p.DESCRIPTION, p.THUMB_ID, p.CREATED_DATE, s.THUMB_ID AS SHOP_THUMB_ID FROM POST p LEFT JOIN SHOP s ON p.MERCHANT = s.CODE WHERE p.POST_ID='$product_code'");
// $sql = "SELECT CONCAT(ul.FIRST_NAME, ' ', ul.LAST_NAME) AS NAME, p.DESCRIPTION, p.CREATED_DATE, ul.IMAGE AS THUMB_ID FROM POST p LEFT JOIN USER_LIST ul ON p.F_PIN = ul.F_PIN WHERE p.POST_ID='$product_code'";
// $query = $dbconn->prepare("SELECT p.NAME, p.DESCRIPTION, p.THUMB_ID, p.CREATED_DATE, s.THUMB_ID as SHOP_THUMB_ID FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE p.CODE='$product_code'");
$sql = '(
    SELECT 
  p.POST_ID AS CODE, 
  p.TITLE, 
  p.DESCRIPTION, 
  p.CREATED_DATE, 
  p.REPORT,
  p.F_PIN AS CREATED_BY,
  cc.CATEGORY,  
  CONCAT(u.FIRST_NAME, " ", u.LAST_NAME) AS CREATOR_NAME, 
  NULL AS TAGGED_PRODUCT, 
  p.FILE_ID AS THUMB_ID, 
  p.F_PIN AS SHOP_CODE, 
  CONCAT(u.FIRST_NAME, " ", u.LAST_NAME) AS STORE_NAME, 
  p.SCORE,
  "" AS STORE_LINK, 
  u.IMAGE AS STORE_THUMB_ID, 
  p.TOTAL_LIKES, 
  0 AS IS_VERIFIED, 
  p.PRICING, 
  p.PRICING_MONEY AS PRICE, 
  0 AS `IS_PRODUCT`
FROM 
  POST p 
  LEFT JOIN USER_LIST u ON p.F_PIN = u.F_PIN 
  LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID 
  LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID 
  LEFT JOIN USER_LIST_EXTENDED ul ON p.F_PIN = ul.F_PIN 
WHERE 
 p.POST_ID = "'.$product_code.'"
    
)';
$query = $dbconn->prepare($sql);
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

// echo json_encode($rows);

return $rows;
