<?php
$i = 1900;
$date = date('Y');
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ループ(while)の使用例</title>
    </head>

    <body>
        <form action="#">
            生まれた西暦を選択してください
            <select name "born_year">
            <?php
            // 1900年〜現在の西暦までをループで処理する
            while ($i <= $date) {
            ?>
                <option value="<?php print $i; ?>"><?php print $i; ?>年</option>
            <?php
                $i++;
            }
            ?>
            </select>
        </from>
    </body>
</html>