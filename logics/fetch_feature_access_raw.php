<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if(isset($_REQUEST['f_pin'])){
    $f_pin = $_REQUEST['f_pin'];
}

$user_type = 0;

$sql_user = "SELECT * FROM `USER_LIST` WHERE `F_PIN` = '".$f_pin."' limit 1";
$query_user = $dbconn->prepare($sql_user);
$query_user->execute();
$result_user = $query_user->get_result()->fetch_object();
if(!is_null($result_user)){
    if($result_user->IS_CHANGED_PROFILE == 1){
        $user_type = 1;
    }
}
$query_user->close();

$sql_kta = "SELECT * from KTA WHERE `F_PIN` = '".$f_pin."' LIMIT 1";
$query_kta = $dbconn->prepare($sql_kta);
$query_kta->execute();
$result_kta = $query_kta->get_result()->fetch_object();
if(!is_null($result_kta)){
    if($result_kta->STATUS_ANGGOTA == 1){
        $user_type = 3;
    } else {
        $user_type = 2;
    }
}
$query_kta->close();

$sql_kis = "SELECT * FROM `KIS` WHERE `F_PIN` = '".$f_pin."'";
$query_kis = $dbconn->prepare($sql_kis);
$query_kis->execute();
$kis = $query_kis->get_result()->fetch_assoc();
if(!is_null($kis)){
  $user_type = 4;
}
$query_kis->close();

$sql_tkt = "SELECT * FROM `TKT` WHERE `F_PIN` = '".$f_pin."'";
$query_tkt = $dbconn->prepare($sql_tkt);
$query_tkt->execute();
$tkt = $query_tkt->get_result()->fetch_assoc();
if(!is_null($tkt)){
  $user_type = 5;
}
$query_tkt->close();

$sql_taa = "SELECT * FROM `TAA` WHERE `F_PIN` = '".$f_pin."'";
$query_taa = $dbconn->prepare($sql_taa);
$query_taa->execute();
$taa = $query_taa->get_result()->fetch_assoc();
if(!is_null($taa)){
  $user_type = 6;
}
$query_taa->close();

if(isset($_REQUEST['feature'])){
    $feature = $_REQUEST['feature'];
    $query = $dbconn->prepare("SELECT `KEY`, `VALUE` from FEATURE_ACCESS_GASPOL where `USER_TYPE` = " . $user_type . " AND `KEY` = '". $feature ."' ");
} else {
    $query = $dbconn->prepare("SELECT `KEY`, `VALUE` from FEATURE_ACCESS_GASPOL where `USER_TYPE` = " . $user_type . " ");
}
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

// echo json_encode($rows);

return $rows;
