<?php

$id = $_GET['id'];

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.xendit.co/ewallets/charges/" . $id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "{\n\t\"charge_id\": \"" .$id. "\"\n}",
    CURLOPT_HTTPHEADER => array(
        "authorization: Basic eG5kX2RldmVsb3BtZW50X3RCTnkxZGRWb2pjcEN1M0ZjQjdJbHhybDNFZnFUY3V0akp4eGxMQzJrcWNtcUc4TFdFYll2VDF1VFFoVmo6",
        "cache-control: no-cache",
        "content-type: application/json"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}
