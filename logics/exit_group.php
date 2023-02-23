<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();
$api_url = "http://192.168.1.100:8004/webrest/";

// get message data
$f_pin = $_POST['f_pin'];
$group_id = $_POST['group_id'];

try {

    $api_data = array(
        'code' => 'EXITGRUP',
        'data' => array(
            'from' => $f_pin, // FPIN + millisecond (new Date().getTime().toString())
            'group_id' => $group_id
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
    // $api_json_result = json_decode($api_result);

    if (http_response_code() != 200) {
        throw new Exception('Send message failed!');
    }

    echo("Success");

} catch (Exception $e) {

    echo($e);

}

