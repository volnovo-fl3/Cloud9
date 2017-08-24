<?php
/* ---------- データベース接続用 ---------- */
$host = 'localhost';
$username = 'tanakanoboru';
$password = '';
$dbname = 'camp';
$charset  = 'utf8';

// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;

/* ------------------------------ */
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>データ操作(query)</title>
    </head>

    <body>
        <?php
        try {
            //データベースに接続
            $dbh = new PDO($dsn, $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            //SQL文を作成
            $sql = 'select * from test_post';
            
            //SQLを実行
            $res  = $dbh->query($sql);
            
            //レコードの取得
            $rows = $res->fetchAll();
            var_dump($rows);
        } catch (PDOException $e) {
            echo '接続できませんでした。理由：'.$e->getMessage();
        }
        ?>
    </body>
</html>