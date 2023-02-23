<?php

function insertIndexPostById($post_id)
{
    include_once $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php';
    $dbconn = paliolite();

    // get post
    $query = $dbconn->prepare("SELECT * FROM POST WHERE POST_ID='$post_id'");
    $query->execute();
    $item = $query->get_result()->fetch_assoc();
    $title = $item['TITLE'];
    $description = $item['DESCRIPTION'];
    $query->close();

    $rows = array();
    while ($shop = $users->fetch_assoc()) {
        $rows[] = $shop;
    };

    $title_words = preg_split("/\s+/", $title, -1, PREG_SPLIT_NO_EMPTY);
    $description_words = preg_split("/\s+/", $description, -1, PREG_SPLIT_NO_EMPTY);

    $words_list = array_unique(array_map('strtolower', array_merge($title_words, $description_words)));
    $query2 = $dbconn->prepare("insert into `QIDX_POST` (`WORD`,`KEY`) values (?,'" . $post_id . "')");
    foreach ($words_list as $word_index) {
        $query2->execute($word_index);
    }
    $query2->close();

}

function insertIndexPost($post_id, $title, $description)
{
    $title_words = preg_split("/\s+/", $title, -1, PREG_SPLIT_NO_EMPTY);
    $description_words = preg_split("/\s+/", $description, -1, PREG_SPLIT_NO_EMPTY);

    $words_list = array_unique(array_map('strtolower', array_merge($title_words, $description_words)));

    include_once $_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php';
    $dbconn = paliolite();
    $query = $dbconn->prepare("insert into `QIDX_POST` (`WORD`,`KEY`) values (?,'" . $post_id . "')");
    foreach ($words_list as $word_index) {
        $query->execute($word_index);
    }
    $query->close();

}

function getPostIndexSearchSubQuery($words)
{
    if (is_array($words)) {
        return "POST_ID in (select `KEY` in `QIDX_POST` where `WORD` regexp '" . implode("|", $words) . "')";
    } else {
        return "POST_ID in (select `KEY` in `QIDX_POST` where `WORD` like '%" . $words . "%')";
    }
}
