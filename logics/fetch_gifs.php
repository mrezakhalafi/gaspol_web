<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$f_pin = $_GET['f_pin'];

// $sqlGIF = "SELECT 
//   xg.* 
// FROM 
//   XPORA_GIF xg 
//   LEFT JOIN USER_LIST ul ON ul.BE = xg.BE_ID 
// WHERE 
// xg.BE_ID IN (
//     SELECT 
//       BE 
//     FROM 
//       USER_LIST 
//     WHERE 
//       F_PIN = '$f_pin'
//   ) 
//   AND ul.F_PIN = '$f_pin' 
//   AND (
//     ul.F_PIN NOT IN (
//       SELECT 
//         F_PIN 
//       FROM 
//         TKT
//     ) 
//     OR ul.F_PIN NOT IN (
//       SELECT 
//         F_PIN 
//       FROM 
//         KIS
//     ) 
//     OR ul.F_PIN NOT IN (
//       SELECT 
//         F_PIN 
//       FROM 
//         KTA
//     ) 
//     OR ul.F_PIN NOT IN (
//       SELECT 
//         F_PIN 
//       FROM 
//         TAA
//     )
//   ) 
// GROUP BY 
//   xg.FILENAME 
// ORDER BY 
//   xg.ID ASC
// ";
$sqlGIF = "SELECT 
xg.* 
FROM 
XPORA_GIF xg 
WHERE xg.BE_ID = 282
GROUP BY 
xg.FILENAME 
ORDER BY 
xg.ID ASC";
$queryGIF = $dbconn->prepare($sqlGIF);
$queryGIF->execute();
$resultGIF = $queryGIF->get_result();
$queryGIF->close();

$gif_arr = array();
while($gif = $resultGIF->fetch_assoc()) {
  $gif_arr[] = $gif;
}

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

echo json_encode(utf8ize($gif_arr));

?>