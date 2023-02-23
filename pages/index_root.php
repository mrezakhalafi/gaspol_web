<?php

// ini_set('display_errors', 1); 
// ini_set('display_startup_errors', 1); 
// error_reporting(E_ALL);

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

$query = $dbconn->prepare("SELECT * FROM GASPOL_NEWS LIMIT 3");
$query->execute();
$news = $query->get_result();
$query->close();

session_start();
$_SESSION['web_login'] = null;
$_SESSION['is_scanned'] = null;
$_SESSION['f_pin'] = null;

?>

<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="description" content="Gaspol One page parallax responsive HTML Template ">

  <meta name="author" content="Themefisher.com">

  <title>Gaspol! | Official Website</title>

  <!-- Mobile Specific Meta
  ================================================== -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS
  ================================================== -->
  <!-- Themefisher Icon font -->
  <link rel="stylesheet" href="gaspol_web/pages/gaspol-landing/plugins/themefisher-font/style.css">
  <!-- bootstrap.min css -->
  <link rel="stylesheet" href="gaspol_web/pages/gaspol-landing/plugins/bootstrap/css/bootstrap.min.css">
  <!-- Lightbox.min css -->
  <link rel="stylesheet" href="gaspol_web/pages/gaspol-landing/plugins/lightbox2/dist/css/lightbox.min.css">
  <!-- animation css -->
  <link rel="stylesheet" href="gaspol_web/pages/gaspol-landing/plugins/animate/animate.css">
  <!-- Slick Carousel -->
  <link rel="stylesheet" href="gaspol_web/pages/gaspol-landing/plugins/slick/slick.css">
  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="gaspol_web/pages/gaspol-landing/css/style.css">

  <link rel="stylesheet" href="gaspol_web/pages/gaspol-landing/css/jquery-ui.css?v=4">
  <link rel="stylesheet" href="gaspol_web/pages/gaspol-landing/css/paliobutton.css?v=910928953">
  <link rel="stylesheet" href="gaspol_web/pages/gaspol-landing/css/paliopay.css?v=4">

  <style>
    .privacy-area {
      font-size: 12px;
      line-height: 1;
      color: #888888;
      font-weight: 400;
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-top: 7px;
      margin-bottom: 3px;
    }
  </style>

</head>

<body id="body">

  <!--
Fixed Navigation
==================================== -->
  <header class="navigation fixed-top">
    <div class="container">
      <!-- main nav -->
      <nav class="navbar navbar-expand-lg navbar-light">
        <!-- logo -->
        <a class="navbar-brand logo" href="index.php">
          <img class="logo-default" style="width:210px" src="gaspol_web/pages/gaspol-landing/assets/img/logo_gaspol.svg" alt="logo" />
          <img class="logo-white" style="width:210px" src="gaspol_web/pages/gaspol-landing/assets/img/logo_gaspol.svg" alt="logo" />
        </a>
        <!-- /logo -->
        <button class="navbar-toggler" style="background-color: darkorange" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navigation">
          <ul class="navbar-nav ml-auto text-center">
            <li class="nav-item dropdown active">
              <a class="nav-link" href="index.php">
                Homepage
              </a>
              <!-- <div class="dropdown-menu" aria-labelledby="navbarDropdown"> -->
              <!-- <a class="dropdown-item" href="index.html">Homepage</a>
              <a class="dropdown-item" href="onepage-slider.html">Onepage</a>
              <a class="dropdown-item" href="onepage-text.html">Onepage 2</a> -->
              <!-- </div> -->
            </li>
            <li class="nav-item ">
              <a class="nav-link" href="gaspol_web/pages/gaspol-landing/about.php">About Gaspol</a>
            </li>
            <li class="nav-item " id="menu-membership">
              <a class="nav-link" id="membership-link" href="gaspol_web/pages/gaspol-landing/membership.php">Menu Membership</a>
            </li>
            <li class="nav-item " id="faq">
              <a class="nav-link" id="faq-link" href="gaspol_web/pages/gaspol-landing/faq.php">FAQ</a>
            </li>
            <li class="nav-item" style="margin-top: -10px" id="menu-sign-in">
              <a class="nav-link" href="https://qmera.io/chatcore/pages/login_page?env=2">
                <div class="btn btn-main">SIGN IN</div>
              </a>
            </li>
            <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Pages
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="404.html">404 Page</a>
              <a class="dropdown-item" href="blog.html">Blog Page</a>
              <a class="dropdown-item" href="article.php">Blog Single Page</a>
            </div>
          </li> -->
          </ul>
        </div>
      </nav>
      <!-- /main nav -->
    </div>
  </header>
  <!--
End Fixed Navigation
==================================== -->


  <div class="hero-slider">
    <div class="slider-item th-fullpage hero-area" style="background-image: url(gaspol_web/pages/gaspol-landing/assets/img/Collage.png);">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <h1 data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".1">Temukan Komunitas, <br>
              Aktivitas dan Pertemanan</h1>
            <p data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".5">Hobi dan kegemaran anda dalam <br> kendaraan bermotor dalam <br> satu aplikasi.</p>
            <a id="join-club-1" data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".8" class="btn btn-main" href="gaspol_web/pages/gaspol-landing/membership.php">Pembuatan KTA, KIS, TKT, TAA</a>
            <!-- <a id="join-club-3" data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".8" class="btn btn-main" href="gaspol_web/pages/menu_membership">Pembuatan KTA, KIS, TKT, TAA</a> -->
          </div>
        </div>
      </div>
    </div>
    <div class="slider-item th-fullpage hero-area" style="background-image: url(gaspol_web/pages/gaspol-landing/assets/img/Collage.png);">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <h1 data-duration-in=".3" data-animation-in="fadeInDown" data-delay-in=".1">Ayo bergabung dengan<br> komunitas IMI Indonesia</h1>
            <p data-duration-in=".3" data-animation-in="fadeInDown" data-delay-in=".5">Daftar KTA dan jadi anggota komunitas
              <br> otomotif terbesar di dunia.
            </p>
            <a id="join-club-2" data-duration-in=".3" data-animation-in="fadeInDown" data-delay-in=".8" class="btn btn-main" href="gaspol_web/pages/gaspol-landing/membership.php">Pembuatan KTA, KIS, TKT, TAA</a>
            <!-- <a id="join-club-4" data-duration-in=".3" data-animation-in="fadeInDown" data-delay-in=".8"  class="btn btn-main" href="gaspol_web/pages/menu_membership">Pembuatan KTA, KIS, TKT, TAA</a> -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <div style="background-color: #F6F6F6" class="p-5">
    <div class="row">
      <div class="col-12 text-center">
        <p>Bekerjasama dengan :</p>
        <img src="gaspol_web/pages/gaspol-landing/assets/img/represented.svg" style="width: 180px;">
      </div>
    </div>
  </div>

  <!--
Start About Section
==================================== -->
  <section class="service-2 section">
    <div class="container">
      <div class="row">

        <div class="col-12">
          <!-- section title -->
          <div class="title text-center">
            <h2>Mari Bergabung Dengan Klub IMI</h2>
            <p>Terhubung dengan komunitas terbesar di Indonesia dan dapatkan benefit dari mitra dan toko kami</p>
            <div class="border"></div>
          </div>
          <!-- /section title -->
        </div>

        <div class="col-12 col-md-6">
          <div class="text-center mt-3" style="background-color: #F6F6F6; border-radius: 20px; height: 200px">
            <img src="gaspol_web/pages/gaspol-landing/assets/img/association-orange.svg" style="width: 150px">
            <p><b>Akses tak terbatas ke jaringan IMI</b></p>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="text-center mt-3" style="background-color: #F6F6F6; border-radius: 20px; height: 200px">
            <img src="gaspol_web/pages/gaspol-landing/assets/img/events-orange.svg" style="width: 150px">
            <p><b>Akses tak terbatas ke acara nasional</b></p>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="text-center mt-3" style="background-color: #F6F6F6; border-radius: 20px; height: 200px">
            <img src="gaspol_web/pages/gaspol-landing/assets/img/product.svg" style="width: 150px">
            <p><b>Diskon produk dari toko</b></p>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="text-center mt-3" style="background-color: #F6F6F6; border-radius: 20px; height: 200px">
            <img src="gaspol_web/pages/gaspol-landing/assets/img/safe.svg" style="width: 150px">
            <p><b>Asuransi anggota premium</b></p>
          </div>
        </div>

      </div> <!-- End row -->
    </div> <!-- End container -->
  </section> <!-- End section -->

  <!--
		Start Blog Section
		=========================================== -->

  <section class="blog" id="blog">
    <div class="container">
      <div class="row">

        <!-- section title -->
        <div class="col-12">
          <div class="title text-center ">
            <h2>Berita</h2>
            <p>Berikut adalah berita terbaru dari Gaspol!</p>
            <div class="border"></div>
          </div>
        </div>
        <!-- /section title -->
        <!-- single blog post -->

        <?php foreach ($news as $n) : ?>

          <article class="col-md-4 col-sm-6 col-xs-12 clearfix ">
            <div class="post-item">
              <div class="media-wrapper text-center">
                <img src="gaspol_web/pages/gaspol-landing/<?= $n['IMAGES'] ?>" alt="amazing caves coverimage" style="border-radius: 20px" class="img-fluid">
              </div>

              <div class="content text-center">
                <h3 style="font-size: 20px"><a href="gaspol_web/pages/gaspol-landing/article.php"><?= $n['TITLE'] ?></a></h3>
                <p><?= substr_replace($n['NEWS'], "...", 120);  ?></p>
                <a class="btn btn-main" href="gaspol_web/pages/gaspol-landing/article.php?id=<?= $n['ID'] ?>">Read more</a>
              </div>
            </div>
          </article>

        <?php endforeach; ?>

        <!-- /single blog post -->

        <!-- single blog post -->
        <!-- <article class="col-md-4 col-sm-6 col-xs-12 ">
				<div class="post-item">
					<div class="media-wrapper text-center">
						<img src="https://gaspol.co.id/abouts/imi-network.svg" alt="amazing caves coverimage" class="img-fluid">
					</div>

					<div class="content text-center">
						<h3><a href="article.php">Berita 2</a></h3>
						<p>Berisi deskripsi singkat dari berita Gaspol! untuk melihat berita selengkapnya silahkan pilih tombol di bawah ini.</p>
						<a class="btn btn-main" href="article.php">Read more</a>
					</div>
				</div>
			</article> -->
        <!-- end single blog post -->

        <!-- single blog post -->
        <!-- <article class="col-md-4 col-sm-6 col-xs-12 ">
				<div class="post-item">
					<div class="media-wrapper text-center">
						<img src="https://gaspol.co.id/abouts/imi-network.svg" alt="amazing caves coverimage" class="img-fluid">
					</div>

					<div class="content text-center">
						<h3><a href="article.php">Berita 3</a></h3>
						<p>Berisi deskripsi singkat dari berita Gaspol! untuk melihat berita selengkapnya silahkan pilih tombol di bawah ini.</p>
						<a class="btn btn-main" href="article.php">Read more</a>
					</div>
				</div>
			</article> -->
        <!-- end single blog post -->
      </div> <!-- end row -->
    </div> <!-- end container -->
  </section> <!-- end section -->

  <footer id="footer" class="bg-one">
    <div class="footer-bottom">
      <h5>Copyright 2022. All rights reserved.</h5>
      <h6>Developed by <a href="">Gaspol!</a></h6>
      <hr>
      <hr>
      <div class="privacy-area">
        <span>Privacy Policy</span> | <span>Terms of Services</span>
      </div>
    </div>
  </footer> <!-- end footer -->

  <!-- end Footer Area
    ========================================== -->

  <!-- 
    Essential Scripts
    =====================================-->
  <!-- Main jQuery -->
  <script src="gaspol_web/pages/gaspol-landing/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap4 -->
  <script src="gaspol_web/pages/gaspol-landing/plugins/bootstrap/js/bootstrap.min.js"></script>
  <!-- Parallax -->
  <script src="gaspol_web/pages/gaspol-landing/plugins/parallax/jquery.parallax-1.1.3.js"></script>
  <!-- Owl Carousel -->
  <script src="gaspol_web/pages/gaspol-landing/plugins/slick/slick.min.js"></script>
  <!-- filter -->
  <script src="gaspol_web/pages/gaspol-landing/plugins/filterizr/jquery.filterizr.min.js"></script>
  <!-- Smooth Scroll js -->
  <script src="gaspol_web/pages/gaspol-landing/plugins/smooth-scroll/smooth-scroll.min.js"></script>

  <!-- Custom js -->
  <script src="gaspol_web/pages/gaspol-landing/js/script.js"></script>

</body>

</html>

<script>
  if (window.Android) {
    // $('#menu-membership').hide();
    // $('#menu-sign-in').hide();
    // $('#join-club-1').hide();
    // $('#join-club-2').hide();

    $("#join-club-1").attr("href", "gaspol_web/pages/gaspol-landing/membership.php?f_pin=" + window.Android.getFPin());
    $("#join-club-2").attr("href", "gaspol_web/pages/gaspol-landing/membership.php?f_pin=" + window.Android.getFPin());
    $("#membership-link").attr("href", "gaspol_web/pages/gaspol-landing/membership.php?f_pin=" + window.Android.getFPin());

    console.log("Ini Android");

  } else {
    // $('#join-club-3').show();
    // $('#join-club-4').show();
  }
</script>