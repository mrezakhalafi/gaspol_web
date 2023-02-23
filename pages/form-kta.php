<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$f_pin = $_GET['f_pin'];

$dbconn = paliolite();

$ver = time();

$sqlData = "SELECT COUNT(*) as exist
  FROM KTA
  WHERE F_PIN = '$f_pin'";

//   echo $sqlData;

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$resDATA = $queDATA->get_result()->fetch_assoc();
$exist = $resDATA["exist"];
$queDATA->close();

if ($exist > 0) {
    header("Location: /gaspol_web/pages/card-kta?f_pin=$f_pin");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form KTA</title>

    <script src="../assets/js/xendit.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">

    <style>
        .modal {
            z-index: 9999;
        }

        #modal-payment .modal-content{
            margin: 0;
            width: 100%;
        }

    </style>


</head>

<body>

    <div class="main">

        <div class="container">
            <form method="POST" class="main-form" id="kta-form" action="/gaspol_web/logics/register_new_kta" enctype="multipart/form-data">
                <h2>Form KTA (Kartu Tanda Anggota)</h2>
                <div class="form-group-2" style="margin-bottom:10px">
                    <h3 class="styleH3">Foto Fisik E-KTP/KK</h3>
                    <div style="margin-bottom:5px">
                        <input type="radio" id="radioEktpFile" name="ektp_radio" class="radio" value="File" checked>
                        <label for="radioEktpFile">From File</label>
                        <input type="radio" id="radioEktpOcr" name="ektp_radio" class="radio" value="OCR">
                        <label for="radioEktpOcr">Take Photo</label><br>
                    </div>
                    <label for="fotoEktp" id="ektpLabelBtn" class="btn">Choose File</label>
                    <p id="ektpFileName" style="display: inline;">No file chosen</p>
                    <input type="file" style="display:none;" accept="image/*,ocr_file/*" name="fotoEktp" id="fotoEktp" class="photo" placeholder="Foto Fisik E-KTP" required />
                </div>
                <div class="form-group-2">
                    <h3 class="styleH3">Data Anggota</h3>
                    <p>Foto Profil</p>
                    <input type="file" accept="image/*,photo/*,ocr/*" name="fotoProfil" id="fotoProfil" class="photo" placeholder="Foto Profil" required />
                    <input type="text" name="name" id="name" placeholder="Nama Lengkap" required />
                    <input type="text" name="ektp" id="ektp" placeholder="NIK KTP / KK" minlength="16" maxlength="16" required />
                    <input type="text" name="domisili" id="domisili" placeholder="Domisili/Provinsi (Sesuai KTP)" required />
                    <div class="fotoSim">
                        <p class="fotoSim">Foto Fisik SIM (Optional)</p>
                        <input type="file" name="fotoSim" id="fotoSim" class="photo" accept="photo/*,ocr/*" placeholder="Foto Fisik SIM" />
                    </div>
                </div>
                <div class="form-group-2">
                    <p><b>Biaya Pembayaran : Rp. 60,000</b></p>
                </div>
                <div class="form-submit">
                    <input type="submit" name="submit" id="submit" class="submit" value="Submit" />
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
        var REG_TYPE = 2;
        localStorage.setItem('grand-total', 60000);
    </script>
    <script src="../assets/js/membership_payment.js?v=<?php echo $ver; ?>"></script>
    <script src="../assets/js/form-kta.js?v=<?php echo $ver; ?>"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>