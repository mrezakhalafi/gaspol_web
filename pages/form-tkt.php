<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$f_pin = $_GET['f_pin'];
$uid = "";
$dbconn = paliolite();

$sql = "SELECT * FROM PROVINCE ORDER BY PROV_NAME ASC";

$query = $dbconn->prepare($sql);
$query->execute();
$province = $query->get_result();
$query->close();
$sqlData = "SELECT CLUB_UID
  FROM TKT
  WHERE F_PIN = '$f_pin'";
$query = $dbconn->prepare($sqlData);
$query->execute();
$d = $query->get_result()->fetch_assoc();
if (!is_null($d)) {
    $uid = $d['CLUB_UID'];
}
$query->close();
$ver = time();

// PRICE

$sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '4'";

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
    <title>Form IMI</title>

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

            <form method="POST" class="main-form" id="tkt-form" action="/gaspol_web/logics/register_new_tkt" enctype="multipart/form-data">
                    
                <div class="row gx-0 p-2" style="border-bottom: 2px #e5e5e5 solid">
                    <div class="col-1 d-flex justify-content-start">
                        <a href="menu_membership.php?f_pin=<?= $f_pin ?>"><img src="../assets/img/icons/Back-(Black).png" alt="" style="height: 36px"></a>
                    </div>
                    <div class="col-11 d-flex justify-content-center">
                        <h2 style="margin-bottom: 0px">Pembuatan TKT</h2>
                    </div>
                </div>

                <!-- <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div> -->

                <div class="container pt-4">
                    <h2 class="text-center"><span style="font-size: 22px">Keanggotaan <span style="color: #4966b1">IMI Club</span></span><br><span style="font-size: 16px; color: #626262">(Tanda Klub Terdaftar)</span></h2>
                </div>

                <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>

                <div class="form-group-2 mt-3">
                    <div class="container">
                    <b style="font-size: 18px">Data Klub</b>
                    <input type="text" name="name" id="name" placeholder="Nama Klub *" required />
                    <!-- <p>Provinsi</p> -->
                    <select name="province" id="province" class="mt-3 mb-3" required>
                        <option value="">Pilih Provinsi</option>
                        <?php
                        foreach ($province as $value) {
                            echo '<option value="' . $value['PROV_NAME'] . '">' . $value['PROV_NAME'] . '</option>';
                        }
                        ?>
                    </select>
                    <b style="font-size: 18px">Kategori Klub</b><br>
                    <input type="checkbox" class="check mt-2" id="cat1" name="cat1" value="1">
                    <label for="cat1">&nbsp;&nbsp;&nbsp;Olahraga</label><br>
                    <input type="checkbox" class="check" id="cat2" name="cat2" value="2">
                    <label for="cat2">&nbsp;&nbsp;&nbsp;Hobi</label><br>
                    <input type="checkbox" class="check" id="cat3" name="cat3" value="3">
                    <label for="cat3">&nbsp;&nbsp;&nbsp;Penyelenggara</label><br>
                </div>
                <div class="container mt-3">
                    <div class="form-group-2">
                        <b style="font-size: 18px">Data Pengurus Klub</b>
                        <input type="text" name="ketua" id="ketua" placeholder="Ketua *" required />
                        <input type="text" name="wakil" id="wakil" placeholder="Wakil *" required />
                        <input type="text" name="sekretaris" id="sekretaris" placeholder="Sekretaris *" required />
                        <input type="text" name="bendahara" id="bendahara" placeholder="Bendahara *" required />
                        <input type="text" name="admin" id="admin" placeholder="Admin *" required />
                        <input type="text" name="hrd" id="hrd" placeholder="HRD" />
                    </div>
                    <div class="form-group-2 mt-3">
                        <b style="font-size: 18px">Unggah Dokumen</b>
                        <p class="mt-3">AD/ART (File PDF)</p>
                        <input type="file" accept="application/pdf" name="adArt" id="adArt" class="doc" required />
                        <p class="mt-3">Akta Pendirian Perkumpulan (File PDF)</p>
                        <input type="file" accept="application/pdf" name="aktaPP" id="aktaPP" class="doc" />
                    </div>
                </div>
                    <!-- <div class="form-group-2">
                        <p><b>Biaya Pembayaran : Rp. 200,000<b></p>
                    </div> -->
                    <div style="width: 100%; height: 10px; background-color: #e5e5e5" class="mt-4"></div>
                    <div class="container">
                        <div class="form-group-2 mt-4 mb-4">
                            <div class="row">
                                <div class="col-6" style="color: #626262">
                                    Registration Fee
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
                <div style="width: 100%; height: 100px; background-color: #fff"></div>
            </div>
        </form>
    </div>

        <!-- The Modal -->
        <div id="modalProgress" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <p>Upload in progress...</p>
            </div>

        </div>

        <div id="modalSuccess" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <p>Successfully upload data</p>
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
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
        var UID = "<?php echo $uid; ?>";
        var REG_TYPE = 4;
        localStorage.setItem('grand-total', <?= $upgradeFee+$adminFee ?>);
    </script>
    <script src="../assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script>
    <script src="../assets/js/form-tkt.js?v=<?php echo $ver; ?>"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

<script>

$(document).ready(function(e) {

    $('#province').selectize();

});

</script>