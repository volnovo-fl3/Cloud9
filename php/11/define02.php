<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>定数2</title>
    </head>

    <body>
    <?php
    define('TAX', 1.05); //消費税
    $price = 100;
    
    //print $price . '円の税込み価格は' . $price * TAX . '円です';
    print $price . '円の税込み価格は' . price_before_tax($price). '円です';
    
    function price_before_tax($price) {
        return $price * TAX;
    }
    ?>
    </body>
</html>