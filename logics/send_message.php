<?php 

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();
$api_url = "http://192.168.1.100:8004/webrest/";

// get message data
$message_id = $_POST['message_id'];
$originator = $_POST['originator'];
$destination = $_POST['destination'];
$content = base64_decode($_POST['content']);
$sent_time = $_POST['sent_time'];
$scope = $_POST['scope'];
$chat_id = $_POST['chat_id']; // if custom topic

$is_complain = $_POST['is_complain'];
$call_center_id = $_POST['call_center_id'];

$reply_to = $_POST['reply_to'];
$file_id = $_POST['file_id'];

try {

    $api_data = array(
        'code' => 'SNDMSG',
        'data' => array(
            'message_id' => $message_id, // FPIN + millisecond (new Date().getTime().toString())
            'from' => $originator, // FPIN
            'to' => $destination, // FPIN
            'message_text' => $content, // dari JS, JSON stringify->base64; di sini decode base64->json_decode`
            'scope' => $scope, // 
            'chat_id' => "", // ''
            'is_complaint' => 0, // 0
            'call_center_id' => "", // ''
            'reply_to' => $reply_to, // msg_id = '',
            'file_id' => $file_id
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

    if (http_response_code() != 200) {
        throw new Exception('Send message failed!');
    }

} catch (Exception $e) {

    echo("<script>console.log(" . $e . ");</script>");

}

echo 'Success';
