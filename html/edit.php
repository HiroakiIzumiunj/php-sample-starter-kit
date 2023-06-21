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

if ($_SERVER['REQUEST_METHOD'] === "POST"){
    
    if((mb_strlen($_POST['username'])<1) || (mb_strlen($_POST['username'])>20)){
    
        $err_username = "ユーザー名を20文字以内で入力して下さい。";

        $username_border_color = "red";
    
        $err_username_flag = true;
        
    }
    else   
    {
        $err_username_flag = false;
    }

    if((mb_strlen($_POST['comment'])>100)){
    
        $err_comment = "ユーザーは100文字以内で入力して下さい。";
        
        $comment_border_color = "red";
    
        $err_comment_flag = true;
        
    }
    else   
    {
        $err_comment_flag = false;
    } 
        
}

ini_set('display_errors', 0);

// 接続処理
    // POST のときはデータの投入を実行
if ($_SERVER['REQUEST_METHOD'] === "POST" && !$err_username_flag && !$err_comment_flag) {

    validateToken();
    // データベースへの接続
    $link = mysqli_connect('db', 'root', 'secret', 'sample');
    if ($link == null) {
    die("データベースの接続に失敗しました。");
    }

    // 文字コード
    mysqli_set_charset($link, 'utf8');

    $userid = mysqli_real_escape_string($link, $_POST['userid']);

    // SELECT文を実行
    $sql ="UPDATE questionnaire SET username = '". $_POST['username'] . "', participation_id = '" . $_POST['participation_id'] . "', comment = '" . $_POST['comment'] . "' WHERE userid = '$userid';";

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

    $userid = mysqli_real_escape_string($link, $_GET['userid']);

    // SELECT文を実行
    $sql = "SELECT username , participation_id , comment , userid FROM questionnaire where userid ='$userid'";

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
    <?php include('./Bootstrap_first.php'); ?>
    <title>アンケート入力</title>
</head>
<style>

    .err_username_form{
        border-color:<?= $username_border_color; ?>
    }

    .err_comment_form{
        border-color:<?= $comment_border_color; ?>
    }



</style>
<body>
<div class="container">
    <h1 class="my-3">新人歓迎会参加アンケート</h1>
    <form method="POST" action="./edit.php">
        <div class="form-group">
            
                <label for="username">氏名</label>
                
                <input type="text" name="username" class="form-control err_username_form" value=<?=  $row['username'] ?>/>
                <i class="bi bi-exclamation-circle"></i>
        
                <FONT COLOR="red"><?= isset($err_username) ? $err_username : null ?></FONT>
            
        </div>
        <div class="form-group">
            <label for="participation_id">新人歓迎会に参加しますか？:</label>
            
            <select name="participation_id" class="form-control">
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
        <div class="form-group">
            
                <label for="comment">コメント:</label>
                <textarea name="comment" class="form-control err_comment_form"><?= $row['comment'] ?></textarea>
                <i class="bi bi-exclamation-circle"></i>
            
                <FONT COLOR="red"><?= isset($err_comment) ? $err_comment : null ?></FONT>
            
        </div>
        <div>
        <input type="hidden" name="userid" value=<?= $row['userid'] ?> />
        </div>
        <div>
            <a href='./index.php' class="btn btn-secondary">戻る</a>
            <button type="submit" class="btn btn-secondary">送信</button>
        </div>
        <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']); ?>">
    </form>
    <?php include('./Bootstrap_second.php'); ?>
    </div>
    
</body>
</html>
