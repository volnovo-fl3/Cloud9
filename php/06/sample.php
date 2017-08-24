<?php
$class01 = array('鈴木','佐藤','斎藤','伊藤','杉内');
$class02[0] = '鈴木';
$class02[1] = '佐藤';
$class02[2] = '斎藤';
$class02[3] = '伊藤';
$class02[4] = '杉内';
$class03 = ['鈴木','佐藤','斎藤','伊藤','杉内'];
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>配列サンプル</title>
    </head>

    <body>
        <h1>class01</h1>
        <?php
        print "$class01[0] $class01[1] $class01[2] $class01[3] $class01[4]";
        ?>
        
        <h1>class02</h1>
        <?php
        print "$class02[0] $class02[1] $class02[2] $class02[3] $class02[4]";
        ?>

        <h1>class03</h1>
        <?php
        print "$class03[0] $class03[1] $class03[2] $class03[3] $class03[4]";
        ?>
    </body>
</html>