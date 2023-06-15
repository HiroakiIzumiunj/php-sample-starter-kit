<?php

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
    <title>アンケート入力</title>
</head>
<body>
    <table border='1'>
        <tr>
            <th>ID</th>
            <th>氏名</th>
            <th>参加するかどうか</th>
            <th>コメント</th>
            <th> </th>
        </tr>

    <?php while($row = mysqli_fetch_assoc($res)) : ?>
        <tr>
            <td><?= $row['userid'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['participation_id'] ?></td>
            <td><?= $row['comment'] ?></td>
        
            <form action="./edit.php" method="POST">
            <input type=hidden name="userid" value=<?= $row["userid"] ?>>
            <td><input type=submit value=更新></td>
            </form>

            <form action="./delete.php" method="POST">
            <input type=hidden name="userid" value=<?= $row["userid"] ?>>
            <td><input type=submit value=削除></td>
            </form>

        </tr>
    <?php endwhile; ?>
    
    
    </table>
    <!-- 接続断 -->
    <?php mysqli_close($link) ?>
</body>
</html>