<?php

//セッションスタート
session_start();

//送られたトークンが空、または一致していなければエラーを表示する関数
function validateToken()
{
    if (
        empty($_SESSION['token']) ||
        $_SESSION['token'] !== filter_input(INPUT_POST, 'token')
    ) {
        exit('Invalid post request');
    }
}

$err_username_flag = false;

$err_comment_flag = false;

//POST送信時
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    //ユーザー名が1文字以上、20文字以内で入力されなければエラー表示
    if ((mb_strlen($_POST['username']) < 1) || (mb_strlen($_POST['username']) > 20)) {

        //エラーメッセージ
        $err_username = "ユーザー名を20文字以内で入力して下さい。";

        //エラー時に追加するクラス
        $err_username_class = "is-invalid";

        //ユーザー名エラーのフラグをtrue
        $err_username_flag = true;
    } else {
        //ユーザー名エラーのフラグをfalse
        $err_username_flag = false;
    }

    //コメントが100文字以上であればエラー表示
    if ((mb_strlen($_POST['comment']) > 100)) {

        //エラーメッセージ
        $err_comment = "ユーザーは100文字以内で入力して下さい。";

        //エラー時にクラスを追加
        $err_comment_class = "is-invalid";

        //コメントエラーのフラグをtrue
        $err_comment_flag = true;
    } else {
        //コメントエラーのフラグをfalse
        $err_comment_flag = false;
    }
}

// 接続処理
// POST のとき、かつユーザー名とコメントのエラーが出ていない時はデータの投入を実行
if ($_SERVER['REQUEST_METHOD'] === "POST" && !$err_username_flag && !$err_comment_flag) {

    validateToken();
    // データベースへの接続
    $link = mysqli_connect('db', 'root', 'secret', 'sample');
    if ($link == null) {
        die("データベースの接続に失敗しました。");
    }

    // 文字コード
    mysqli_set_charset($link, 'utf8');

    //エスケープされる文字列に含まれる特殊文字をエスケープし、mysql_query()関数で安全に利用できる形式に変換します。
    $userid = mysqli_real_escape_string($link, $_POST['userid']);

    //htmlspcialchar()関数を使用して全て文字列として表示

    // $username = htmlspecialchars($_POST['username'], ENT_QUOTES, "UTF-8");
    // $participation_id = htmlspecialchars($_POST['participation_id'], ENT_QUOTES, "UTF-8");
    // $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, "UTF-8");

    $userid = mysqli_real_escape_string($link, $_POST['userid']);
    $participation_id = mysqli_real_escape_string($link, $_POST['participation_id']);
    $comment = mysqli_real_escape_string($link, $_POST['comment']);

    // SELECT文を実行
    $sql = "UPDATE questionnaire SET username = '" . $username . "', participation_id = '" . $participation_id . "', comment = '" . $comment . "' WHERE userid = '$userid';";

    mysqli_query($link, $sql);

    // ホーム画面にリダイレクト
    header('Location: http://' . $_SERVER['HTTP_HOST']);
}


// GETの時の接続処理
else if ($_SERVER['REQUEST_METHOD'] === "GET") {

    $link = mysqli_connect('db', 'root', 'secret', 'sample');
    if ($link == null) {
        die("データベースの接続に失敗しました。");
    }

    // 文字コード
    mysqli_set_charset($link, 'utf8');

    //エスケープされる文字列に含まれる特殊文字をエスケープし、mysql_query()関数で安全に利用できる形式に変換します。
    //$userid = mysqli_real_escape_string($link, $_GET['userid']);

    // SELECT文を実行
    $sql = "SELECT username , participation_id , comment , userid FROM questionnaire where userid ='" . $_GET['userid'] . "';";

    $res = mysqli_query($link, $sql);

    // 結果の行を取得
    $row = mysqli_fetch_assoc($res);
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrapの最初のリンクを読み込む -->
    <?php include('./Bootstrap_first.php'); ?>

    <title>アンケート入力</title>
</head>

<body>
    <div class="container">
        <h1 class="my-3">新人歓迎会参加アンケート</h1>
        <form method="POST" action="./edit.php">
            <div class="form-group">
                <label for="username">氏名</label>

                <!-- コメントエラー時クラスを追加 -->
                <!-- GET受信されていないときはPOST送信されたものを表示する -->
                <input type="text" name="username" class="form-control <?= $err_username_class ?>" value="<?= isset($row['username']) ? $row['username'] : $_POST['username']; ?>" />

                <!-- ユーザー名エラー表示 -->
                <FONT COLOR="red"><?= isset($err_username) ? $err_username : null ?></FONT>

            </div>
            <div class="form-group">
                <label for="participation_id">新人歓迎会に参加しますか？:</label>

                <select name="participation_id" class="form-control">
                    <?php
                    //'participation_id'から、選択されている項目をセレクトボタンの最初に表示
                    if ($row['participation_id'] === "1") {
                        $first_p_id = "1";
                        $second_p_id = "2";
                        $first_status = "参加！";
                        $second_status = "不参加で。。。";
                    } else {
                        $first_p_id = "2";
                        $second_p_id = "1";
                        $first_status = "不参加で。。。";
                        $second_status = "参加！";
                    }
                    ?>
                    <option value=<?= $first_p_id ?>><?= $first_status ?></option>
                    <option value=<?= $second_p_id ?>><?= $second_status ?></optiohn>

                </select>

            </div>
            <div class="form-group">

                <label for="comment">コメント:</label>

                <!-- コメントエラー時クラスを追加 -->
                <!-- GET受信されていないときはPOST送信されたものを表示する -->
                <textarea name="comment" class="form-control <?= $err_comment_class ?>"><?= isset($row['comment']) ? $row['comment'] : $_POST['comment']; ?></textarea>

                <!-- コメントエラー表示 -->
                <FONT COLOR="red"><?= isset($err_comment) ? $err_comment : null ?></FONT>

            </div>
            <div>
                <!-- GET受信されていないときはなにも送信しない -->
                <input type="hidden" name="userid" value=<?= isset($row['userid']) ? $row['userid'] : ""; ?> />
            </div>
            <div>

                <!-- 'index.php'にGET送信 -->
                <a href='./index.php' class="btn btn-secondary">戻る</a>

                <!-- エラーが出ているときは送信ボタンを押させない <?= ($err_username_flag || $err_comment_flag) ? "disabled" : null; ?>-->
                <button type="submit" class="btn btn-secondary">送信</button>
            </div>

            <!-- トークンを送る -->
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']); ?>">
        </form>
        <!-- Bootstrapの最後のリンクを読み込む -->
        <?php include('./Bootstrap_second.php'); ?>
    </div>

</body>

</html>