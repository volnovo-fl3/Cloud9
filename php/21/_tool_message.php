<?php
/* -----------------------------------------------
リロード対策で

  header('location: URL');
  exit;

を使用していると、新規登録完了などのメッセージを設定しても
処理完了と同時に初期化されてしまうため
さらにその対策としてメッセージ専門のファイルを作ってみました。
-----------------------------------------------*/
print 'ファイル操作開始';

$filename = './tool_message.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $comment = $_POST['comment']."\n";
  if (($fp = fopen($filename, 'a')) !== FALSE) {
    if (fwrite($fp, $comment) === FALSE) {
      print 'ファイル書き込み失敗:  ' . $filename;
    }
    fclose($fp);
  }
}

$data = [];

$file = file($filename);
unset($file[1]);
file_put_contents($filename, $file);

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
  <title>ファイル操作</title>
</head>
<body>
  <h1>ファイル操作</h1>
  <form method="post">
    <input type="text" name="comment">
    <input type="submit" name="submit" value="送信">
  </form>
  <p>以下に<?php print $filename; ?>の中身を表示</p>
<?php foreach ($data as $read) { ?>
  <p><?php print $read; ?></p>
<?php } ?>
</body>
</html>