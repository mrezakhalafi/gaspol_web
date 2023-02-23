<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$f_pin = $_GET['f_pin'];

$dbconn = paliolite();

$ver = time();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form E-SIM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">
</head>

<body>

    <div class="main" style="padding: 0px">

        <div>
            <form method="POST" class="main-form" id="e-sim-form" action="/gaspol_web/logics/register_new_esim" enctype="multipart/form-data">
            <div class="row gx-0 p-2" style="border-bottom: 2px #e5e5e5 solid; background-image: url(../assets/img/lbackground_2.png)">
                <div class="col-1 d-flex justify-content-start">
                    <img onclick="closeAndroid()" src="../assets/img/icons/Back-(Black).png" alt="" style="height: 36px">
                </div>
                <div class="col-11 d-flex justify-content-center">
                    <h2 style="margin-bottom: 0px">Form E-SIM</h2>
                </div>
            </div>

            <!-- <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div> -->

            <div class="container pt-4">
                <h2 class="text-center"><span style="font-size: 22px">Formulir <span style="color: #f66701">E-SIM</span></span><br><span style="font-size: 16px; color: #626262">(Surat Izin Mengemudi Elektronik)</span></h2>
            </div>

            <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>
            
            <div class="container mt-3">
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
                    <span class="star-pp text-danger" style="position: absolute; margin-top: 7px; margin-left: 1px">*</span>
                </div>
                <div class="form-group-1">
                    <h3 class="styleH3">Data E-KTP</h3>
                    <input type="text" name="ektp" id="ektp" placeholder="NIK" minlength="16" maxlength="16" required />
                    <label id="ektp-error" class="error" for="ektp"></label>

                    <span class="star-e-ktp text-danger" style="position: absolute; margin-top: -41px; margin-left: 21px">*</span>

                </div>
                <div class="form-group-2">
                    <h3 class="styleH3">Data SIM</h3>
                    <p>Jenis Permohonan</p>
                    <select name="simRequest" id="simRequest">
                        <option value="1" selected>SIM Baru</option>
                        <option value="2">Perpanjangan SIM</option>
                    </select>
                    <p>Jenis SIM</p>
                    <select name="confirm_type" id="confirm_type">
                        <option value="A">SIM A (Kendaraan Roda 4)</option>
                        <option value="C">SIM C (Kendaraan Roda 2)</option>
                    </select>
                </div>
                <div class="form-group-2 baru">
                    <input ype="text" name="name" id="name" placeholder="Nama" required />
                    <span class="people-name text-danger" style="position: absolute; margin-top: -41px; margin-left: 41px">*</span>

                    <input type="text" name="placeOfBirth" id="placeOfBirth" placeholder="Tempat Lahir" required />
                    <span class="placebirth text-danger" style="position: absolute; margin-top: -40px; margin-left: 87px;">*</span>

                    <p>Tanggal Lahir</p>
                    <input type="date" name="dateOfBirth" id="dateOfBirth" placeholder="Tanggal Lahir" required />
                    <span class="text-danger" style="position: absolute; margin-top: -43px; margin-left: 88px;">*</span>

                    <p>Golongan Darah</p>
                    <select name="bloodType" id="bloodType">
                        <option value="A" selected>A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                    <select name="gender" id="gender" placeholder="Jenis Kelamin" required>
                        <option value="PRIA" selected>Laki-Laki</option>
                        <option value="WANITA">Perempuan</option>
                    </select>
                </div>
                <div class="form-group-2 perpanjangan">
                    <input type="text" name="noSim" id="noSim" placeholder="Nomor SIM" />
                    <label id="noSim-error" class="error" for="noSim"></label>
                    <span class="nosim text-danger" style="position: absolute; margin-top: -41px; margin-left: 71px;">*</span>

                </div>
                <div class="form-group-2">
                    <input type="text" name="address" id="address" placeholder="Alamat" required />
                    <span class="alamat text-danger" style="position: absolute; margin-top: -40px; margin-left: 49px;">*</span>

                    <input type="text" name="occupation" id="occupation" placeholder="Pekerjaan" required />
                    <span class="pekerjaan text-danger" style="position: absolute; margin-top: -41px; margin-left: 65px;">*</span>
                </div>
                <div class="form-group-2">
                    <h3 class="styleH3">Unggah Dokumen</h3>
                    <div class="fotoSim">
                        <p class="fotoSim">Foto Fisik SIM</p>
                        <input type="file" name="fotoSim" id="fotoSim" class="photo" placeholder="Foto Fisik SIM" />
                        <span class="starfotosim text-danger" style="position: absolute; margin-top: -79px; margin-left: 84px">*</span>

                    </div>
                    <p>Foto Tanda Tangan</p>
                    <input type="radio" id="radioFile" name="ttd_radio" class="radio" value="File" checked>
                    <label for="radioFile">File</label>
                    <input type="radio" id="radioCanvas" name="ttd_radio" class="radio" value="Canvas">
                    <label for="radioCanvas">Canvas</label>
                    <div class="uploadTtdFile">
                        <input type="file" name="fotoTtd" id="fotoTtd" class="photo" placeholder="Foto Tanda Tangan" />
                        <span class="star-canvas text-danger" style="position: absolute; margin-top: -43px; margin-left: 185px">*</span>
                    </div>
                    <div class="uploadTtdCanvas">
                        <canvas id="canvasSignature" width="325px" height="300px" style="border:2px solid #000000;"></canvas><br>
                        <button type="button" name="clearTtd" id="clearTtd" onclick="clearCanvas()">Clear</button>
                    </div>
                    <p>Pas Foto</p>
                    <input type="file" name="pasFoto" id="pasFoto" class="photo" placeholder="Pas Foto" required />
                    <span class="pas-foto text-danger" style="position: absolute; margin-top: -80px; margin-left: 55px">*</span>
                </div>
                <!-- <div class="form-check">
                    <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                    <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree to the  <a href="#" class="term-service">Terms and Conditions</a></label>
                </div> -->
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

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
    </script>
    <script src="../assets/js/form-e-sim.js?v=<?php echo $ver; ?>"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

<script>
    $(".star-pp").show();
    $(".star-e-ktp").show();
    $(".people-name").show();
    $(".placebirth").show();
    $(".nosim").show();
    $(".alamat").show();
    $(".pekerjaan").show();
    $(".starfotosim").show();
    $(".star-canvas").show();
    $(".pas-foto").show();

    $("#ektp").bind("change paste keyup", function() {
        var valnik = $(this).val();

        if (valnik) {
            $(".star-e-ktp").hide();
        }

        else {
            $(".star-e-ktp").show();
        }
    });

    $("#fotoEktp").change(function() {
        var valfotoktp = $(this).val();

        if (valfotoktp) {
            $(".star-pp").hide();
        }

        else {
            $(".star-pp").show();
        }
    });

    $("#name").bind("change paste keyup", function() {
        var valname = $(this).val();

        if (valname) {
            $(".people-name").hide();
        }

        else {
            $(".people-name").show();
        }
    });

    $("#placeOfBirth").bind("change paste keyup", function() {
        var valbirth = $(this).val();

        if (valbirth) {
            $(".placebirth").hide();
        }

        else {
            $(".placebirth").show();
        }
    });

    $("#noSim").bind("change paste keyup", function() {
        var valsim = $(this).val();

        if (valsim) {
            $(".nosim").hide();
        }

        else {
            $(".nosim").show();
        }
    });

    $("#address").bind("change paste keyup", function() {
        var valadd = $(this).val();

        if (valadd) {
            $(".alamat").hide();
        }

        else {
            $(".alamat").show();
        }
    });

    $("#occupation").bind("change paste keyup", function() {
        var valjob = $(this).val();

        if (valjob) {
            $(".pekerjaan").hide();
        }

        else {
            $(".pekerjaan").show();
        }
    });

    $("#fotoSim").change(function() {
        var valfotosim = $(this).val();

        if (valfotosim) {
            $(".starfotosim").hide();
        }

        else {
            $(".starfotosim").show();
        }
    });

    $("#fotoTtd").change(function() {
        var valfotottd = $(this).val();

        if (valfotottd) {
            $(".star-canvas").hide();
        }

        else {
            $(".star-canvas").show();
        }
    });

    $('#canvasSignature').on('mouseup', function() {
        
        alert();
        var valcanvas = $(this).val();

        if (valcanvas) {
            $(".star-canvas").hide();
        }

        else {
            $(".star-canvas").show();
        }
    });

    $("#pasFoto").change(function() {
        var valpasfoto = $(this).val();

        if (valpasfoto) {
            $(".pas-foto").hide();
        }

        else {
            $(".pas-foto").show();
        }
    });

    function closeAndroid(){

        if (window.Android){

        window.Android.finishGaspolForm();

        }else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishGaspolForm) {

        window.webkit.messageHandlers.finishGaspolForm.postMessage({
            param1: ""
        });
        return;

        }else{

        history.back();

        }
    }
    
</script>