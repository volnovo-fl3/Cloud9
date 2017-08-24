<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>スーパーグローバル変数</title>
    </head>

    <body>
    <?php
    if (isset($_GET['my_name']) === TRUE) {
        print 'ここに入力した名前を表示: '. htmlspecialchars($_GET['my_name'], ENT_QUOTES, 'UTF-8');
        // htmlspecialchars は、HTMLで特殊な意味を持つ文字(特殊文字)を表示可能な形式に変換する関数です
    } else {
        '名前が送られていません';
    }
    ?>
    </body>
</html>