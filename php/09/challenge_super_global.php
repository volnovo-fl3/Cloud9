<?php
// 変数
$name = '';
$gender = '';
$receive = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name']) === TRUE ) {
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($_POST['gender']) === TRUE ) {
        $gender = htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($_POST['receive']) === TRUE ) {
        $receive = htmlspecialchars($_POST['receive'], ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>スーパーグローバル変数 課題1</title>
    </head>

    <body>
        <h1>課題1<h1>
        <h2>-------------- 入力 --------------</h2>
        <form method="post">
            <p><label>お名前: <input type="text" name="name"></label></p>
            <p>
                <label>性別: 
                    <input type="radio" name="gender" value="男" <?php if ($gender === '男') { print 'checked'; } ?>>男
                    <input type="radio" name="gender" value="女" <?php if ($gender === '女') { print 'checked'; } ?>>女
                </label>
            </p>
            <p><input type="checkbox" name="receive" value="OK">お知らせメールを受け取る</p>
            <input type="submit" value="送信">
        </form>
        <br>
        <h2>-------------- 参照値 --------------</h2>
        <?php
        print var_dump($name);
        print var_dump($gender);
        print var_dump($receive);
        ?>
        <br>
        <h2>-------------- 出力 --------------</h2>
        <!-- 名前 -->
        <?php if ($name != '') { ?>
        <p>お名前: <?php print $name; ?></p>
        <?php } ?>
        <!-- 性別 -->
        <?php if ($gender === '男' || $gender === '女') { ?>
        <p>性別: <?php print $gender; ?></p>
        <?php } ?>
        <!-- お知らせ -->
        <?php if ($receive === 'OK') { ?>
        <p>お知らせメールを受け取る</p>
        <?php } ?>
    </body>
</html>