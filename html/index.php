<?php

//セッションスタート
session_start();

//トークンを作成する関数
function createToken()
{
    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }
}

//トークンを作成する関数を呼び出す
createToken();

// 接続処理
$link = mysqli_connect('db', 'root', 'secret', 'sample');
if ($link == null) {
    die("データベースの接続に失敗しました。");
}

// 文字コード
mysqli_set_charset($link, 'utf8');

// SQLの発行と出力
$sql = "SELECT * FROM questionnaire";
$res = mysqli_query($link, $sql);

//接続断
mysqli_close($link);

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
        <h1 class="my-3">新人歓迎会参加アンケート結果</h1>
        <table class="table align-middle">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">氏名</th>
                    <th scope="col">参加するかどうか</th>
                    <th scope="col">コメント</th>
                    <th scope="col" style="width: 10%"></th>
                    <th scope="col" style="width: 10%"></th>
                </tr>
            </thead>

            <!-- 取得した行に対応する連想配列を返します。もしもう行がない場合には NULL を返します。 -->
            <?php while ($row = mysqli_fetch_assoc($res)) : ?>
                <tbody>
                    <tr>
                        <td><?= $row['user_id'] ?></td>
                        <td><?= $row['username'] ?></td>

                        <!-- 'participation_id'が１なら参加。 -->
                        <td><?= ($row['participation_id'] === "1") ? '参加！' : '不参加で。。。'; ?></td>
                        <td><?= $row['comment'] ?></td>

                        <!-- 編集リンクボタン -->
                        <td><a href="edit/<?= $row['user_id'] ?>" class="btn btn-link">編集</a></td>
                        <td>
                            <!-- 削除リンクボタン -->
                            <form action="delete/<?= $row['user_id'] ?>" method="POST">
                                <input type=submit value=削除 class="btn btn-link">
                                <!-- トークンをdelete.phpに送る -->
                                <input type=hidden name="token" value="<?= htmlspecialchars(($_SESSION['token']), ENT_QUOTES, 'UTF-8'); ?>">
                            </form>
                        </td>
                    </tr>
                </tbody>
            <?php endwhile; ?>
        </table>
        <form action="./add.php" method="GET">
            <input type=hidden name="" value="">

            <!-- アンケート画面に遷移する -->
            <td><input type=submit value=アンケートに回答する class="btn btn-secondary"></td>
        </form>
    </div>



    <!-- Bootstrapの最後のリンクを読み込む -->
    <?php include('./Bootstrap_second.php'); ?>



</body>

</html>