<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

// $f_pin = $_GET['f_pin'];
// SELECT USER PROFILE
if(!isset($f_pin) && isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}
$rows = array();
if (isset($f_pin)) {
    $sql = "
   (SELECT 
    c1.POST_ID AS PRODUCT_CODE 
  FROM 
    POST_COMMENT c1 
    LEFT JOIN POST_COMMENT c2 ON c1.REF_COMMENT_ID = c2.COMMENT_ID 
  WHERE 
    (
      c1.REF_COMMENT_ID IS NULL 
      OR c2.ID IS NOT NULL
    ) 
    AND c1.F_PIN = '$f_pin'
   )
    ";
    $query = $dbconn->prepare($sql);
    // $query = $dbconn->prepare("SELECT c1.PRODUCT_CODE FROM PRODUCT_COMMENT c1 LEFT JOIN PRODUCT_COMMENT c2 on c1.REF_COMMENT_ID = c2.COMMENT_ID WHERE (c1.REF_COMMENT_ID IS NULL OR c2.ID IS NOT NULL) AND c1.F_PIN = ?");
    // $query->bind_param("s", $f_pin);
    // SELECT USER PROFILE
    $query->execute();
    $groups  = $query->get_result();
    $query->close();
    
    while ($group = $groups->fetch_assoc()) {
        $rows[] = $group;
    };
};
return $rows;
?>