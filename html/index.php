<?php
    session_start();

    createToken();

    function createToken()
    {
        if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
        }
    }
   
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
<h1 class="my-3">新人歓迎会参加アンケート結果</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">氏名</th>
            <th scope="col">参加するかどうか</th>
            <th scope="col">コメント</th>
            <th scope="col" colspan="2"> </th> 
        </tr>
        </thead>

    <?php while($row = mysqli_fetch_assoc($res)) : ?>
        <tbody>
        <tr>
            <td><?= $row['userid'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?php
                $val = ($row['participation_id'] === "1" )? '参加！' : '不参加で。。。';
                echo $val;
            ?></td>
            <td><?= $row['comment'] ?></td>
        
            
            <form action="./edit.php" method="GET">
            <input type=hidden name="userid" value=<?= $row["userid"] ?>/>
            <td><input type=submit value=編集 class="btn btn-link"></td>
            </form>

            <form action="./delete.php" method="POST">
            <input type=hidden name="userid" value=<?= $row["userid"] ?>>
            <td><input type=submit value=削除 class="btn btn-link"></td>
            </form>

            <!-- トークンをdelete.phpに送る -->
            <form action="./delete.php" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']); ?>">
            </form>


        </tr>
    <?php endwhile; ?>
    </tbody>
    </table>
    <form action="./add.php" method="GET">
    <input type=hidden name="" value="">
    <!-- アンケート画面に遷移する -->
    <td><input type=submit value=アンケートに回答する class="btn btn-secondary"></td>
    </form>


    <!-- 接続断 -->
    <?php mysqli_close($link) ?>
    <?php include('./Bootstrap_second.php'); ?>
    </div>

    
</body>
</html>
