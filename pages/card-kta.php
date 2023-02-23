<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$f_pin = $_GET['f_pin'];

$dbconn = paliolite();

$ver = time();

$sqlData = "SELECT * FROM KTA kta WHERE kta.F_PIN = '$f_pin' ORDER BY kta.ID DESC LIMIT 1";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$resDATA = $queDATA->get_result()->fetch_assoc();
$name = $resDATA["NAME"];
$stats = $resDATA["STATUS_ANGGOTA"];
$name_fp = $resDATA["PROFILE_IMAGE"];
$unique_number = (explode(".",(explode("-",$name_fp))[1]))[0];
$queDATA->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form KTA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">

    <!-- Script QR CODE -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script type="text/javascript">
        function generateBarCode() {
            var nric = "TEST";
            var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + nric + '&amp;size=50x50';
            $('#barcode').attr('src', url);
        }
    </script>
</head>

<body>

    <div style="margin: 60% auto; height: 100%; display: flex; justify-content: center;">
        <div style=" width: 280px; display: flex; justify-content: center; align-items: center;">
            <div style=" background-image: url(../assets/img/card-template.png); background-size: contain; background-repeat: no-repeat; background-position: center center; margin: 10px; height: 300px; width: 100%; border-radius: 10px; position: relative;">
                <div style="position: absolute; top: 130px; left: 12px;">
                    <img src="../images/<?=$name_fp?>" alt="Foto Profil" style="width: 52px; height: 65px;">
                </div>
                <div style="position: absolute; top: 120px; left: 70px;">
                    <p style="font-size: 10px;"><?= strtoupper($name) ?></p>
                </div>
                <div style="position: absolute; top: 140px; left: 70px;">
                    <p style="font-size: 10px; color: green;"><?= strtoupper($unique_number) ?></p>
                </div>
                <div style="position: absolute; top: 160px; left: 70px;">
                    <p style="font-size: 10px;"><?= $stats == 0 ? "Basic" : "Full Membership" ?></p>
                </div>
                <div style="position: absolute; top: 150px; right: 15px;">
                    <img id='barcode' src="https://api.qrserver.com/v1/create-qr-code/?data=<?= strtoupper($unique_number) ?>&amp;size=100x100" alt="" width="40" height="40" style="color: green;" />
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
    </script>
    <script src="../assets/js/form-kta.js?v=<?php echo $ver; ?>"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>