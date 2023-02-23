<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();
$api_url = "http://192.168.1.100:8004/webrest/";

$admin_fpin = $_POST['f_pin'];

$query = $dbconn->prepare("SELECT * FROM `CLUB_MEMBERSHIP` where `CLUB_CHOICE` in (SELECT ID FROM TKT WHERE F_PIN = ?) and `STATUS` = '0' and `F_PIN` != ? ;");    
$query->bind_param("ss", $admin_fpin, $admin_fpin);
$query->execute();
$result = $query->get_result();
$query->close();

foreach ($result as $row) {
    if(!empty($row["REF_ID"]) && !empty($row["F_PIN"])){
        try {
            $content = array(
                "form_id" => "105857",
                "form_title" => "Join+IMI+Club",
                "A01" => "",
                "club_type" => $row["CLUB_TYPE"],
                "province" => $row["CLUB_LOCATION"],
                "club" => $row["CLUB_CHOICE"]
            );

            $api_data = array(
                'code' => 'SNDMSG',
                'data' => array(
                    'from' => $row["F_PIN"], // FPIN
                    'to' => $admin_fpin, // FPIN
                    'message_text' => json_encode($content), // dari JS, JSON stringify->base64; di sini decode base64->json_decode`
                    'scope' => 18, // 
                    'chat_id' => "", // ''
                    'is_complaint' => 0, // 0
                    'call_center_id' => "", // ''
                    'reply_to' => $row["REF_ID"], // msg_id = '',
                    'file_id' => "105857"
                ),
            );

            $api_options = array(
                'http' => array(
                    'header'  => "Content-type: application/json\r\n",
                    'method'  => 'POST',
                    'content' => strval(json_encode($api_data))
                )
            );

            $api_stream = stream_context_create($api_options);
            $api_result = file_get_contents($api_url, false, $api_stream);
            $api_json_result = json_decode($api_result);
        } catch (Exception $e) {
        }
    }
}

echo "Fetched";

?>