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
    $sql ="UPDATE questionnaire SET username = '". $_POST['username'] . "', participation_id = '" . $_POST['participation_id'] . "', comment = '" . $_POST['comment'] . "' WHERE userid = '" . $_POST['userid'] . "';";

    mysqli_query($link, $sql);

    // ホーム画面にリダイレクト
    header('Location: http://' . $_SERVER['HTTP_HOST']);
}
else{
    // 接続処理
    $link = mysqli_connect('db', 'root', 'secret', 'sample');
    if ($link == null) {
    die("データベースの接続に失敗しました。");
    }

    // 文字コード
    mysqli_set_charset($link, 'utf8');

    // SELECT文を実行
    $sql = "SELECT username , participation_id , comment , userid FROM questionnaire where userid ='" . $_GET['userid'] . "'";

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
    <title>アンケート入力</title>
</head>
<body>
    <h1 class="my-3">新人歓迎会参加アンケート</h1>
    <form method="POST" action="./edit.php">
        <div>
            <label for="username">氏名</label>
            <input type="text" name="username" value=<?=  $row['username'] ?>/>
        </div>
        <div>
            <label for="participation_id">新人歓迎会に参加しますか？:</label>
            
            <select name="participation_id">
            <?php
                if($row['participation_id'] === "1" ) {
                    $first_p_id = "1";
                    $second_p_id = "2";
                    $first_status = "参加！";
                    $second_status = "不参加で。。。"; 
                }
                else{
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
        <div>
            <label for="comment">コメント:</label>
            <textarea name="comment"><?= $row['comment'] ?></textarea>
        </div>
        <div>
        <input type="hidden" name="userid" value=<?= $row['userid'] ?> />
        </div>
        <div>
            <button onclick="location.href='./index.php'">戻る</a>
            <button type="submit">送信</button>
        </div>
    </form>
</body>
</html>
