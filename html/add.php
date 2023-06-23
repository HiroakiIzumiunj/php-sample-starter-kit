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

//POST送信時
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    //ユーザー名が1文字以上、20文字以内で入力されなければエラー表示
    if ((mb_strlen($_POST['username']) < 1) || (mb_strlen($_POST['username']) > 20)) {

        //ユーザー名エラーメッセージ
        $err_username = "ユーザー名を20文字以内で入力して下さい。";

        //ユーザー名エラー時に追加するクラス
        $err_username_class = "is-invalid";

        //ユーザー名エラーのフラグをtrue
        $err_username_flag = true;
    } else {
        //ユーザー名エラーのフラグをfalse
        $err_username_flag = false;
    }

    //コメントが100文字以上であればエラー表示
    if ((mb_strlen($_POST['comment']) > 100)) {

        //コメントエラーメッセージ
        $err_comment = "ユーザーは100文字以内で入力して下さい。";

        //コメントエラー時に追加するクラス
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

    //トークンを確認する
    validateToken();

    // データベースへの接続
    $link = mysqli_connect('db', 'root', 'secret', 'sample');
    if ($link == null) {
        die("データベースの接続に失敗しました。");
    }

    //htmlspcialchar()関数を使用して全て文字列として表示
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, "UTF-8");
    $participation_id = htmlspecialchars($_POST['participation_id'], ENT_QUOTES, "UTF-8");
    $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, "UTF-8");

    // データの投入
    $sql = "INSERT INTO `questionnaire` (`username`, `participation_id`, `comment`) VALUES ('"  . $username . "', " . $participation_id . ", '" . $comment . "');";
    mysqli_query($link, $sql);

    // ホーム画面にリダイレクト
    header('Location: http://' . $_SERVER['HTTP_HOST']);
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
        <form method="POST" action="./add.php">
            <div class="form-group">
                <label for="username">氏名</label>

                <!-- ユーザー名エラー時クラスを追加 -->
                <input type="text" name="username" class="form-control <?= $err_username_class ?>" />

                <!-- ユーザー名エラー表示 -->
                <FONT COLOR="red"><?= isset($err_username) ? $err_username : null ?></FONT>
            </div>
            <div class="form-group">
                <label for="participation_id">新人歓迎会に参加しますか？:</label>
                <select name="participation_id" class="form-control">
                    <option value="1">参加！</option>
                    <option value="2">不参加で。。。</optiohn>
                </select>
            </div>
            <div class="form-group">
                <label for="comment">コメント:</label>

                <!-- コメントエラー時クラスを追加 -->
                <textarea name="comment" class="form-control <?= $err_comment_class ?>"></textarea>

                <!-- コメントエラー表示 -->
                <FONT COLOR="red"><?= isset($err_comment) ? $err_comment : null ?></FONT>
            </div>
            <div>

                <!-- 'index.php'にGET送信 -->
                <a href='./index.php' class="btn btn-secondary">戻る</a>
                <button type="submit" class="btn btn-secondary">送信</button>
            </div>

            <!-- トークンを送る -->
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']); ?>">
        </form>
    </div>

    <!-- Bootstrapの最後のリンクを読み込む -->
    <?php include('./Bootstrap_second.php'); ?>
</body>

</html>