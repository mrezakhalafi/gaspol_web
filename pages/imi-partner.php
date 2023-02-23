<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbconn = paliolite();

if(isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
    $_SESSION['user_f_pin'] = $f_pin;
}
else if(isset($_SESSION['user_f_pin'])){
    $f_pin = $_SESSION['user_f_pin'];
}

// $sql = "SELECT * FROM IMI_PARTNERS";
// $query = $dbconn->prepare($sql);
// $query->execute();
// $result = $query->get_result();
// $query->close();

// $partners = array();
// while ($partner = $result->fetch_assoc()) {
//     $partners[] = $partner;
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMI Partners</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <style>
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
            font-family: 'Poppins';
            font-size: .8rem;
        }

        .partners {
            position: relative;
        }

        .partners:after {
            content: "";
            display: block;
            border-bottom: 1px solid #ccc;
            position: absolute;
            bottom: 0;
            left: 15px;
            right: 15px;
        }

        .partner-logo {
            width: 50px;
            height: auto;
        }

        .partner-name {
            font-weight: 700;
            margin-bottom: .25rem;
            font-size: 15px;
        }

        .partner-type {
            color: #12A200;
            margin-bottom: .25rem;
        }

        .truncate {
            display: inline-block;
            width: 255px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .truncate+a {
            display: inline-block;
            vertical-align: top;
            color: #ff6b00 !important;
            text-decoration: none;
        }

        .modalMenu .modal-dialog {
            position: fixed;
            top: auto;
            right: auto;
            left: auto;
            bottom: 0;
            margin: 0;
            padding: 0;
            width: inherit;
        }

        .modalMenu .modal-content {
            min-height: 350px;
            border: none;
            border-radius: 1rem 1rem 0 0;
        }

        .modalMenu .modal-body {
            font-size: 12px;
            line-height: 1.8;
        }

        .closeModal {
            border: 1px solid black;
            background-color: white;
            color: black;
            border-radius: 20px;
            width: 100%;
            text-align: center;
            padding: .8rem;
        }
    </style>
</head>

<body class="bg-light invisible">

    <div class="container-fluid bg-white" id="header">
        <div class="row py-3 px-2 align-items-center">
            <div class="col-2 pe-0">
                <a onclick="window.history.back();">
                    <img src="../assets/img/back_arrow.png" alt="" style="width: 30px; height: 30px">
                </a>
            </div>
            <div class="col-8 ps-0">
                <h6 id="title-main" class="mb-0" style="font-weight: 700; font-size:15px">IMI Partner</h6>
            </div>
            <div class="col-2">
                <a href="gaspol_search.php?f_pin=<?= $f_pin ?>">
                    <img src="../assets/img/tab5/search-black.png" alt="" style="width: 30px; height: 30px">
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-light mt-4" id="partner-list">

    </div>

    <div class="modal fade modalMenu" id="modalReport" tabindex="-1" aria-labelledby="modalReportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <div style="margin: 10px; width: 100px; height: 3px; background-color: #e0e0e0; border-radius: 20px"></div>
                    </div>
                </div>
                <div class="modal-body m-4">
                </div>
                <div class="modal-footer py-4">
                    <div class="row w-100">
                        <div class="col-12">
                            <button type="btn" class="closeModal" data-bs-dismiss="modal">
                                <h6 class="mb-0"><strong>Close</strong></h6>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

<script>

    if (localStorage.lang == 1){

        $('#title-main').text('Mitra IMI');
        
    }

    let partners = [];

    function getPartners() {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                partners = JSON.parse(xmlHttp.responseText);
                console.log(partners);
                drawPartners(partners);
            }
        }
        xmlHttp.open("get", "/gaspol_web/logics/fetch_imi_partners");
        xmlHttp.send();
    }

    function drawPartners(arr) {
        arr.forEach(pt => {
            let html = `
            <div class="row py-3 partners" id="partner-${pt.ID}">
                <div class="col-2 pe-0 text-center">
                    <img class="partner-logo" src="../images/${pt.IMAGE}">
                </div>
                <div class="col-10" onclick="expandText(${pt.ID});">
                    <h5 class="partner-name">${pt.NAME}</h5>
                    <p class="partner-type">${pt.TYPE}</p>
                    <span class="partner-desc" id="partnerdesc-${pt.ID}">${pt.DESCRIPTION}</span> <a class="d-none" id="expanddesc-${pt.ID}">view more</a>
                </div>
            </div>
            `;

            $('#partner-list').append(html);
        })

        checkDescLength();
    }

    function checkDescLength() {
        $('.partner-desc').each(function() {
            if ($(this).width() > 250) {
                $(this).toggleClass('truncate');
                let id = $(this).attr('id').split('-')[1];
                $('a#expanddesc-' + id).removeClass('d-none');
            }
        })
        $('body').removeClass('invisible');
    }

    function expandText(id) {
        let partner = partners.find(pt => pt.ID == id);
        let partnerDesc = partner.DESCRIPTION.split('|');
        console.log(partnerDesc);
        let descText = `<ul>`;
        partnerDesc.forEach(pd => {
            descText += `
                <li>
                    ${pd}
                </li>
            `;
        })
        descText += '</ul>';
        let partnerContent = `
        <div class="row">
            <div class="col-12">
                <h4><strong>${partner.NAME}</strong></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                ${descText}
            </div>
        </div>
        `

        $('#modalReport .modal-body').html(partnerContent)
        $('#modalReport').modal('show');
    }

    window.onload = () => {
        getPartners();
        // checkDescLength();

        $('#modalReport').on('show.bs.modal', function() {
            if (window.Android) {
                window.Android.tabShowHide(false);
            }
        })

        $('#modalReport').on('hide.bs.modal', function() {
            if (window.Android) {
                window.Android.tabShowHide(true);
            }
        })
    }
</script>