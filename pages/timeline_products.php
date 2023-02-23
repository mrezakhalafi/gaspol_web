<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (isset($_REQUEST['store_id'])) {
    $store_id = $_REQUEST['store_id'];
}

if (isset($_REQUEST['f_pin'])) {
    $f_pin = $_REQUEST['f_pin'];
}

$products_final = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_products_raw.php');

// print_r($products_final);


// shuffle the timeline
// shuffle($products_final);



if (empty($products_final)) {
    echo '<div class="my-2" id="product-null">';
    echo '<div class="col-sm mt-2">';
    echo '<h5 class="prod-name" id="none-text" style="text-align:center;"></h5>';
    echo '</div>';
    echo '</div>';
} else {

    // check wishlist
    if ($query = $dbconn->prepare("SELECT PRODUCT_CODE FROM WISHLIST_PRODUCT WHERE FPIN = ?")) {
        $query->bind_param('s', $f_pin);
        $query->execute();
        $wishlist = $query->get_result();
        $query->close();
    } else {
        //error !! don't go further
        var_dump($dbconn->error);
    }

    $wishlists = array();
    while ($wish = $wishlist->fetch_assoc()) {
        $wishlists[] = $wish['PRODUCT_CODE'];
    };
    // end wishlist

    $products_liked_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_products_liked_raw.php');
    $products_liked = array();
    foreach ($products_liked_raw as $product_liked) {
        $products_liked[] = $product_liked["PRODUCT_CODE"];
    }

    $stores_followed_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_followed_stores_raw.php');
    $stores_followed = array();
    foreach ($stores_followed_raw as $store_followed) {
        $stores_followed[] = $store_followed["L_PIN"];
    }

    $bookmarks_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_post_bookmarks.php');
    $bookmarks = array();
    foreach ($bookmarks_raw as $bookmark) {
        $bookmarks[] = $bookmark["POST_ID"];
    }

    // echo "<pre>";
    // print_r($stores_followed);
    // echo "</pre>";

    // $stores_followed_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_stores_followed_raw.php');
    // $stores_followed = array();
    // foreach ($stores_followed_raw as $store_followed) {
    //     $stores_followed[] = $store_followed["STORE_CODE"];
    // }

    $products_commented_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_products_commented_raw.php');
    $products_commented = array();
    foreach ($products_commented_raw as $product_commented) {
        $products_commented[] = $product_commented["PRODUCT_CODE"];
    }

    $purchases_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_purchases_raw.php');
    $purchases = array();
    foreach ($purchases_raw as $pc) {
        $purchases[] = $pc["POST_ID"];
    }

    $posts_reported_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_posts_reported_raw.php');
    $posts_reported = array();
    foreach ($posts_reported_raw as $post_reported) {
        $posts_reported[] = $post_reported["POST_ID"];
    }

    $shared_posts = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_shared_posts_raw.php');
    // $shared_posts = array();
    // foreach ($shared_posts_raw as $shared_post) {
    //     // $shared_posts[] = $shared_post["POST_ID"];
    //     $shared_posts[] = array(
    //         "POST_ID"
    //     );
    // }

    // echo "<pre>";
    // print_r($shared_posts);
    // echo "</pre>";

    $total_comments_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_total_comments.php');

    $total_likes_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_total_likes.php');

    // echo 'reported by fpin';
    // echo "<pre>";
    // print_r($posts_reported);
    // echo "</pre>";

    $reports_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_reported_posts.php');

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

    $image_type_arr = array("jpg", "jpeg", "png", "webp");
    $video_type_arr = array("mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg');
    $shop_blacklist = array("17b0ae770cd", "239"); //isi manual 

    // CHECK IS CHANGED PROFILE

    $query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '" . $f_pin . "'");
    $query->execute();
    $changedProfile = $query->get_result()->fetch_assoc();
    $query->close();

    for ($i = 0; $i < count($products_final); $i++) {

        $code = $products_final[$i]["CODE"];
        $post_f_pin = $products_final[$i]["CREATED_BY"];
        $changedProfile = $changedProfile['IS_CHANGED_PROFILE'];

        if ($reports_arr[$code]['TOTAL_REPORTS'] >= 100 || in_array($code, $posts_reported)) {
            // echo 'bro';
            continue;
        }
        if (in_array($post_f_pin, $blocked_users)) {
            continue;
        }

        if ($user_reports_arr[$post_f_pin]['TOTAL_REPORTS'] >= 100 || in_array($post_f_pin, $users_reported)) {
            continue;
        }

        $name = $products_final[$i]["NAME"];
        $created_date = $products_final[$i]["CREATED_DATE"];
        $category = $products_final[$i]["CATEGORY"];
        // $classification = $products_final[$i]["CLASSIFICATION"];
        $seconds = intval(intval($created_date) / 1000);
        // // $printed_date = date("H:i", $seconds);

        $lazy = ' loading=lazy';

        // print date
        $date_diff = round((time() - $seconds) / (60 * 60 * 24));
        if ($date_diff == 0) {
            $printed_date = "Hari ini";
        } else if ($date_diff == 1) {
            $printed_date = "Kemarin";
        } else if ($date_diff == 2) {
            $printed_date = "2 hari lalu";
        } else if ($date_diff == 3) {
            $printed_date = "3 hari lalu";
        } else if ($date_diff == 4) {
            $printed_date = "4 hari lalu";
        } else if ($date_diff == 5) {
            $printed_date = "5 hari lalu";
        } else if ($date_diff == 6) {
            $printed_date = "6 hari lalu";
        } else if ($date_diff == 7) {
            $printed_date = "7 hari lalu";
        } else if ($date_diff > 7 && $date_diff < 365) {
            $printed_date = date("j M Y", $seconds);
        } else if ($date_diff >= 365) {
            $printed_date = date("j M Y", $seconds);
        }
        $hourmin = date("H:i", $seconds);

        $store_id = $products_final[$i]["SHOP_CODE"];
        $desc = nl2br($products_final[$i]["DESCRIPTION"]);
        $thumb_id = $products_final[$i]["THUMB_ID"];
        $thumb_ids = explode("|", $thumb_id);
        $store_thumb_id = $products_final[$i]["STORE_THUMB_ID"];
        $store_name = $products_final[$i]["STORE_NAME"];
        $store_link = $products_final[$i]["STORE_LINK"];
        $total_likes = $products_final[$i]["TOTAL_LIKES"];
        $total_follower = $products_final[$i]["TOTAL_FOLLOWER"];
        $total_comment = count(include($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/fetch_products_comments.php'));
        $use_adblock = $products_final[$i]["USE_ADBLOCK"];
        $is_verified = $products_final[$i]["IS_STORE_VERIFIED"];
        $is_product = $products_final[$i]["IS_PRODUCT"];
        $tagged_product = $products_final[$i]["TAGGED_PRODUCT"];
        $is_paid = $products_final[$i]["PRICING"];
        $is_shop = $products_final[$i]["IS_SHOP"];
        $report = $products_final[$i]["REPORT"];

        if (in_array($store_id, $shop_blacklist)) {
            continue;
        }

        $domain = 'http://108.136.138.242';

        $imgs = explode('|', $store_thumb_id);
        if (substr($imgs[0], 0, 4) !== "https") {
            if ($imgs[0] == "") {
                $thumb = "../assets/img/ic_person_boy.png";
            } else {
                // $thumb = $imgs[0];
                // $thumb = "/gaspol_web/images/" . $imgs[0];
                $thumb = "http://108.136.138.242/filepalio/image/" . $imgs[0];
            }
        } else {
            $thumb = $imgs[0];
        }

        // if ($i > 0) {
        // }
        if ($category == "4") {
            echo '<div class="product-row mt-2 pt-3 pb-1" id="product-' . $code . '" data-iscontent="true" onclick="event.stopPropagation();openComment(\'' . $code . '\')">';
        } else {
            echo '<div class="product-row mt-2 pt-3 pb-1" id="product-' . $code . '" onclick="event.stopPropagation();openComment(\'' . $code . '\')">';
        }
        echo '<div class="col-sm">';
        echo '<div class="timeline-post-header media">';
        echo '<a class="d-flex pe-3" href="tab3-profile.php?l_pin=' . $store_id . '&f_pin=' . $f_pin . '">';
        // if ($is_shop >= 1) {
        //     echo '<a class="d-flex pe-2" href="tab3-profile.php?store_id=' . $store_id . '&f_pin=' . $f_pin . '">';
        // } else {
        //     echo '<a class="d-flex pe-2" href="tab3-profile-user.php?f_pin=' . $f_pin . '&store_id=' . $store_id . '">';
        // }
        echo '<img src="' . $thumb . '" class="profile-pic align-self-start rounded-circle mr-2">';
        echo '</a>';
        echo '<div class="media-body">';
        // if ($is_verified > 0) {
        //     echo '<h5 class="store-name"><img src="/gaspol_web/assets/img/icons/Verified-(Black).png"/>' . $store_name . '</h5>';
        // } else {
        //     echo '<h5 class="store-name">' . $store_name . '</h5>';
        // }
        if ($shared_posts[$code] != null) {
            $club_name = strlen($shared_posts[$code]["CLUB_NAME"]) > 15 ? substr($shared_posts[$code]["CLUB_NAME"],0,15)."..." : $shared_posts[$code]["CLUB_NAME"];
            $club_id = $shared_posts[$code]["CLUB_ID"];
            echo '<strong class="store-name">' . $store_name . '</strong> dalam <a href="gaspol_club?f_pin='.$f_pin.'&l_pin='.$club_id.'"><strong class="store-name">' . $club_name . '</strong></a>';
        } else {
            echo '<h5 class="store-name">' . $store_name . '</h5>';
        }
        // echo '<h5 class="store-name">' . $store_name . '</h5>';
        echo '<p style="color:#afafaf !important;"><span class="prod-timestamp">' . $printed_date . '</span> | <span class="hour-minute">'. $hourmin .'</span></p>';
        echo '</div>';
        echo '<div class="post-status d-none">';
        echo '<img src="../assets/img/ic_public.png" height="20" width="20"/>';
        echo '</div>';
        echo '<div class="post-status d-none">';
        echo '<img src="../assets/img/ic_user.png" height="20" width="20"/>';
        echo '</div>';
        echo '<div class="dropdown dropdown-edit edit-menu-' . $post_f_pin . '">';
        $isFollowed = 0;
        $isBookmarked = 0;
        if (in_array($store_id, $stores_followed)) {
            $isFollowed = 1;
        }
        if (in_array($code, $bookmarks)) {
            $isBookmarked = 1;
        }
        echo '<a class="post-status" id="edt-del-' . $code . '" data-postid="'.$code.'" data-isfollowed="'.$isFollowed.'" data-isbookmarked="'.$isBookmarked.'" data-createdby="'.$post_f_pin.'"><img src="../assets/img/icons/More.png" height="25" width="25" style="background-color:unset;"/></a>';
        echo '<ul class="dropdown-menu" aria-labelledby="edt-del-' . $code . '">';
        echo '<li><a class="dropdown-item button_edit" onclick="editPost(\'' . $code . '\')">Edit</a></li>';
        echo '<li><a class="dropdown-item button_delete" onclick="deletePost(\'' . $code . '\')">Delete</a></li>';
        echo '</ul>';
        // echo '<img src="../assets/img/icons/More.png" height="25" width="25"/>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="col-sm mt-3">';
        if ($code != '16467163130000246a901c4') {
            // echo '<span class="prod-name"><img class="verified-icon-prod" src="../assets/img/icons/Verified-(Black).png">' . $store_name . '</span>&emsp;';
            if ($is_paid == 0) {
                echo '<span class="prod-desc">' . strip_tags($desc) . '</span>';
            } else {
                if (in_array($code, $purchases)) {
                    echo '<span class="prod-desc">' . strip_tags($desc) . '</span>';
                } else {
                    echo '<div class="row my-3">';
                    echo '<div class="col-sm-12">';
                    echo '<h5 class="text-center">Purchase to see content</h5>';
                    echo '</div>';
                    echo '</div>';
                }
            }
        } else {
            echo '<div class="row">';
            echo '<div class="col-8">';
            // echo '<span class="prod-name"><img class="verified-icon-prod" src="../assets/img/icons/Verified-(Black).png">' . $store_name . '</span>&emsp;';
            if ($is_paid == 0) {
                echo '<span class="prod-desc">' . strip_tags($desc) . '</span>';
            } else {
                if (in_array($code, $purchases)) {
                    echo '<span class="prod-desc">' . strip_tags($desc) . '</span>';
                } else {
                    echo '<div class="row my-3">';
                    echo '<div class="col-sm-12">';
                    echo '<h5 class="text-center">Purchase to see content</h5>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            echo '</div>';
            echo '<div class="col-4 d-flex align-items-center justify-content-end">';
            echo '<a class="btn btn-dark click-membership" onclick="event.stopPropagation();goToURL(\'/gaspol_web/pages/menu_membership?f_pin=' . $f_pin . '\');" style="font-size:12px;">Klik Disini</a>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        if ($code == '16467163130000246a901c4') {

            echo '<div class="col-sm timeline-image" onclick="event.stopPropagation();goToURL(\'/gaspol_web/pages/menu_membership?f_pin=' . $f_pin . '\');">';
        } else {
            echo '<div class="col-sm timeline-image">';
        }
        // echo '<a class="timeline-main" onclick="openStore(\'' . $store_id . '\',\'' . $store_link . '\');">';
        // if ($is_paid != 0) {
        //     echo '<a class="timeline-main" id="detail-product-' . $code . '" onclick="showAddModalPost(\'' . $code . '\');">';
        // } else {
        //     echo '<a class="timeline-main" id="detail-product-' . $code . '">';
        // }
        if (count($thumb_ids) == 1) {

            // echo '<img class="single-image img-fluid rounded" src="' . $thumb_id . '">';
            $thumb_ext = trim(pathinfo($thumb_ids[0], PATHINFO_EXTENSION));
            $image_name = str_replace($thumb_ext, "", $thumb_ids[0]);
            // echo 'ext ' .$thumb_ext;
            // if ($code == '164429392600002d4a953a0') {
            //     echo 'count thumb ' . count($thumb_ids);
            //     echo '<br>';
            //     echo $thumb_ids[0];
            // }
            if (in_array($thumb_ext, $image_type_arr)) {
                echo '<img src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[0]) . '" class="img-fluid rounded"' . $lazy . '>';
                if ($tagged_product != null) {
                    echo '<div class="timeline-product-tag">';
                    echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                    echo '</div>';
                }
            } else if (in_array($thumb_ext, $video_type_arr)) {
                echo '<div class="video-wrap" id="videowrap-' . $code . '-0">';
                echo '<video muted playsinline loop src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[0]) . '#t=0.5" id="video-' . $code . '-0" class="myvid rounded" preload="" poster="' . $image_name . 'webp">';
                // echo '<source src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[0]) . '#t=0.5" type="video/' . $thumb_ext . '">';
                echo '</video>';
                if ($tagged_product != null) {
                    echo '<div class="timeline-product-tag-video">';
                    echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                    echo '</div>';
                }
                echo '<div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute(\'videowrap-' . $code . '-0\');">';
                echo '<img src="../assets/img/video_mute.png" />';
                echo '</div>';
                // echo '<div class="video-fullscreen">';
                // echo '<img src="../assets/img/fullscreen.png" />';
                // echo '</div>';
                echo '<div class="video-play d-none">';
                echo '<img src="../assets/img/video_play.png" />';
                echo '</div></div>';
            }
        } else {
            $count_thumb_id = count($thumb_ids);
            echo '<div id="carousel-' . $code . '" class="carousel slide pointer-event" data-bs-touch="true" data-bs-interval="false" data-bs-ride="carousel" data-bs-wrap="false">';
            echo '<ol id="ci-' . $code . '" class=' . '"carousel-indicators">';
            for ($j = 0; $j < $count_thumb_id; $j++) {
                if ($j == 0) {
                    echo '<li data-bs-target="#carousel-' . $code . '" data-bs-slide-to="' . $j . '" class="active"></li>';
                } else {
                    echo '<li data-bs-target="#carousel-' . $code . '" data-bs-slide-to="' . $j . '"></li>';
                }
            }
            echo '</ol>';
            echo '<div class="carousel-inner">';
            for ($j = 0; $j < count($thumb_ids); $j++) {
                if ($j == 0) {
                    echo '<div class="carousel-item active">';
                } else {
                    echo '<div class="carousel-item">';
                }
                echo '<div class="carousel-item-wrap">';
                $thumb_ext = pathinfo($thumb_ids[$j], PATHINFO_EXTENSION);
                $image_name = str_replace($thumb_ext, "", $thumb_ids[$j]);
                if (in_array($thumb_ext, $image_type_arr)) {
                    echo '<img src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[$j]) . '" class="img-fluid rounded"' . $lazy . '>';
                    if ($tagged_product != null) {
                        echo '<div class="timeline-product-tag">';
                        echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                        echo '</div>';
                    }
                } else if (in_array($thumb_ext, $video_type_arr)) {
                    echo '<div class="video-wrap" id="videowrap-' . $code . '-' . $j . '">';
                    echo '<video playsinline muted loop src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[$j]) . '#t=0.5" id="video-' . $code . '-' . $j . '" class="myvid rounded" preload="" poster="' . $image_name . 'webp">';
                    // echo '<source src="' . $domain . '/gaspol_web/images/' . basename($thumb_ids[$j]) . '#t=0.5" type="video/' . $thumb_ext . '">';
                    echo '</video>';
                    if ($tagged_product != null) {
                        echo '<div class="timeline-product-tag-video">';
                        echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                        echo '</div>';
                    }
                    echo '<div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute(\'videowrap-' . $code . '-' . $j . '\');">';
                    echo '<img src="../assets/img/video_mute.png" />';
                    echo '</div>';
                    // echo '<div class="video-fullscreen">';
                    // echo '<img src="../assets/img/fullscreen.png" />';
                    // echo '</div>';
                    echo '<div class="video-play d-none">';
                    echo '<img src="../assets/img/video_play.png" />';
                    echo '</div></div>';
                }

                echo '</div></div>';
            }
            echo '</div>';
            // echo '<a class="carousel-control-prev" data-bs-target="#carousel-' . $code . '" data-bs-slide="prev" onclick="event.stopPropagation(); buttonTheme(\'' . $category . '\')">';
            // echo '<span class="carousel-control-prev-icon"></span>';
            // echo '</a>';
            // echo '<a class="carousel-control-next" data-bs-target="#carousel-' . $code . '" data-bs-slide="next" onclick="event.stopPropagation(); buttonTheme(\'' . $category . '\')">';
            // echo '<span class="carousel-control-next-icon"></span>';
            // echo '</a>';
            echo '</div>';
        }
        echo '</a>';
        echo '</div>';
        echo '<div class="col-sm like-comment-container">';        
        echo '<div class="comment-button">';
        // echo '<a href="comment?product_code=' . $code . '&f_pin='.$f_pin.'">';
        echo '<a onclick="event.stopPropagation();openComment(\'' . $code . '\')">';
        if (in_array($code, $products_commented)) {
            echo '<img class="comment-icon-' . $code . '" src="../assets/img/jim_comments_blue.png?v=2" height="25" width="25"/>';
        } else {
            echo '<img class="comment-icon-' . $code . '" src="../assets/img/jim_comments.png?v=2" height="25" width="25"/>';
        }
        echo '</a>';
        echo '<div class="like-comment-counter">';
        echo $total_comment;
        echo '</div>';
        echo '</div>';
        echo '<div class="like-button" onClick="event.stopPropagation();likeProduct(\'' . $code . '\')">';
        if (in_array($code, $products_liked)) {
            echo '<img id=like-' . $code . ' src="../assets/img/jim_likes_red.png" height="25" width="25"/>';
        } else {
            echo '<img id=like-' . $code . ' src="../assets/img/jim_likes.png?v=2" height="25" width="25"/>';
        }
        echo '<div id=like-counter-' . $code . ' class="like-comment-counter">';
        echo $total_likes;
        echo '</div>';
        echo '</div>';

        // if ($post_f_pin != $f_pin) {
        //     echo '<img src="../assets/img/warning.png?v=2" style="width: 25px; height: 25px" id="dropdownMenuSelectLanguage" class="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false"></img>';
        //     echo '<ul class="dropdown-menu shadow-lg" style="min-width: auto !important; position: absolute; border: 1px solid black; z-index: 1000" aria-labelledby="dropdownMenuLanguage">';

        //     echo '<li id="report_content" onclick="reportContent(\'' . $code . '\',' . $report . ')"><a class="dropdown-item report_content_text" data-translate="tab5listing-10">Report this Content</a></li>';
        //     echo '<li id="report_user" onclick="reportUser(\'' . $post_f_pin . '\')" ><a class="dropdown-item report_user_text" data-translate="tab5listing-10">Report this User</a></li>';
        //     echo '<li id="block_user" onclick="blockUser(\'' . $post_f_pin . '\')"><button type="submit" style="color:brown" class="dropdown-item block_user_text" data-translate="tab5listing-11">Block this User</button></li>';

        //     echo '</ul>';
        // }

        // echo '<div class="follower-button" onClick="addWishlist(\'' . $code . '\',this)">';
        // if ($is_paid == 1) {
        //     if (in_array($code, $wishlists)) {
        //         echo '<img class="follow-icon-' . $store_id . '" src="../assets/img/icons/Wishlist-fill.png" height="25" width="25"/>';
        //     } else {
        //         echo '<img class="follow-icon-' . $store_id . '" src="../assets/img/icons/Wishlist.png" height="25" width="25"/>';
        //     }
        // }
        // echo '<div id=follow-counter-post-' . $code . ' class="d-none like-comment-counter follow-counter-store-' . $store_id . '">';
        // echo $total_follower . ' pengikut';
        // echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';      
        // echo '<hr class="my-0" style="background-color: lightgray; height: 7px;">';
    }
}

?>

<script>
    if (localStorage.lang == 1) {
        $('.report_content_text').text('Laporkan Konten');
        $('.report_user_text').text('Laporkan Pengguna');
        $('.block_user_text').text('Blokir Pengguna');
        $('a.click-membership').text('Klik Disini');
        $('#none-text').text('Tidak ada konten yang sesuai dengan kriteria.');
    } else {
        $('#none-text').text('There are no content that match the criteria.');
        $('a.click-membership').text('Click here');

        if ($(".prod-timestamp").text() == "Hari ini") {
            $(".prod-timestamp").text($(".prod-timestamp").text().replace("Hari ini", "Today"));
        }

        if ($(".prod-timestamp").text() == "Kemarin") {
            $(".prod-timestamp").text($(".prod-timestamp").text().replace("Kemarin", "Yesterday"));
        }

        if ($(".prod-timestamp").text() == "3 hari lalu") {
            $(".prod-timestamp").text($(".prod-timestamp").text().replace("3 hari lalu", "3 days ago"));
        }

        if ($(".prod-timestamp").text() == "4 hari lalu") {
            $(".prod-timestamp").text($(".prod-timestamp").text().replace("4 hari lalu", "4 days ago"));
        }

        if ($(".prod-timestamp").text() == "5 hari lalu") {
            $(".prod-timestamp").text($(".prod-timestamp").text().replace("5 hari lalu", "5 days ago"));
        }

        if ($(".prod-timestamp").text() == "6 hari lalu") {
            $(".prod-timestamp").text($(".prod-timestamp").text().replace("6 hari lalu", "6 days ago"));
        }
    }

    // FOR MODAL REPORT AUTOCLOSE WHILE SCROLLING

    $(window).scroll(function() {
        $(".dropdownMenuSelectLanguage").dropdown('hide');
    });
</script>