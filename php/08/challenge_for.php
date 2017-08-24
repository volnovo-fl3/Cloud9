<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>繰り返し 課題1 (for)</title>
    </head>

    <body>
    <?php
    $sum = 0;
    
    for($i = 1; $i <= 100; $i++){
        if ($i % 3 == 0){
            $sum += $i;
        }
    }
    
    print "合計: $sum";
    ?>
    </body>
</html>