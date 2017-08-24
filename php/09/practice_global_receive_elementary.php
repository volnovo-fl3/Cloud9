<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>スーパーグローバル変数 課題2</title>
    </head>

    <body>
        <h1>課題2<h1>
        <h2>表示</h2>
        <?php
        $name = $_POST['name'];
        if ($name == '') {
            print "名前を入力してください";
        } else {
            print "ようこそ $name さん";
        } ?>
        <br>
        <p>-----------------------------------------</p>
        <?php
        var_dump($name);
        ?>
    </body>
</html>