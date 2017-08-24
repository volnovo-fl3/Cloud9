<?php
$filename = './challenge_log.txt';
date_default_timezone_set('Asia/Tokyo');

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $comment = date('m月d日 H:i:s') . ' ' . $_POST['tweet']."\n";
    if (($fp = fopen($filename, 'a')) !== FALSE) {
        if (fwrite($fp, $comment) === FALSE) {
            print 'ファイル書き込み失敗:  ' . $filename;
        }
    fclose($fp);
    }
}

$data = [];

if (is_readable($filename) === TRUE) {
    if (($fp = fopen($filename, 'r')) !== FALSE) {
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
        }
        fclose($fp);
    }
} else {
    $data[] = 'ファイルがありません';
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ファイル操作 課題1</title>
    </head>

    <body>
        <h1>つぶやいたったー</h1>
        <form method="post">
            <label>発言: </label>
            <input type="text" name="tweet">
            <input type="submit" name="submit" value="送信">
        </form>
        <p>発言一覧</p>
        <?php foreach ($data as $read) { ?>
        <p><?php print $read; ?></p>
        <?php } ?>
    </body>
</html>