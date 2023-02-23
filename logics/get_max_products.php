<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbconn = paliolite();

$showLinkless = 2;
try {
    $query = $dbconn->prepare("SELECT `VALUE` FROM `SHOP_SETTINGS` WHERE `PROPERTY` = 'SHOW_LINKLESS_STORE'");
    $query->execute();
    $geoloc = $query->get_result()->fetch_assoc();
    $showLinkless = $geoloc['VALUE'];
    $query->close();
} catch (\Throwable $th) {
}

if(!isset($store_id) && isset($_REQUEST['store_id'])){
    $store_id = $_REQUEST['store_id'];
}
if(!isset($f_pin) && isset($_REQUEST['f_pin'])){
    $f_pin = $_REQUEST['f_pin'];
}
if(!isset($que) && isset($_REQUEST['query'])){
    $que = $_REQUEST['query'];
}

$limit = 10;
$offset = 0;
$seed = $_GET['seed'];

if (isset($_GET['limit'])) {
    $limit = (intval($_GET['limit']) != 0 ) ? $_GET['limit'] : 10;
}
if (isset($_GET['offset'])) {
    $offset = (intval($_GET['offset']) != 0 ) ? $_GET['offset'] : 0;
}

if(!isset($que) && isset($_REQUEST['filter'])){
    $filter = $_REQUEST['filter'];
}

$sql_where = '';

if(isset($store_id) || isset($f_pin) || isset($que) || isset($filter)){
    $sql_where = $sql_where . " AND ";
}
if(isset($store_id)){
    $sql_where = $sql_where . "p.SHOP_CODE = '$store_id'";
    if(isset($que) || isset($f_pin) || isset($filter)){
        $sql_where = $sql_where . " AND ";
    }
}
if(isset($que)){
    
    $quelike = "%" . $que . "%";
    $sql_where = $sql_where . "(p.NAME like '$quelike' OR p.DESCRIPTION like '$quelike' OR s.NAME like '$quelike')";
    if(isset($f_pin) || isset($filter)){
        $sql_where = $sql_where . " AND ";
    }
}
if(isset($filter)) {
    // $sql_where = $sql_where . "p.CATEGORY = '$filter'";
    // if(isset($f_pin)){
    //     $sql_where = $sql_where . " AND ";
    // }
    $filter = $_REQUEST['filter'];

    $filterArr = explode('-', $_REQUEST['filter']);

    $sql_where .= '(';

    $tempArr = array();

    foreach($filterArr as $filter) {
        $tempArr[] = "p.CATEGORY = '$filter'";
    }

    $sql_where .= implode(' OR ', $tempArr);

    $sql_where .= ')';
    if(isset($f_pin)){
        $sql_where = $sql_where . " AND ";
    }
}
if(isset($f_pin)){
    $sql_where = $sql_where . "(s.IS_VERIFIED = 1 or s.CREATED_BY = '$f_pin' or s.CREATED_BY in (SELECT fl.L_PIN FROM FRIEND_LIST fl where fl.F_PIN = '$f_pin'))";
} else if(!(isset($store_id) || isset($f_pin) || isset($que) || isset($filter))) {
    $sql_where = $sql_where . " AND s.IS_VERIFIED = 1";
} else {
    $sql_where = $sql_where . " AND s.IS_VERIFIED = 1";
}
$sql_where = $sql_where . " AND p.IS_DELETED = 0 ORDER BY RAND($seed) LIMIT $limit OFFSET $offset";

$sql_where_post = str_replace(" AND p.IS_DELETED = 0","",$sql_where);
$sql_where_post = str_replace("p.CATEGORY","c.ID",$sql_where_post);
$sql_where_post = str_replace("p.SHOP_CODE","p.MERCHANT",$sql_where_post);


$sql = '
      SELECT 
        COUNT(*) AS ROWCOUNT
      FROM 
        SHOP s 
        LEFT JOIN POST p ON p.MERCHANT = s.CODE 
        LEFT JOIN SHOP_POST sp ON sp.POST_CODE = p.POST_ID 
        LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID 
        LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID 
        LEFT JOIN USER_LIST u ON p.F_PIN = u.F_PIN 
        LEFT JOIN USER_LIST_EXTENDED ul ON p.F_PIN = ul.F_PIN 
      WHERE 
        c.EDUCATIONAL = 5' . $sql_where_post;

// echo $sql;

$query = $dbconn->prepare($sql);

// SELECT USER PROFILE
$query->execute();
$groups  = $query->get_result()->fetch_assoc();
$query->close();


echo json_encode($groups['ROWCOUNT']);
?>