<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

    $dbconn = paliolite();

    // $store_code = $_POST['post_id'];
    $flag_follow = $_POST['flag_follow']; // current state - 0 = not followed; 1 = followed
    $last_update = $_POST['last_update'];
    $f_pin = $_POST['f_pin'];
    $tkt_id = $_POST['tkt_id'];

    try {
        if($flag_follow == '0'){
            // $query = $dbconn->prepare("INSERT INTO SHOP_FOLLOW (F_PIN, STORE_CODE, FOLLOW_DATE) VALUES (?,?,?) ON DUPLICATE KEY UPDATE FOLLOW_DATE = ?");
            // $query->bind_param("ssss", $f_pin, $store_code, $last_update, $last_update);
            // $query->execute();
            // $query->close();

            // $query = $dbconn->prepare("UPDATE SHOP SET TOTAL_FOLLOWER=TOTAL_FOLLOWER+1 WHERE CODE = ?");
            // $query->bind_param("s", $store_code);
            // $query->execute();
            // $query->close();


            $sql = "INSERT INTO FOLLOW_TKT (F_PIN, TKT_ID) VALUES ('".$f_pin."','".$tkt_id."')";
            $query = $dbconn->prepare($sql);
            $query->execute();
            $query->close();

            echo "follow|" . $post_f_pin;
        } else {
            $query = $dbconn->prepare("DELETE FROM FOLLOW_TKT WHERE F_PIN = ? AND TKT_ID = ?");
            $query->bind_param("ss", $f_pin, $tkt_id);
            $query->execute();
            $query->close();

            // $query = $dbconn->prepare("UPDATE SHOP SET TOTAL_FOLLOWER=IF(TOTAL_FOLLOWER<=0,0,TOTAL_FOLLOWER-1) WHERE CODE = ?");
            // $query->bind_param("s", $store_code);
            // $query->execute();
            // $query->close();

            echo "unfollow|" . $post_f_pin;
        }

        // echo ' Success ';

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>