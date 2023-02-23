<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$f_pin = $_GET['f_pin'];

$dbconn = paliolite();

$sql = "SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = 2";

$que = $dbconn->prepare($sql);
$que->execute();
$setting = $que->get_result()->fetch_assoc();
$que->close();

$sqlGIF = "SELECT BE_ID, COUNT(BE_ID) AS COUNT_BE FROM XPORA_GIF WHERE BE_ID = 0 OR BE_ID IN (SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin')";
$queGIF = $dbconn->prepare($sqlGIF);
$queGIF->execute();
$resGIF = $queGIF->get_result()->fetch_assoc();
$queGIF->close();

$countGIF = $resGIF["COUNT_BE"];
$be_id = $resGIF["BE_ID"];

$query = $dbconn->prepare("SELECT * FROM CATEGORY WHERE EDUCATIONAL = 5");
$query->execute();
$category_raw = $query->get_result();
$query->close();

$category_arr = array();
while ($cat = $category_raw->fetch_assoc()) {
    $category_arr[] = $cat;
};

?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/tab3-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/c6d7461088.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jQueryRotate.js"></script>
    <script src="../assets/js/jquery.validate.js"></script>
    <script src="../assets/js/isInViewport.min.js?v=<?= time(); ?>"></script>
    <link rel="stylesheet" href="../assets/css/style-store_list.css?random=<?= time(); ?>">
    <link rel="stylesheet" href="../assets/css/gridstack.min.css" />
    <link rel="stylesheet" href="../assets/css/gridstack-extra.min.css" />

    <script type="text/javascript" src="../assets/js/gridstack-static.js"></script>
    <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script>

    <?php
    $rand_bg = rand(1, 12) . ".png";
    ?>

    <style>
        body {
            background: transparent;
        }

        #header-layout {
            background: <?= $setting['COLOR_PALETTE']; ?>;
            z-index: 99;
        }

        form#searchFilterForm-a {
            border: 1px solid #c9c9c9;
            background-color: rgba(255, 255, 255, .55);
            width: 100%;
        }

        input#query {
            background-color: rgba(255, 255, 255, 0);
        }

        .grid-stack>.grid-stack-item>.grid-stack-item-content {
            overflow-y: hidden !important;
        }

        #content-grid {
            margin-top: 175px;
        }

        .float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 75px;
            right: 20px;
            background-color: rgba(0, 0, 0, .65);
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            box-shadow: 2px 2px 3px #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            z-index: 999;
        }

        .my-float {
            /* margin-top: 22px; */
            z-index: 999;
        }

        .content-image {
            object-fit: cover;
        }

        <?php

        $rand_pos = rand(0, 1);

        ?>#gif-container {
            position: fixed;
            z-index: 9999;
        }

        #gif-container.left {
            bottom: 70px;
            left: 20px;
        }

        #gif-container.right {
            bottom: 150px;
            right: 20px;
        }

        .gifs img {
            height: 200px;
            width: auto;
        }

        * {
            -webkit-touch-callout: none !important;
            /* Safari Touch */
            -webkit-user-select: none !important;
            /* Webkit */
        }

        #categoryFilter-body ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #categoryFilter-body input[type="checkbox"] {
            margin: 5px;
        }

        #categoryFilter-body ul ul {
            margin: 0 0 0 30px;
        }

        #categoryFilter-body ul li label {
            font-size: .75rem;
        }
    </style>

</head>


<body class="tab3">
    <img id="scroll-top" class="rounded-circle" src="../assets/img/ic_collaps_arrow.png" onclick="topFunction(true)">
    <div class="container-fluid px-0">
        <div id="header-layout" class="sticky-top">
            <div id="story-container">
                <?php require('timeline_story_container_grid.php'); ?>
            </div>
            <div id="header" class="row justify-content-between">
            <div class="col-12">
          <div id="searchFilter-a" class="col-12 d-flex align-items-center justify-content-center text-white">
            <form id="searchFilterForm-a" method=GET>
              <!-- <div class="d-flex align-items-center div-search"> -->
              <?php
              $query = "";
              if (isset($_REQUEST['query'])) {
                $query = $_REQUEST['query'];
              }
              ?>
              <input id="query" type="text" class="search-query" name="query" onclick="onFocusSearch()" value="<?= $query; ?>">
              <script>
                if (localStorage.lang == 0) {
                  // $('input#query').attr('placeholder', 'Search');
                  document.getElementById('query').placeholder = "Search";
                } else {
                  document.getElementById('query').placeholder = "Pencarian";
                }
              </script>
              <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).png">
              <img id="voice-search" src="../assets/img/icons/Voice-Command-(Black).png" onclick="voiceSearch();">
              <!-- </div> -->

            </form>

          </div>
        </div>
        <div id="gear-div" class="col-1 d-none align-items-center justify-content-center" style="padding-right: 9px; padding-left: 9px;">
          <img class="header-icon me-3" id="toggle-filter" src="../assets/img/icons/Add-(Grey).png">
          <a class="me-1 d-none" id="to-grid-layout">
            <div class="position-relative">
              <img class="header-icon" src="../assets/img/ic_grid.png">
              <span id='counter-here' class="d-none"></span>
            </div>
          </a>
          <a class="me-3 d-none" id="to-list-layout">
            <div class="position-relative">
              <img class="header-icon mx-auto" src="../assets/img/ic_list.png">
              <span id='counter-notifs' class="d-none"></span>
            </div>
          </a>
        </div>
            </div>
            <div id="category-tabs" class="ms-2 small-text d-none">
                <ul class="nav nav-tabs horizontal-slide" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="categoryFilter-all" data-bs-toggle="tab" role="tab">All</a>
                    </li>
                    <?php

                    $filters = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_products_category.php');

                    for ($i = 0; $i < count($filters); $i++) {

                        $idFilter = $filters[$i]["ID"];
                        $nameFilter = $filters[$i]["CODE"];
                        echo '<li class="nav-item">';

                        echo '<a class="nav-link" id="categoryFilter-' . $idFilter . '" data-bs-toggle="tab" role="tab">' . $nameFilter . '</a>';
                        echo '</li>';
                    }

                    ?>
                </ul>
            </div>
        </div>
        <a id="to-new-post" class="float d-none">
            <img src="../assets/img/plus-white.png" style="width:17px; height:auto;">
        </a>
        <script>
            // if (window.Android && typeof window.Android.checkFeatureAccessSilent === "function" && !window.Android.checkFeatureAccessSilent("new_post")) {
            //     //   let test = false;
            //     // if(!test) {
            //     $('#to-new-post').addClass('d-none');
            // } else {
            //     $('#to-new-post').removeClass('d-none');
            // }
        </script>
    </div>
    <div class="box">
        <div id="container">
            <div id="loading" class="d-none">
                <div class="col-sm mt-5">
                    <h5 class="prod-name" style="text-align:center;">Sedang memuat. Tunggu sebentar...</h5>
                </div>
            </div>
            <div class="d-none" id="no-stores">
                <div class="col-sm mt-5">
                    <h5 class="prod-name" style="text-align:center; margin-top:175px;">Nothing matches your criteria</h5>
                </div>
            </div>
            <div id="content-grid" class="grid-stack grid-stack-3" style="inset: -1px;">
                <div id="grid-overlay" class="overlay d-none"></div>
            </div>
        </div>
        <script>
            const search = <?php if (isset($_GET['query'])) {
                                echo '"' . $_GET['query'] . '"';
                            } else {
                                echo "null";
                            } ?>;
            const filter = <?php if (isset($_GET['filter'])) {
                                echo '"' . $_GET['filter'] . '"';
                            } else {
                                echo "null";
                            } ?>;
        </script>
    </div>
    <div class="bg-grey stack-top" style="display: none;" id="stack-top">
        <div class="container small-text">
            <div id="sort-store-popular" class="bg-white row py-3">
                <div class="col-6" style="font-weight:500;">Popular</div>
                <div class="col-6 check-mark">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
            <div id="sort-store-date" class="bg-white row py-3" style="margin-top: 1px;">
                <div class="col-6" style="font-weight:500;">Date Added (New to Old)</div>
                <div class="col-6 check-mark d-none">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
            <div id="sort-store-follower" class="bg-white row py-3" style="margin-top: 1px;">
                <div class="col-6" style="font-weight:500;">Followers</div>
                <div class="col-6 check-mark d-none">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
        </div>
    </div>
    <!-- FOOTER -->

    <div class="modal fade" id="modal-addtocart" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content animate-bottom">
                <div class="modal-body p-0" id="modal-add-body" style="position: relative;">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-categoryFilter" tabindex="-1" role="dialog" aria-labelledby="modal-categoryFilter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Select category
                </div>
                <div class="modal-body" id="categoryFilter-body" style="position: relative;">
                    <ul>
                        <?php foreach ($category_arr as $category) { ?>
                            <li id="item-<?= $category['ID'] ?>">
                                <input type="checkbox" id="<?= $category['ID'] ?>" name="item-<?= $category['ID'] ?>" />
                                <label for="<?= $category['ID'] ?>"><?= $category['CODE'] ?></label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark" type="button" id="submitCategory">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-category" tabindex="-1" role="dialog" aria-labelledby="modal-category" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content animate-bottom">
                <div class="modal-body p-4" id="modal-add-body" style="position: relative;">

                    <div class="row gx-0">
                        <div class="col-12">
                            <div class="col-12 mb-3 text-center">
                                <h5>Why you want to report this content?</h5>
                            </div>
                            <div class="col-12" style="float: left; font-size: 16px">
                                <ul>
                                    <form action="/action_page.php">

                                        <?php

                                        $query = $dbconn->prepare("SELECT * FROM REPORT_CATEGORY");
                                        $query->execute();
                                        $category = $query->get_result();
                                        $query->close();

                                        foreach ($category as $c) : ?>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="report_category" id="report_category<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" <?= $c['ID'] == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="report_category<?= $c['ID'] ?>">
                                                    <?= $c['CATEGORY'] ?>
                                                </label>
                                            </div>


                                        <?php endforeach;

                                        ?>

                                        <!-- <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category1" value="0">
                                        <label class="form-check-label" for="report_category1">
                                        It's a scam
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category2" value="1">
                                        <label class="form-check-label" for="report_category2">
                                        Nudity or sexual activity
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category3" value="2">
                                        <label class="form-check-label" for="report_category3">
                                        Hate speech or symbols
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category3" value="3">
                                        <label class="form-check-label" for="report_category3">
                                        Bullying or harassment
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category4" value="4">
                                        <label class="form-check-label" for="report_category4">
                                        Violence or dangerous organization
                                        </label>
                                    </div> -->

                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-center">
                                                <button class="btn btn-dark" type="button" onclick="reportContentSubmit()">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-category2" tabindex="-1" role="dialog" aria-labelledby="modal-category2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content animate-bottom">
                <div class="modal-body p-4" id="modal-add-body" style="position: relative;">

                    <div class="row gx-0">
                        <div class="col-12">
                            <div class="col-12 mb-3 text-center">
                                <h5>Why you want to report this user?</h5>
                            </div>
                            <div class="col-12" style="float: left; font-size: 16px">
                                <ul>
                                    <form action="/action_page.php">


                                        <?php

                                        $query = $dbconn->prepare("SELECT * FROM REPORT_CATEGORY");
                                        $query->execute();
                                        $category = $query->get_result();
                                        $query->close();

                                        foreach ($category as $c) : ?>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="report_category" id="report_category<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" <?= $c['ID'] == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="report_category<?= $c['ID'] ?>">
                                                    <?= $c['CATEGORY'] ?>
                                                </label>
                                            </div>


                                        <?php endforeach;

                                        ?>

                                        <!-- <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category1" value="0">
                                        <label class="form-check-label" for="report_category1">
                                        It's a scam
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category2" value="1">
                                        <label class="form-check-label" for="report_category2">
                                        Nudity or sexual activity
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category3" value="2">
                                        <label class="form-check-label" for="report_category3">
                                        Hate speech or symbols
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category3" value="3">
                                        <label class="form-check-label" for="report_category3">
                                        Bullying or harassment
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="report_category" id="report_category4" value="4">
                                        <label class="form-check-label" for="report_category4">
                                        Violence or dangerous organization
                                        </label>
                                    </div> -->

                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-center">
                                                <button class="btn btn-dark" type="button" onclick="reportUserSubmit()">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-report-success" tabindex="-1" aria-labelledby="modal-report-success" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-4 text-center">
                    <p style="font-size: 16px">Report submited.</p>
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <button class="btn btn-dark" type="button" onclick="reloadPages()">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-block-success" tabindex="-1" aria-labelledby="modal-report-success" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-4 text-center">
                    <p style="font-size: 16px">You blocked this person.</p>
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <button class="btn btn-dark" type="button" onclick="reloadPages()">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($countGIF > 0) { ?>
        <div id="gif-container" class="<?php echo $rand_pos == 1 ? "right" : "left" ?>">

        </div>

    <?php } ?>

    <!-- show product modal -->
    <div class="modal fade" id="modal-product" tabindex="-1" aria-labelledby="modal-product" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body p-0"></div>
            </div>
        </div>
    </div>
    <!-- show product modal -->

</body>

<!-- <script type="text/javascript" src="../assets/js/script-filter.js?random=<?= time(); ?>"></script> -->
<script src="https://apis.google.com/js/api.js" defer></script>
<!-- <script src="../assets/js/update_counter.js?random=<?= time(); ?>"></script> -->
<script src="../assets/js/tab5-collection.js?r=<?= time(); ?>"></script>
<script src="../assets/js/long-press-event.min.js?random=<?= time(); ?>"></script>
<script type="text/javascript" src="../assets/js/script-store_list.js?random=<?= time(); ?>" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script>
    localStorage.setItem("is_grid", "1");

    function myFunction() {
        var x = document.getElementById("stack-top");
        if (x.style.display === "none") {
            x.style.display = "block";
            $('#grid-overlay').removeClass('d-none');
        } else {
            x.style.display = "none";
            $('#grid-overlay').addClass('d-none');
        }
    }

    if (localStorage.lang == 0) {
        $('input#query').attr('placeholder', 'Search');
        $('#no-stores .prod-name').text('Nothing matches your criteria');
    } else {
        $('input#query').attr('placeholder', 'Pencarian');
        $('#no-stores .prod-name').text('Tidak ada toko yang sesuai dengan kriteria');
    }

    $(document).ready(function() {
        $('#to-new-post').click(function() {
            if (window.Android) {
                if (window.Android.checkProfile()) {
                    window.location = "tab5-new-post?f_pin=" + window.Android.getFPin();
                }
            } else {
                let fpin = new URLSearchParams(window.location.search).get("f_pin");
                window.location = "tab5-new-post?f_pin=" + fpin;
            }
        })
    })

    function openNewPost(checkIOS = false) {
        if (window.Android) {
            if (typeof window.Android.checkFeatureAccess === "function" && window.Android.checkFeatureAccess("new_post") && window.Android.checkProfile()) {
                window.location = "tab5-new-post?f_pin=" + window.Android.getFPin();
            }
        } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
            window.webkit.messageHandlers.checkProfile.postMessage({
                param1: '',
                param2: 'newpost'
            });
            return;
        } else {
            let fpin = new URLSearchParams(window.location.search).get("f_pin");
            window.location = "tab5-new-post?f_pin=" + fpin;
        }
        localStorage.setItem("is_grid", "0");
    }



    $(document).ready(function() {
        $('#to-new-post').click(function() {
            openNewPost();

        })


    })
</script>

</html>