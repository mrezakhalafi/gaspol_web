<ul>
    <li id="all-store" class='has-story'>
        <div class="story">
            <img src="../assets/img/nxsport_icons/glball.webp?v=14216">
        </div>
        <span class="user" id="story-all-posts"></span>
    </li>
    <!-- <li id="store-0246a901c4" class="has-story">
        <div class="story">
            <img src="https://qmera.io/filepalio/image/profile-1803B8019E4.jpg" loading="lazy">
        </div>
        <span class="user">Ikatan Motor Indonesia</span>
    </li> -->

    <?php

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    $shop_blacklist = array("17b0ae770cd", "239"); //isi manual 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

    $dbconn = paliolite();

    $showLinkless = 2;
    try {
        $query = $dbconn->prepare("SELECT `VALUE` FROM `SHOP_SETTINGS` WHERE `PROPERTY` = 'SHOW_LINKLESS_STORE'");
        $query->execute();
        $geoloc = $query->get_result()->fetch_assoc();
        $showLinkless = $geoloc['VALUE'];
        $query->close();
    } catch (\Throwable $th) {
    }

    if (!isset($_GET['horizontal_seed'])) {
        $horizontal_seed = time();
    } else {
        $horizontal_seed = $_GET['horizontal_seed'];
    }

    $sql_where = '';

    $sql_filter = '';

    if (isset($_REQUEST['filter'])) {
        $filter = $_REQUEST['filter'];

        $filterArr = explode('-', $_REQUEST['filter']);

        $sql_filter = 'AND (';

        $tempArr = array();

        foreach ($filterArr as $filter) {
            $tempArr[] = "p.CATEGORY = '$filter'";
        }

        $sql_filter .= implode(' OR ', $tempArr);

        $sql_filter .= ')';
    }

    if (isset($_REQUEST['f_pin'])) {
        $f_pin = $_REQUEST['f_pin'];
        $sql_where .= " OR s.CREATED_BY = '$f_pin' OR s.CREATED_BY IN (SELECT fl.L_PIN from SHOP sp LEFT JOIN FRIEND_LIST fl on sp.CREATED_BY = fl.L_PIN WHERE fl.F_PIN = '$f_pin')";
    }

    // $sql_where .= " GROUP BY (s.CODE) ORDER BY RAND($horizontal_seed)";

    $sql_filter_post = str_replace(" AND p.IS_DELETED = 0", "", $sql_filter);
    $sql_filter_post = str_replace("p.CATEGORY", "c.ID", $sql_filter_post);

    // $sql_where_post = str_replace("")

    $sql = 'SELECT 
    u.ID, 
    u.F_PIN AS CODE, 
    u.IMAGE AS THUMB_ID, 
    CONCAT(u.FIRST_NAME, " ", u.LAST_NAME) AS NAME, 
    0 AS IS_VERIFIED, 
    0 AS IS_LIVE_STREAMING, 
    p.F_PIN, 
    0 AS IS_SHOP,
    MAX(p.SCORE) AS SCORE 
  FROM 
    POST p 
    LEFT JOIN USER_LIST u ON p.F_PIN = u.F_PIN 
    LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID 
    LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID 
  WHERE 
    (
      c.EDUCATIONAL = 5 ' . $sql_filter_post . '
    ) 
    AND p.EC_DATE IS NULL 
    AND u.BE IN (
      SELECT 
        BE 
      FROM 
        USER_LIST 
      WHERE 
        F_PIN = "' . $f_pin . '"
    ) 
  GROUP BY  
    u.F_PIN 
  ORDER BY 
    SCORE DESC;
    ';




    // echo $sql;

    $query = $dbconn->prepare($sql);
    $query->execute();
    $groups  = $query->get_result();
    $query->close();

    $stores_final = array();
    while ($group = $groups->fetch_assoc()) {
        if ($showLinkless == 2 || ($showLinkless == 1 && empty($group["LINK"])) || ($showLinkless == 0 && !empty($group["LINK"]))) {
            $stores_final[] = $group;
        }
    };

    // echo "<pre>";
    // print_r($stores_final);
    // echo "</pre>";

    $blocked_users_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_blocked_users.php');
    $blocked_users = array();
    foreach ($blocked_users_raw as $blocked_user) {
        $blocked_users[] = $blocked_user["L_PIN"];
    }

    $users_reported_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_users_reported_raw.php');
    $users_reported = array();
    foreach ($users_reported_raw as $user_reported) {
        $users_reported[] = $user_reported["F_PIN_REPORTED"];
    }

    $user_reports_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_reported_users.php');


    for ($i = 0; $i < count($stores_final); $i++) {


        $idStore = $stores_final[$i]["ID"];
        $codeStore = $stores_final[$i]["CODE"];
        $urlStore = $stores_final[$i]["THUMB_ID"];
        $nameStore = $stores_final[$i]["NAME"];
        $is_verified = $stores_final[$i]["IS_VERIFIED"];
        $is_live_streaming = $stores_final[$i]["IS_LIVE_STREAMING"];
        $post_f_pin = $stores_final[$i]["F_PIN"];
        $is_shop = $stores_final[$i]["IS_SHOP"];

        if (in_array($codeStore, $shop_blacklist)) {
            continue;
        }

        // if (in_array($post_f_pin, $blocked_users)) {
        //     continue;
        // }

        // if ($user_reports_arr[$post_f_pin]['TOTAL_REPORTS'] >= 100 || in_array($f_pin, $users_reported)) {
        //     continue;
        // }

        $imgs = explode('|', $urlStore);
        if ($imgs[0] == null || strlen($imgs[0]) == 0) {
            $thumb = '/gaspol_web/assets/img/ic_person_boy.png';
        } else {
            if (substr($imgs[0], 0, 5) !== "https") {
                $thumb = "http://108.136.138.242/filepalio/image/" . $imgs[0];
            } else {
                $thumb = $imgs[0];
            }
        }

        // $imgs = explode('|', $urlStore);
        // if (substr($imgs[0], 0, 4) !== "http") {
        //     if ($is_shop == 1) {
        //         $thumb = "https://qmera.io/gaspol_web/images/" . $imgs[0];
        //     } else {
        //         $thumb = "https://qmera.io/filepalio/image/" . $imgs[0];
        //     }
        // } else {
        //     $thumb = $imgs[0];
        // }

        $lazy = "";

        if ($i > 5) {
            $lazy = " loading='lazy'";
        }

        echo '<li id="store-' . $codeStore .  '" class="has-story">';
        // echo "<a href='timeline.php?store_id=" . $idStore . "'>";
        echo "<div class='story'>";
        echo "<img src='$thumb' $lazy>";

        if ($is_live_streaming > 0) {
            // echo '<div class="icon-live">';
            echo '<img class="icon-live" src="/gaspol_web/assets/img/live_indicator.png"/>';
            // echo '</div>';
        }

        echo "</div>";
        // echo "</a>";
        if ($is_verified == 1) {
            // echo "<span class='user'><img src='/gaspol_web/assets/img/icons/Verified-(Black).png'/>" . $nameStore . "</span>";
            echo "<span class='user'>" . $nameStore . "</span>";
        } else {
            echo "<span class='user'>" . $nameStore . "</span>";
        }
        echo "</li>";
    }

    // get profpic
    // $str = "SELECT * FROM USER_LIST WHERE F_PIN = '02fc4da57e'";
    // $query = $dbconn->prepare($str);
    // $query->execute();
    // $groups = $query->get_result()->fetch_assoc();
    // $query->close();

    // $name1 = $groups['FIRST_NAME'] . ' ' . $groups['LAST_NAME'];
    // $profpic1 = $groups['IMAGE'];

    // $str = "SELECT * FROM USER_LIST WHERE F_PIN = '02072b68ec'";
    // $query = $dbconn->prepare($str);
    // $query->execute();
    // $groups = $query->get_result()->fetch_assoc();
    // $query->close();

    // $name2 = $groups['FIRST_NAME'] . ' ' . $groups['LAST_NAME'];
    // $profpic2 = $groups['IMAGE'];

    // if ($profpic1 == '' || $profpic1 == null) {
    //     $profpic1 ="../assets/img/ic_person_boy.png";
    // } else {
    //     $profpic1 ="https://qmera.io/filepalio/image/" . $profpic1;
    // }

    // if ($profpic2 == '' || $profpic2 == null) {
    //     $profpic2 = "../assets/img/ic_person_boy.png";
    // } else {
    //     $profpic2 =  "https://qmera.io/filepalio/image/" . $profpic2;
    // }

    ?>
</ul>

<script>
    // $('#addtocart-success').on('hidden.bs.modal', function() {
    //   location.reload();
    // });

    if (localStorage.lang == 0) {
        $('#story-all-posts').text("All Posts");
    } else {
        $('#story-all-posts').text("Semua Post");
    }
</script>