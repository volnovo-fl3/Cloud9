<?php
$filename = './tokyo.csv';

function entity($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<!-- テスト用ファイルは『tokyo_test.php』 -->

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ファイル操作 課題2</title>
        <style>
        table {
        	border-collapse: collapse;
        }
        td, th{
        	border: solid 1px;
        	padding: 0.5em;
        }
        </style>
    </head>

    <body>
        <p>以下にファイルから読み込んだ住所データを表示</p>
        
        <table>
            <caption>住所データ</caption>
            <tr>
                <th>郵便番号</th>
                <th>都道府県</th>
                <th>市町村</th>
                <th>町域</th>
            </tr>
            <?php
            if (is_readable($filename) === TRUE) {
                if (($fp = fopen($filename, 'r')) !== FALSE) {
                    while (($tmp = fgetcsv($fp)) !== FALSE) {
            ?>
            <tr>
                <td><?php print entity($tmp[2])?></td>
                <td><?php print entity($tmp[6])?></td>
                <td><?php print entity($tmp[7])?></td>
                <td><?php print entity($tmp[8])?></td>
            </tr>
            <?php
                    }
                    fclose($fp);
                }
            } else {
                print 'ファイルがありません';
            }
            ?>
        </table>        
    </body>
</html>