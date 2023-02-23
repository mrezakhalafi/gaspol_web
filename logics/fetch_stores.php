<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

// SELECT USER PROFILE
if(!isset($f_pin) && isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}


if (isset($f_pin)) {
  $blocked_users_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_blocked_users.php');
    $blocked_users = array();
    foreach ($blocked_users_raw as $blocked_user) {
        $blocked_users[] = $blocked_user["L_PIN"];
    }

    $users_reported_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_users_reported_raw.php');
    $users_reported = array();
    foreach ($users_reported_raw as $user_reported) {
        $users_reported[] = $user_reported["F_PIN_REPORTED"];
    }

    $user_reports_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_reported_users.php');

  //   $query = $dbconn->prepare("SELECT 
  //   s.*, 
  //   be.ID AS BE_ID, 
  //   srp.AMOUNT AS REWARD_POINT, 
  //   ssa.ADDRESS, 
  //   ssa.VILLAGE, 
  //   ssa.DISTRICT, 
  //   ssa.CITY, 
  //   ssa.PROVINCE, 
  //   ssa.ZIP_CODE, 
  //   ssa.PHONE_NUMBER, 
  //   ssa.COURIER_NOTE, 
  //   COUNT(pc.COMMENT_ID) AS COMMENTS 
  // FROM 
  //   SHOP s 
  //   LEFT JOIN SHOP_REWARD_POINT srp ON (
  //     s.CODE = srp.STORE_CODE 
  //     AND srp.F_PIN = '$f_pin'
  //   ) 
  //   LEFT JOIN BUSINESS_ENTITY be ON s.PALIO_ID = be.COMPANY_ID 
  //   LEFT JOIN SHOP_SHIPPING_ADDRESS ssa ON s.CODE = ssa.STORE_CODE 
  //   LEFT JOIN POST po ON po.MERCHANT = s.CODE
  //   LEFT JOIN POST_COMMENT pc ON pc.POST_ID = po.POST_ID
  // WHERE 
  //   s.IS_VERIFIED = 1 
  //   AND s.IS_QIOSK = 4
  //   OR s.CREATED_BY = '$f_pin' 
  //   GROUP BY s.CODE
  // ORDER BY 
  //   s.SCORE DESC");
  $sql = "SELECT 
  u.F_PIN AS CODE,
  u.BE AS BE_ID,
  u.IMAGE AS THUMB_ID,
  CONCAT(u.FIRST_NAME,' ',u.LAST_NAME) AS NAME,
  cc.CATEGORY,
  0 AS IS_LIVE_STREAMING,
  1 AS SHOW_FOLLOWS,
  (
    SELECT COUNT(pr.POST_ID)
    FROM POST_REACTION pr
    LEFT JOIN POST p ON p.POST_ID = pr.POST_ID
    WHERE p.F_PIN = '$f_pin'
  ) AS TOTAL_LIKES,
  (
    SELECT COUNT(com.POST_ID)
    FROM POST_COMMENT com
    LEFT JOIN POST p ON p.POST_ID = com.POST_ID
    WHERE p.F_PIN = '$f_pin'
  ) AS COMMENTS,
  (
    SELECT COUNT(F_PIN)
    FROM FOLLOW_LIST
    WHERE L_PIN = p.F_PIN
  ) AS TOTAL_FOLLOWS,
  (
    SELECT COUNT(fl.F_PIN)
    FROM FOLLOW_LIST fl
    WHERE fl.L_PIN = p.F_PIN AND fl.F_PIN = '$f_pin'
  ) AS IS_FOLLOWED
  FROM USER_LIST u
  LEFT JOIN POST p ON p.F_PIN = u.F_PIN
  LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID 
  LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID 
  WHERE 
  u.BE IN (
  SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin'
  )
  AND p.EC_DATE IS NULL
  AND u.F_PIN IN (
    SELECT p.F_PIN FROM POST p GROUP BY p.F_PIN HAVING COUNT(p.F_PIN) > 0
  ) GROUP BY u.F_PIN";
    $query = $dbconn->prepare($sql);
    // echo $sql;
}
// else {
//     $query = $dbconn->prepare("SELECT 
//     s.*, 
//     be.ID AS BE_ID,
//     COUNT(pc.COMMENT_ID) AS COMMENTS 
//   FROM 
//     SHOP s 
//     LEFT JOIN BUSINESS_ENTITY be ON s.PALIO_ID = be.COMPANY_ID 
//     LEFT JOIN POST po ON po.MERCHANT = s.CODE
//     LEFT JOIN POST_COMMENT pc ON pc.POST_ID = po.POST_ID
//   WHERE 
//     s.IS_VERIFIED = 1 
//     AND s.IS_QIOSK = 4
//     GROUP BY s.CODE
//   ORDER BY 
//     s.SCORE DESC");
// };

$query->execute();
$groups  = $query->get_result();
$query->close();

// echo "<pre>";
// print_r($user_reports_arr);
// echo "</pre>";

$rows = array();
while ($group = $groups->fetch_assoc()) {
  $created_by = $group['CODE'];
  // $is_less_100_users = false;
  // if (in_array($created_by,$user_reports_arr)) {
    $is_less_100_users = (!in_array($created_by,$user_reports_arr)) || $user_reports_arr[$created_by]['TOTAL_REPORTS'] < 100;
  // }
  $is_not_reported_user = !in_array($created_by, $users_reported);
  if (!in_array($created_by, $blocked_users) && $is_less_100_users && $is_not_reported_user) {

    $rows[] = $group;
  }
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
