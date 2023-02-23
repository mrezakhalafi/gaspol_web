<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbconn = paliolite();

// session_start();
// if(isset($_SESSION['user_f_pin'])){
//   $f_pin = $_SESSION['user_f_pin'];
// }
// else if(isset($_GET['f_pin'])){
  $f_pin = $_GET['f_pin'];
// }

$rand_bg = rand(1, 12) . ".png";

// QUERY
$query = $dbconn->prepare("SELECT * FROM KTA WHERE KTA.F_PIN = '$f_pin'");
$query->execute();
$exec = $query->get_result()->fetch_assoc();
$query->close();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Membership</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
      <style>
        body {
          font-family: 'Poppins', sans-serif;
        }
        .grid-container {
          display: grid;
          grid-template-columns: repeat(1, minmax(0, 1fr));
          padding: 10px;
        }
        .grid-item {
          background-color: #FFFFFF;
          border: 0px;
          padding: 10px;
          font-size: 20px;
          margin: 5px;
          text-align: center;
          border-radius: 15px;
          box-shadow: 2px 2px 4px black;
        }
        .grid-item img {
          max-width: 100px;
          height:auto;
        }
        * {
          box-sizing: border-box;
        }
        html {
          height: 100%;
          /* background-image: url("../assets/img/body-bg.jpeg"); */
          background-repeat: no-repeat;
          background-size: cover;
          background-attachment: fixed;
          -moz-background-size: cover;
          -webkit-background-size: cover;
          -o-background-size: cover;
          -ms-background-size: cover;
          background-position: center center;
        }
        body {
          background-image: url('../assets/img/lbackground_<?php echo $rand_bg; ?>');
          background-size: 100% auto;
          background-repeat: repeat-y;
        }
      </style>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

      <!-- Font Icon -->
      <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">
  </head>

  <body>
    <div class="row gx-0 p-2" style="border-bottom: 2px #e5e5e5 solid">
      <div class="col-1 d-flex justify-content-start">
          <a href="menu_membership.php?f_pin=<?= $f_pin ?>"><img src="../assets/img/icons/Back-(Black).png" alt="" style="height: 36px"></a>
      </div>
      <div class="col-11 d-flex justify-content-center">
          <h2 style="margin-bottom: 0px"></h2>
      </div>
    </div>

    <div class="grid-container">
      <div class="grid-item" id="imi-form">
        <img src="../assets/img/membership_card.png" >
        <h3>IMI Club</h3>
      </div>
      <div class="grid-item" id="gaspol-form">
        <img src="../assets/img/membership_card.png" >
        <h3>Gaspol Club</h3>
      </div>
    </div>
    
    <!-- IF ELSE MODAL -->
    <div class="modal fade" id="kta-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Warning!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You have no registered KTA. Please register!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="form-kta-mobility.php?f_pin=<?= $f_pin ?>"><button type="button" class="btn btn-primary">Register</button></a>
                </div>
            </div>
        </div>
    </div>

    <img onclick="closeAndroid()" src="../assets/img/close.png" style="position: fixed;
        width: 60px;
        height: 60px;
        bottom: 75px;
        right: 20px;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script>
      var F_PIN = "<?php echo $f_pin; ?>";
      
      // CARD KTA
      $("#imi-form").click(function (e) { 
        e.preventDefault();
        window.location.href = "tkt-imi-club?f_pin=".concat(F_PIN)
      });

      // END OF CARD KTA

      $("#gaspol-form").click(function (e) { 
        e.preventDefault();
        window.location.href = "form-tkt-gaspol?f_pin=".concat(F_PIN)
      });

      function closeAndroid(){

        if (window.Android){
          window.Android.finishGaspolForm();
        }else{
          history.back();
        }
      }

    </script>
  </body>
</html>