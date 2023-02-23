<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$rand_bg = rand(1, 12) . ".png";

$f_pin = $_GET['f_pin'];
$_SESSION['user_f_pin'] = $f_pin;

$dbconn = paliolite();

$ver = time();

$sqlData = "SELECT *
  FROM KTA kta LEFT JOIN REGISTRATION_PAYMENT ON kta.F_PIN = REGISTRATION_PAYMENT.F_PIN WHERE kta.F_PIN = '$f_pin' ORDER BY kta.ID DESC LIMIT 1";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$resDATA = $queDATA->get_result()->fetch_assoc();
$no_member = $resDATA['NO_ANGGOTA'];
$name_fp = $resDATA["PROFILE_IMAGE"];
$name = $resDATA["NAME"];
$address = $resDATA["ADDRESS"];
$stats = $resDATA["STATUS_ANGGOTA"];
$bloodtype = $resDATA["BLOODTYPE"];

// CONVERT MS TO DATE
$msdate = $resDATA["CREATED_DATE"];
$todate = strtotime('+1 year',strtotime($msdate));

// HOW TO CONVERT MS TO DATE
// $msdate = $resDATA["DATE"];
// $todate = ($msdate / 1000);
// echo date("d-m-Y", $todate);

$unique_number = $no_member;
$queDATA->close();

// KIS DATA
$kisData = $dbconn->prepare("SELECT * FROM KIS WHERE KIS.F_PIN = '$f_pin'");
$kisData->execute();
$kis = $kisData->get_result()->fetch_assoc();
$kisData->close();

// KIS CATEGORY
$kisCatData = $dbconn->prepare("SELECT * FROM KIS_CATEGORY");
$kisCatData->execute();
$kisCat = $kisCatData->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Card KTA Pro</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&amp;display=swap" rel="stylesheet">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=1652414177">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
    <!-- Script QR CODE -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script type="text/javascript">
        function generateBarCode() {
            var nric = "TEST";
            var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + nric + '&amp;size=50x50';
            $('#barcode').attr('src', url);
        }
    </script>
    <style>
        .data-person {
            color: white;
            font-size: 8px;
            font-weight: 700;
            position: absolute;
        }

        html {
            background-image: none;
        }

        body {
            background-image: url('../assets/img/lbackground_10.png');
            background-size: 100% auto;
            background-repeat: repeat;
        }

        .profile-picture {
            position: absolute;
            margin-top: 158px;
            /* margin-left: -338px; */
            margin-left: -312px;
            width: 90px;
            height: 90px;
            /* display: flex;
        align-items: center;
        justify-content: center; */
        }

        /* @media (min-width: 319px) and (max-width: 570px) {
            .profile-picture {
                margin-left: -289px;
            }
        } */

        /* @media (max-width: 321px) {
            .profile-picture {
                margin-left: -289px;
            }
        } */

        /* @media (min-width: 359px) {
            .profile-picture {
                margin-left: -324px !important;
            }
        } */

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        body.modal-open {
            overflow: hidden;
            position: fixed;
        }
        
    </style>
</head>



<body>
<!-- <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
</div> -->

<div class="p-3 shadow-sm" style="border-bottom: 1px solid #e4e4e4; background-color: white">
    <div class="row">
        <div class="col-2">
            <img src="../assets/img/membership-back.png" style="width: 30px; height: 30px" onclick="closeAndroid()">
        </div>
        <div class="col-10 pt-1">
            <b style="font-size: 14px">IMI Member Card</b>
        </div>
    </div>
</div>

<!-- KTA CARD -->
<div class="row" style="margin-top: 25px;">
    <div class="col-12 d-flex justify-content-center">
        <div class="row gx-0 p-3 mx-3" style="width: 370px;">
            <div id="card-structure" class="col-12 d-flex justify-content-center" style="height: 215px; border-radius: 10px; background-color: black; padding-left: 10px; padding-right: 10px">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center" style="margin-left: -11px; height: 350px; margin-top: -51px; width: 350px">
                        <img src="output-kta-mobility-4.png" alt="" style="border-radius: 15px;height: 90%;/* margin-top: -20px; */">
                        <div class="row gx-0">
                            <div class="col-12 d-flex justify-content-center" style="margin-top: 110px; margin-left: -159px">
                                <p class="data-person" style="margin-left: 31px;margin-top: -15px;"><?= $no_member ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="profile-picture col-3 d-flex justify-content-center">
                                <img src="../images/<?= $name_fp ?>" alt="" style="width: 66px; height: 76px; margin-top: -28px; max-width: none; object-fit: cover; object-position: center">
                            </div>
                            <div class="col-6 justify-content-center" style="position: absolute; margin-left: -234px; margin-top: 155px">
                                <div class="row gx-0">
                                    <div class="col-12">
                                        <p class="data-person" style="margin-top: -25px;"><?= $name ?></p>
                                    </div>
                                    <br>
                                    <div class="col-12">
                                        <p class="data-person" style="width: 145px;margin-top: -30px;"><?= $address ?></p>
                                    </div>
                                    <div class="col-12" style="margin-top: 20px; position: absolute">
                                        <p style="margin-top: -7px" class="data-person">
                                            <?php
                                                // $club
                                            ?>
                                            <?php 
                                                if (!isset($club)) {
                                                    echo "Private Club";
                                                }

                                                else {
                                                    echo $club;
                                                }
                                            ?>
                                        </p> 
                                    </div>
                                </div>
                                <div class="row gx-0" style="margin-top: -44px">
                                    <div class="col-12" style="position: absolute; margin-top: 52px">
                                        <p class="data-person">

                                            <?php
                                                $val = $stats;

                                                if ($val == 0) {
                                                    echo "Basic";
                                                } else {
                                                    echo "Full Membership";
                                                }
                                            ?>

                                        </p>
                                    </div>
                                    <div style="margin-top: -20px" class="row gx-0">
                                        <div class="col-3 justify-content-center" style="margin-left: -75px; margin-top: 95px; position: absolute">
                                            <p class="data-person">Gol. Darah <span style="position: absolute" class="ms-2"><?= $bloodtype ?></span></p>
                                        </div>
                                        <div class="col-3 justify-content-center" style="margin-top: 95px">
                                            <p class="data-person">Jatuh tempo &nbsp; &nbsp; &nbsp; <?= date("d-m-Y", $todate) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-center" style="position: absolute; margin-top: 137px; margin-left: -80px; width: 100px; height: 77px">
                                <!-- <div class="spinner-border" role="status" style="margin-top: 10px; margin-left: 35px; position: absolute">
                                    <span class="visually-hidden">Loading...</span>
                                </div> -->
                                <img id="barcode" onclick="modalQR()" src="https://api.qrserver.com/v1/create-qr-code/?data=<?= $no_member ?>&amp;size=100x100" alt="" width="120" height="z0" style="color: green; z-index: 999;/* margin-top: -10px; */">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BARCODE FIELD -->
<div class="row">
    <div class="col-12 d-flex justify-content-center">
        <div class="row gx-0 p-3 mx-3" style="width: 350px">
            <div class="col-12 d-flex justify-content-center" style="height: 250px; width: 350px; border-radius: 10px; background-color: transparent; margin-top: -33px">
                <div class="row gx-0">
                    <div class="col-12 d-flex justify-content-center" style="margin-left: 4px; width: 160px; height: 160px; margin-top: 45px">
                        <!-- <div class="spinner-border" role="status" style="margin-top: 20px; position: absolute; width: 120px; height: 120px">
                            <span class="visually-hidden">Loading...</span>
                        </div> -->
                        <img id="barcode" onclick="modalQR()" src="https://api.qrserver.com/v1/create-qr-code/?data=<?= $no_member ?>&amp;size=100x100" alt="" width="160" height="160" style="color: green; z-index: 999">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>

<section id="license-button">
    <div class="row p-3">
        <div class="col-12 d-flex justify-content-start">
            <p style="font-weight: 500">Racing License</p>
        </div>
        <div class="col-12 d-flex justify-content-center">
            <button class="btn" style="color: black; background-color: transparent; border-radius: 35px; border: 1px solid black; font-weight: 600; width: 100%" onclick="buyLicense()">Buy License</button>
        </div>
    </div>
</section>

<section id="license-list">
    <div class="row p-3">
        <div class="col-12 d-flex justify-content-start">
            <p style="font-weight: 500">Racing License</p>
        </div>

        <?php
        $list_category = explode("|", $kis['KATEGORI']);
        foreach ($list_category as $lc) {
            foreach ($kisCat as $kc) {
                if ($lc == $kc['CODE']) {
                    ?>
                    <div class="row gx-0 mt-3">
                        <div class="col-2 d-flex justify-content-center">
                            <img src="../assets/img/bg-cat.png" alt="" style="width: 50px; height: 50px">
                            <p style="position: absolute; font-size: 24px; margin-top: 7px; margin-left: 1px; font-weight: 600; opacity: 50%; color: #000000"><?= $lc ?></p>
                        </div>
                        <div class="col-10 text-start">
                            <p class="mb-0 mt-1 ms-2" style="font-size: 16px; font-weight: 600"><?= $kc['NAME'] ?></p>
                            <p class="mb-0 ms-2" style="font-size: 12px; color: #777777">Active until <?= date('d M Y', $untildate) ?></p>
                        </div>
                    </div>
                    <?php
                }
            }
        }
        ?>

        <!-- <div class="row gx-0 mt-3">
            <div class="col-2 d-flex justify-content-center">
                <img src="../assets/img/bg-cat.png" alt="" style="width: 50px; height: 50px">
                <p style="position: absolute; font-size: 24px; margin-top: 7px; margin-left: 1px; font-weight: 600; opacity: 50%; color: #000000">A1</p>
            </div>
            <div class="col-10 text-start">
                <p class="mb-0 mt-1 ms-2" style="font-size: 16px; font-weight: 600">Balap & Drag Race</p>
                <p class="mb-0 ms-2" style="font-size: 12px; color: #777777">Active until 12 Dec 2022</p>
            </div>
        </div> -->

    </div>
</section>

<div style="width: 100%; height: 300px; background-color: #e5e5e5"></div>

<!-- Modal -->
<div class="modal fade" id="modalQR" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img id="barcode_modal" style="width: 100%; height: 100%">
            </div>
        </div>
    </div>
</div>

<!-- <div class="row mt-5">
    <div class="col-12 d-flex justify-content-center">
        <img id="exit" onclick="closeAndroid()" src="../assets/img/GaspolUIandAnim_d/exit_only_red.png" style="
        width: 120px;
        height: 45px;
        bottom: 75px;
        right: 20px;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 200px">
    </div>
</div> -->

<!-- JS -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    var F_PIN = new URLSearchParams(window.location.search).get('f_pin');
</script>
<script src="../assets/js/form-kta.js?v=1652414177"></script>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>



<script>
    // import { jsPDF } from "jspdf";

    var f_pin = new URLSearchParams(window.location.search).get('f_pin');

    $('#social-share').hide();

    function buyLicense() {

        window.location.href = 'menu_membership?f_pin='+f_pin;

    }

    // function htmlToImage() {
    //     html2canvas(document.querySelector("#card-structure"), {
    //         useCORS: true
    //     }).then(canvas => {
    //         // document.body.appendChild(canvas);

    //         var image = canvas.toDataURL("image/png");
    //         var anchor = document.createElement('a');
    //         anchor.setAttribute('download', 'my-KTA-card.jpg');
    //         anchor.setAttribute('href', image);
    //         anchor.click();
    //     });
    // }

    // function htmlToPrint() {

    //     var w = document.getElementById("card-structure").offsetWidth;
    //     var h = document.getElementById("card-structure").offsetHeight;
    //     html2canvas(document.querySelector("#card-structure"), {
    //         useCORS: true,
    //         dpi: 300, // Set to 300 DPI
    //         scale: 2, // Adjusts your resolution
    //     }).then(canvas => {
    //         console.log(canvas);
    //         // var win=window.open();
    //         // win.document.write("<br><img src='"+canvas.toDataURL("image/png")+"'/>");
    //         // win.print();
    //         var img = canvas.toDataURL("image/png", 1);
    //         var doc = new jsPDF('L', 'pt', [w, h]);
    //         doc.addImage(img, 'PNG', 0, 0, w, h);
    //         // doc.save('sample-file.pdf');
    //         var uri = doc.output('datauristring')
    //         console.log(uri);
    //         var anchor = document.createElement('a');
    //         anchor.setAttribute('download', 'my-KTA-card.pdf');
    //         anchor.setAttribute('href', uri);
    //         anchor.click();

    //     });
    // }

    // function htmlShare() {

    //     if ($('#social-share').is(":hidden")) {
    //         $('#social-share').show();
    //     } else {
    //         $('#social-share').hide();
    //     }
    // }

    function closeAndroid() {

        // if (window.Android) {
        //     window.Android.finishGaspolForm();
        // } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishGaspolForm) {

        //     window.webkit.messageHandlers.finishGaspolForm.postMessage({
        //         param1: ""
        //     });
        //     return;

        // } else {
            history.back();
        // }
    }

    function modalQR() {
        $('#modalQR').modal('show');

        var link = $('#barcode').attr('src');

        // alert(link);

        $('#barcode_modal').attr('src', link);
    }
</script>

<script>
    var theme = 0;

    if (window.Android) {
        theme = window.Android.getThemes();
        getThemes(theme);
    }

    function getThemes(number) {

        // 0 = Blue 
        // 1 = Dark

        if (number == 0) {

            // $('#logo-1').attr('src','../assets/img/GaspolUIandAnim_d/fb_icon_d.png');
            // $('#logo-2').attr('src','../assets/img/GaspolUIandAnim_d/member_off_fia_fim_blue.png');
            // $('#menu-1').attr('src','../assets/img/GaspolUIandAnim_d/kta_mobility_d.png');
            // $('#menu-2').attr('src','../assets/img/GaspolUIandAnim_d/kta_pro_d.png');
            // $('#menu-3').attr('src','../assets/img/GaspolUIandAnim_d/kis_d.png');
            // $('#menu-4').attr('src','../assets/img/GaspolUIandAnim_d/gaspol_club_d.png');
            // $('#menu-5').attr('src','../assets/img/GaspolUIandAnim_d/imi_club_d.png');
            // $('#menu-6').attr('src','../assets/img/GaspolUIandAnim_d/taa_club_d.png');
            $('#exit').attr('src', '../assets/img/GaspolUIandAnim_d/exit_only_red.png');

        } else if (number == 1) {

            // $('#logo-1').attr('src','../assets/img/GaspolUIandAnim_d/fbIcon_black_d.png');
            // $('#logo-2').attr('src','../assets/img/GaspolUIandAnim_d/member_off_fia_fim_black.png');
            // $('#menu-1').attr('src','../assets/img/GaspolUIandAnim_d/kta_mobility_black_d.png');
            // $('#menu-2').attr('src','../assets/img/GaspolUIandAnim_d/kta_pro_black_d.png');
            // $('#menu-3').attr('src','../assets/img/GaspolUIandAnim_d/kis_black_d.png');
            // $('#menu-4').attr('src','../assets/img/GaspolUIandAnim_d/gaspol_club_black_d.png');
            // $('#menu-5').attr('src','../assets/img/GaspolUIandAnim_d/imi_club_black_d.png');
            // $('#menu-6').attr('src','../assets/img/GaspolUIandAnim_d/taa_club_black_d.png');
            $('#exit').attr('src', '../assets/img/GaspolUIandAnim_d/exit_only_black.png');

        }

    }

    // getThemes(number);
</script></body><!-- This templates was made by Colorlib (https://colorlib.com) --></html>

<script>
    <?php
        if (!isset($kis)) {
            ?>
            $("#license-button").show();
            $("#license-list").hide();
            <?php
        }
        else {
            ?>
            $("#license-button").hide();
            $("#license-list").show();
            <?php
        }
    ?>
</script>