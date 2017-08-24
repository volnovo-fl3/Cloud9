<?php
$name = 'ジェラール皇帝';
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>変数の使用例</title>
        <style> h1 { color: red; } </style>
    </head>

    <body>
        <h1>変数の使用例</h1>
        <p>ようこそ<?php print $name; ?> さん。</p>
        <ul>
            <li><a href="#"><?php print $name; ?> さんのマイページ</a></li>
        </ul>
    </body>
    
</html>