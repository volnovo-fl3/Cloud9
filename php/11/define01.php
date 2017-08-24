<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>定数1</title>
    </head>

    <body>
    <?php
    define('TAX', 1.05); //消費税
    define('TAX', 1.08); //消費税
    $price = 100;
    
    print $price . '円の税込み価格は' . $price * TAX . '円です';
    ?>
    </body>
</html>