<?php 

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();
$api_url = "http://192.168.1.100:8004/webrest/";

$originator = $_POST['originator'];
$destination = $_POST['destination'];

try {

    $api_data = array(
        'code' => 'ADDFRD',
        'data' => array(
            'from' => $originator, // FPIN ORANG MAU JOIN
            'to' => $destination, // FPIN ADMIN CLUB
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
