<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>繰り返し 課題2 (foreach)</title>
    </head>

    <body>
    <?php
    $class = ['ガリ勉' => '鈴木', '委員長' => '佐藤', 'セレブ' => '斎藤', 'メガネ' => '伊藤', '女神' => '杉内'];
    ?>
    <?php foreach ($class as $key => $name) { ?>
    <p><?php print '『'.$key.'』'.$name ?></p>
    <?php } ?>
    <br>
    <p>...女神！？</p>
    </body>
</html>