<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>条件分岐2</title>
    </head>

    <body>
    <h2>0~10の乱数のうち、6以上なら合格</h2>
    <?php
    // 0〜10の乱数
    $rand = mt_rand(0, 10);
    print "乱数：$rand";

    // 6以上の場合
    if ($rand >= 6) {
        print "<br><h3>当たり</h3>";
    // 6未満の場合
    } else {
        print "<br><h3>はずれ</h3>";
    }
    ?>
    </body>
</html>