<?php
// 接続処理
    // POST のときはデータの投入を実行
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // データベースへの接続
    $link = mysqli_connect('db', 'root', 'secret', 'sample');
    if ($link == null) {
    die("データベースの接続に失敗しました。");
    }

    // 文字コード
    mysqli_set_charset($link, 'utf8');

    // SELECT文を実行
    $sql ="DELETE from questionnaire WHERE userid = '" . $_POST['userid'] . "';";

    mysqli_query($link, $sql);

    // ホーム画面にリダイレクト
    header('Location: http://' . $_SERVER['HTTP_HOST']);
}