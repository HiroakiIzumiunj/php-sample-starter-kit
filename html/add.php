<?php

session_start();

function validateToken()
{
        if (
            empty($_SESSION['token']) ||
            $_SESSION['token'] !== filter_input(INPUT_POST, 'token')
        ){
            exit('Invalid post request');
        }
}

// POST のときはデータの投入を実行
//
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    validateToken();
    // データベースへの接続
    $link = mysqli_connect('db', 'root', 'secret', 'sample');
    if ($link == null) {
        die("データベースの接続に失敗しました。");
    }
    
    // データの投入
    $sql = "INSERT INTO `questionnaire` (`username`, `participation_id`, `comment`) VALUES ('"  . $_POST['username'] . "', " . $_POST['participation_id'] . ", '" . $_POST['comment'] . "');"; 
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
    <?php include('./Bootstrap_first.php'); ?>
    <title>アンケート入力</title>
</head>
<body>
<div class="container">
    <h1 class="my-3">新人歓迎会参加アンケート</h1>
    <form method="POST" action="./add.php">
        <div class="form-group">
            <label for="username">氏名</label>
            <input type="text" name="username" class="form-control"/>
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
            <textarea name="comment" class="form-control"></textarea>
        </div>
        <div>
            <a href='./index.php' class="btn btn-secondary">戻る</a>
            <button type="submit" class="btn btn-secondary">送信</button>
        </div>
        <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']); ?>">
    </form>
</div>
    <?php include('./Bootstrap_second.php'); ?>
</body>
</html>