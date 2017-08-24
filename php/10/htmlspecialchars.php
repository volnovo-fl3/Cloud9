<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>htmlspecialchars</title>
    </head>

    <body>
        <?php
        // 好きなhtmlを入力（例：<h2>PHP勉強中です！！</h2>）
        $str = '<h2>先帝の無念を晴らす！</h2>';
        
        // htmlspecialcharsを使わない場合
        print $str;
        
        // htmlspecialcharsを使う場合
        print htmlspecialchars($str, ENT_QUOTES);
        ?>
    </body>
</html>