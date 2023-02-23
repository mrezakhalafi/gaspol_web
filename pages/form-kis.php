<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$f_pin = $_GET['f_pin'];

$dbconn = paliolite();

$ver = time();

// PRICE

$sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '1'";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$price = $queDATA->get_result()->fetch_assoc();
$queDATA->close();

$upgradeFee = $price['REG_FEE'];
$adminFee = $price['ADMIN_FEE'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form KIS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <script src="../assets/js/xendit.min.js"></script>

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">

    <style>
        .modal {
            z-index: 9999;
        }

        #modal-payment .modal-content {
            margin: 0;
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="main" style="padding: 0px">

            <form method="POST" class="main-form" id="kis-form" action="/gaspol_web/logics/register_new_kis" enctype="multipart/form-data">
                <div class="row gx-0 p-2" style="border-bottom: 2px #e5e5e5 solid">
                    <div class="col-1 d-flex justify-content-start">
                        <a href="menu_membership.php?f_pin=<?= $f_pin ?>"><img src="../assets/img/icons/Back-(Black).png" alt="" style="height: 36px"></a>
                    </div>
                    <div class="col-11 d-flex justify-content-center">
                        <h2 style="margin-bottom: 0px">Pembuatan KIS</h2>
                    </div>
                </div>
                <div class="container pt-4">
                <h2 class="text-center"><span style="font-size: 22px">Formulir <span style="color: #4966b1">KIS</span></span><br><span style="font-size: 16px; color: #626262">(Kartu Izin Start)</span></h2>
            </div>
            <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>
            <div class="container">
                    
                <div id="notfound" class="form-group-2 mt-3" style="display:none;">
                    <p><b>Data KTA tidak ditemukan.<b></p>
                </div>
                <div class="form-group-2 mt-3">
                    <b style="font-size: 18px">Data Anggota</b>
                    <div class="fotoProfil mt-4">
                        <p>Foto Profil</p>
                        <img id="fotoProfilKta" class="photo d-none" style="max-width: 50vw; max-height: 50vw;" />
                        <input type="hidden" id="fotoKta" name="fotoKta" value="">
                    </div>
                    <input type="text" name="name" id="name" placeholder="Nama Lengkap" required />
                    <input type="text" name="domisili" id="domisili" placeholder="Domisili/Provinsi (Sesuai KTP)" required />
                    <div class="fotoSim mt-3">
                        <p class="fotoSim">Foto Fisik SIM (Optional)</p>
                        <img id="fotoSimKtaImg" class="photo d-none" style="max-width: 50vw; max-height: 50vw;" />
                        <input type="file" name="fotoSim" id="fotoSim" class="photo" accept="photo/*,ocr/*" placeholder="Foto Fisik SIM" />
                        <input type="hidden" id="fotoSimKta" name="fotoSimKta" value="">

                    </div>
                    <div class="fotoPersetujuan mt-3">
                        <p class="fotoPersetujuan">Formulir Persetujuan Orang Tua / Wali (Optional, jika pemohon tidak punya SIM)</p>
                        <input type="file" name="fotoPersetujuan" id="fotoPersetujuan" accept="photo/*,ocr/*" class="photo" placeholder="Formulir Persetujuan Orang Tua / Wali" />
                    </div>
                    <div class="kategoriKis mt-3">
                        <p class="kategoriKis">Kategori KIS</p>
                        <select name="kategoriKis" id="kategoriKis" class="kategoriKis" required>
                            <option value="">- Pilih -</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-3" style="width: 100%; height: 10px; background-color: #e5e5e5"></div>
            <div class="container">
                <div class="form-group-2 mt-4 mb-4">
                    <div class="row">
                        <div class="col-6" style="color: #626262">
                            Mobility Upgrade Fee
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <b>Rp. <?= number_format($upgradeFee, 0, '', '.') ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6" style="color: #626262">
                            Administration Fee
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <b>Rp. <?= number_format($adminFee, 0, '', '.') ?></b>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6" style="color: #626262">
                            Total Payment
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <b style="font-size: 20px">Rp. <?= number_format($upgradeFee+$adminFee, 0, '', '.') ?></b>
                        </div>
                    </div>
                </div>
            </div>

                </div>
                <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>
                <div class="form-submit d-flex justify-content-center" style="margin-top: 20px">
                    <input type="submit" style="width: 40%; font-size: 16px; padding: 10px" name="submit" id="submit" class="submit" value="SUBMIT" />
                </div>
            </form>
        </div>

        <div class="modal fade" id="modalProgress" tabindex="-1" role="dialog" aria-labelledby="modalProgress" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0" id="modalProgress">
                    <p>Upload in progress...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div id="modalSuccess" class="modal"> -->

            <!-- Modal content -->
            <!-- <div class="modal-content">
                <p>Successfully upload data</p>
            </div>

        </div> -->

    <div class="modal fade" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modalSuccess">
                <p>Successfully upload data</p>
                </div>
            </div>
        </div>
    </div>

    </div>

    <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-payment-body">
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
        var REG_TYPE = 1;
        localStorage.setItem('grand-total', 60000);
    </script>
    <script src="../assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script>
    <script src="../assets/js/form-kis.js?v=<?php echo $ver; ?>"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

<script>

$(document).ready(function(e) {


    // $('#kategoriKis').selectize();

});

</script>