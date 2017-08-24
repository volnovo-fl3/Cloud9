<?php
// 変数初期化
$name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name']) === TRUE ) {
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>スーパーグローバル変数 課題2</title>
    </head>

    <body>
        <h1>課題2<h1>
        <h2>入力</h2>
        <form method="post" action="practice_global_receive_elementary.php">
            <label>名前: <input type="text" name="name"></label>
            <input type="submit" value="送信">
        </form>
    </body>
</html>