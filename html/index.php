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

    echo "<table border='1'>";
    echo "    <tr>";
    echo "        <th>ID</th>";
    echo "        <th>氏名</th>";
    echo "        <th>参加するかどうか</th>";
    echo "        <th>コメント</th>";
    echo "        <th> </th>";
    echo "    </tr>";

    while($row = mysqli_fetch_assoc($res)) {
        echo "<tr>";
        echo "  <td>{$row['userid']}</td>";
        echo "  <td>{$row['username']}</td>";
        echo "  <td>{$row['participation_id']}</td>";
        echo "  <td>{$row['comment']}</td>";
        echo "  <td><a href = "."edit.php".">編集</a> <a href = "."delete.php".">削除</a></td>";
        echo "</tr>";
    }
    
    echo "</table>";
    // 接続断
    mysqli_close($link);
?>