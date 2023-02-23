<?php

require '../gmail/email.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$email = $_POST['email'];
$full_name = "";
$subject = "Verifikasi Email Gaspol!";
$body = "<p>Kode OTP adalah : ".base64_decode($_POST['otp'])."</p>";

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
