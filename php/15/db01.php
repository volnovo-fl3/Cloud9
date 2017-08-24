<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>DB操作01</title>
    </head>

    <body>
        <?php
        
        $host = 'localhost';
        $username = 'tanakanoboru';
        $password = ''; //設定していないため、空
        $dbname = 'camp';
        $charset  = 'utf8';
        
        // MySQL用のDSN文字列
        $dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
        
        try {
            // データベースに接続
            $dbh = new PDO($dsn, $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            echo 'データベースに接続しました';            
        } catch (PDOException $e) {
            echo '接続できませんでした。理由：'.$e->getMessage();
        }
        
        ?>
    </body>
</html>