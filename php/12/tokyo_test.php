<?php
    // 読み込むファイル名の指定
    $file_name = "tokyo.csv";
    // ファイルポインタを開く
    $fp = fopen( $file_name, 'r' );
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>fgetcsv / テーブルテスト</title>
        <!-- 表形式での表示の参考にしました -->
        <!-- http://www.kanzaki.com/docs/html/htminfo16.html -->
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
        
        <?php
            // データが無くなるまでファイル(CSV)を１行ずつ読み込む
            while( $ret_csv = fgetcsv( $fp, 256 ) ) {
            // 読み込んだ行(CSV)を表示する
                print entity($ret_csv[2]) . " ";
                print entity($ret_csv[6]) . " ";
                print entity($ret_csv[7]) . " ";
                print entity($ret_csv[8]);
                echo("<br />");
            }
        
            // 開いたファイルポインタを閉じる
            fclose( $fp );
            
            function entity($str) {
                return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
            }
        ?>
        <br>
        <table>
            <caption>身近な果物</caption>
            <tr>
                <th>名称</th>
                <th>味の特徴</th>
                <th>色</th>
            </tr>
            <tr>
                <td>りんご</td>
                <td>甘酸っぱい</td>
                <td>おおむね赤</td>
            </tr>
            <tr>
                <td>なつみかん</td>
                <td>かなり酸っぱいと思う</td>
                <td>たいてい黄色</td>
            </tr>
        </table>        
        
    </body>
</html>
