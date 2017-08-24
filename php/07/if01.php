<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>条件分岐1</title>
    </head>

    <body>
    <h2>0~100の乱数のうち、60以上なら合格</h2>
    <?php
    // 0〜100の乱数
    $rand = mt_rand(0, 100);
    print "乱数：$rand";

    // 60以上の場合
    if ($rand >= 60) {
        print "<br><h3>合格</h3>";
    }
    ?>
    </body>
</html>