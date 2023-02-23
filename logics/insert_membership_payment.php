<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

$f_pin = base64_decode($_POST['fpin']);
$method = $_POST['method'];
$status = $_POST['status'];
$price = $_POST['price'];
$reg_type = $_POST['reg_type'];
$date = $_POST['date'];

$transaction_id = md5($f_pin . $date);

$sql = "
INSERT INTO `REGISTRATION_PAYMENT` (
    PAYMENT_ID, F_PIN, REG_TYPE, PRICE, 
    METHOD, STATUS, DATE
  ) 
  VALUES 
    (
      '$transaction_id', '$f_pin', $reg_type, 
      $price, '$method', $status, $date
    )
";

echo $sql;
$query = $dbconn->prepare($sql);
$query->execute();
$query->close();

echo 'Success';


