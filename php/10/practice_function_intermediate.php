<?php
// $valueの値を定義
$value = 55.5555;

// 小数切り捨て値の処理
$floor = floor($value);

// 小数切り上げの処理を記述
$ceil = ceil($value);

// 小数四捨五入の処理を記述
$round = round($value);

// 小数第二位で四捨五入の処理を記述
$round_hundredth = round($value, 2);

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>関数 課題</title>
    </head>
    
    <body>
        <!-- 参考にしました -->
        <!-- http://www.php-ref.com/bapi/05_round.html -->
        <p>元の値: <?php print $value; ?></p>
        <p>小数切り捨て: <?php print $floor; ?></p>
        <p>小数切り上げ: <?php print $ceil; ?></p>
        <p>小数四捨五入: <?php print $round; ?></p>
        <p>小数第二位で四捨五入: <?php print $round_hundredth; ?></p>
    </body>
</html>