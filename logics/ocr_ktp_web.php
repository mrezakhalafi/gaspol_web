<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

$dbconn = paliolite();

if (isset($_FILES['ektp'])) {
    $errors = array();
    $allowed_ext = array('jpg', 'jpeg', 'png', 'webp');
    $file_name = $_FILES['ektp']['name'];
    //   $file_name =$_FILES['image']['tmp_name'];

    $file_size = $_FILES['ektp']['size'];
    $file_tmp = $_FILES['ektp']['tmp_name'];

    $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
    $data = file_get_contents($file_tmp);
    $base64 = base64_encode($data);

    // echo "Base64 is " . $base64;

    $api_url = "http://192.168.1.100:8004/webrest/";
    $api_data = array(
        'code' => 'OCRKTP',
        'data' => array(
            'data' => $base64, //base64 string 
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
        throw new Exception('Company logo update failed, please try again!');
    }

    echo $api_result;
}
