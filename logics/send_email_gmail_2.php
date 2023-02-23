<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

if(isset($_SESSION['f_pin'])){
    $f_pin = $_SESSION['f_pin'];
}
else if(isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}

$dbconn = paliolite();

$ver = time();
$email = $_POST['email'];
$id_kta = $_POST['id_kta'];

$sqlData = "SELECT * FROM KTA WHERE EMAIL = '$email'";

//   echo $sqlData;

$queMember = $dbconn->prepare($sqlData);
$queMember->execute();
$resMember = $queMember->get_result()->fetch_assoc();
$queMember->close();

// print_r($noAnggota);

require '../gmail/email.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$full_name = "";
$subject = "Selamat Datang di Gaspol!";
$body = "<p>Silahkan gunakan data berikut ini untuk login pada aplikasi Gaspol! (Masuk Melalui Menu Recovery)</p><p>Email : ".$email."</p><p>No Anggota: ".$resMember["NO_ANGGOTA"]." </p>";

//To send email
function sendVerifiedMail($email, $full_name, $subject, $body)
{
    try {
        // Get the API client and construct the service object.
        $client = getClient();
        $service = new Google_Service_Gmail($client);

        $message = createMessage('support@qmera.io', $email, $subject, $body);
        sendMessage($service, 'me', $message);

        return 'Message has been sent';

    } catch (Exception $e) {

        return "Message could not be sent. Mailer Error: {$e}";
    }
}

echo sendVerifiedMail($email, $full_name, $subject, $body);

?>
