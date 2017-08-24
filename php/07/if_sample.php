<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ifの使用例</title>
    </head>

    <body>
        <?php
        $rand = mt_rand(1,10);
        ?>
        <h1>抽選システム</h1>
        <p>値は：<?php print $rand; ?></p>
        <?php if ($rand <= 3) { ?>
        <h3>当たり！！</h3>
        <?php } else { ?>
        <h3>残念でした・・また引いてね</h3>
        <?php } ?>
    </body>
</html>