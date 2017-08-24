<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>条件分岐 課題2</title>
    </head>

    <body>
    <?php
    // 0〜2のランダムな数値を2つ取得し、それぞれ変数$rand1と$rand2へ代入
    $rand1 = mt_rand(0, 2);
    $rand2 = mt_rand(0, 2);
    
    // ランダムな数値$rand1と$rand2をそれぞれ表示
    print 'rand1: ' . $rand1;
    print "<br>";
    print 'rand2: ' . $rand2;
    print "<br>";
    print "<br>";
    
    // $rand1と$rand2のどちらのほうが大きいか比較し、結果を表示
    if ($rand1 == $rand2) {
        print 'rand1とrand2は同じ値';
    } else if ($rand1 > $rand2) {
        print 'rand1の方が大きい値';
    } else if ($rand1 < $rand2) {
        print 'rand2の方が大きい値';
    }
    ?>
    </body>
</html>