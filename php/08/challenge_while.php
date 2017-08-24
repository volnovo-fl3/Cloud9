<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>繰り返し 課題1 (while)</title>
    </head>

    <body>
    <?php
    $i = 1;
    $sum = 0;
    
    while($i <= 100){
        if ($i % 3 == 0){
            $sum += $i;
        }
        $i++;
    }
    
    print "合計: $sum";
    ?>
    </body>
</html>