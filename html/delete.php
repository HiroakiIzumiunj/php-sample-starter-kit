<?php

session_start();

function validateToken()
{
    // var_dump($_SESSION['token']);
    // var_dump(filter_input(INPUT_POST, 'token'));
        if (
            empty($_SESSION['token']) ||
            $_SESSION['token'] !== filter_input(INPUT_POST, 'token')
        ){
            exit('Invalid post request');
        }
}

// 接続処理
    // POST のときはデータの投入を実行
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    validateToken();
    // データベースへの接続
    $link = mysqli_connect('db', 'root', 'secret', 'sample');
    if ($link == null) {
    die("データベースの接続に失敗しました。");
    }

    // 文字コード
    mysqli_set_charset($link, 'utf8');

    $userid = mysqli_real_escape_string($link, $_POST['userid']);

    // SELECT文を実行
    $sql ="DELETE from questionnaire WHERE userid = '$userid'";

    mysqli_query($link, $sql);

    // ホーム画面にリダイレクト
    header('Location: http://' . $_SERVER['HTTP_HOST']);
}