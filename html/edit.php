<?php
// 接続処理
    $link = mysqli_connect('db', 'root', 'secret', 'sample');
    if ($link == null) {
        die("データベースの接続に失敗しました。");
    }

    // 文字コード
    mysqli_set_charset($link, 'utf8');

    // SELECT文を実行
    $sql = "SELECT username,participation_id,comment FROM questionnaire where userid ='$userid'";
    $res = mysqli_query($link, $sql);

    // 結果の行を取得
    $row = mysqli_fetch_assoc($res);

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
    <form method="POST" action="./add.php">
        <div>
            <label for="username">氏名</label>
            <input type="text" name="username" value=<?= $row['username'] ?>/>
        </div>
        <div>
            <label for="participation_id">新人歓迎会に参加しますか？:</label>
            <?php 
                $val = ($row['participation_id'] === "1" )? '参加！' : '不参加で。。。'; 
                echo $val;
            ?>
            <select name="participation_id">
                <option value="1">参加！</option>
                <option value="2">不参加で。。。</optiohn>
            </select>
        </div>
        <div>
            <label for="comment">コメント:</label>
            <textarea name="comment"><?= $row['comment'] ?></textarea>
        </div>
        <div>
            <a href="/">戻る</a>
            <button type="submit">送信</button>
        </div>
    </form>
</body>
</html>
