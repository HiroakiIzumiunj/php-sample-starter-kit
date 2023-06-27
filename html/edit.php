<?php

//セッションスタート
session_start();

//URLからuser_idを取得
$user_id = substr($_SERVER['REQUEST_URI'], 6);

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
    if ((mb_strlen($_POST['username']) > 20)) {

        //ユーザー名エラーのフラグをtrue
        $err_username_flag = true;
    } else if ((mb_strlen($_POST['username']) < 1)) {

        //ユーザー名エラーのフラグをtrue
        $err_username_flag = true;
    } else {

        //ユーザー名エラーのフラグをfalse
        $err_username_flag = false;
    }

    //コメントが100文字以上であればエラー表示
    if ((mb_strlen($_POST['comment']) > 100)) {

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
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $participation_id = mysqli_real_escape_string($link, $_POST['participation_id']);
    $comment = mysqli_real_escape_string($link, $_POST['comment']);

    // SELECT文を実行
    $sql = "UPDATE questionnaire SET username = '" . $username . "', participation_id = '" . $participation_id . "', comment = '" . $comment . "' WHERE user_id = '" . $user_id . "';";

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

    // SELECT文を実行
    $sql = "SELECT username , participation_id , comment , user_id FROM questionnaire where user_id ='" . $user_id . "';";

    $res = mysqli_query($link, $sql);

    // 結果の行を取得
    $row = mysqli_fetch_assoc($res);

    mysqli_close($link);
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

    <!-- validation.jsを読み込む -->
    <script src="/validation.js" defer></script>

    <title>アンケート入力</title>
</head>

<body>
    <div class="container">
        <h1 class="my-3">新人歓迎会参加アンケート</h1>

        <form method="POST">
            <div class="mb-3">
                <label for="username">氏名</label>

                <!-- コメントエラー時クラスを追加 -->
                <!-- GET受信されていないときはPOST送信されたものを表示する -->
                <input type="text" name="username" id="username" class="form-control" value="<?= isset($row['username']) ? $row['username'] : $_POST['username']; ?>" />

                <!-- ユーザー名エラー表示 -->
                <div id="err-msg-name"></div>

            </div>
            <div class="mb-3">
                <label for="participation_id">新人歓迎会に参加しますか？:</label>

                <select name="participation_id" id="participation_id" class="form-control">

                    <?php
                    //'participation_id'から、選択されている項目をセレクトボタンの最初に表示
                    if ($row['participation_id'] === "1") {
                        $selected_ok = "selected";
                        $selected_no = null;
                    } else {
                        $selected_ok = null;
                        $selected_no = "selected";
                    }
                    ?>

                    <option value="1" <?= $selected_ok; ?>>参加！</option>
                    <option value="2" <?= $selected_no; ?>>不参加で。。。</optiohn>

                </select>

            </div>
            <div class="mb-3">

                <label for="comment">コメント:</label>

                <!-- GET受信されていないときはPOST送信されたものを表示する -->
                <textarea name="comment" id="comment" class="form-control"><?= isset($row['comment']) ? $row['comment'] : $_POST['comment']; ?></textarea>

                <!-- コメントエラー表示 -->
                <div id="err-msg-comment"></div>

            </div>
            <div>
                <!-- GET受信されていないときはなにも送信しない -->
                <input type="hidden" name="user_id" value=<?= isset($user_id) ? $user_id : ""; ?> />
            </div>
            <div>

                <!-- 'index.php'にGET送信 -->
                <a href='/' class="btn btn-secondary">戻る</a>

                <button type="submit" id="submit" class="btn btn-secondary">送信</button>
            </div>

            <!-- トークンを送る -->
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']); ?>">
        </form>
        <!-- Bootstrapの最後のリンクを読み込む -->
        <?php include('./Bootstrap_second.php'); ?>
    </div>

</body>

</html>