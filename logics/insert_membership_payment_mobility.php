<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

$f_pin = base64_decode($_POST['fpin']);
if ($f_pin == 'null') {
  $f_pin = '';
}
$method = $_POST['method'];
$status = $_POST['status'];
$price = $_POST['price'];
$reg_type = $_POST['reg_type'];
$date = $_POST['date'];

$transaction_id = md5($f_pin . $date);

session_start();
$ref_id = rand(100000000,999999999);
$_SESSION['ref_id'] = $ref_id;

$ref_id_t = '0'.$ref_id;

$sql = "
INSERT INTO `REGISTRATION_PAYMENT` (
    PAYMENT_ID, F_PIN, REG_TYPE, PRICE, 
    METHOD, STATUS, DATE, REF_ID
  ) 
  VALUES 
    (
      '$transaction_id', '$f_pin', $reg_type, 
      $price, '$method', $status, $date, '$ref_id_t'
    )
";

echo $sql;
$query = $dbconn->prepare($sql);
$query->execute();
$query->close();

echo 'Success';


