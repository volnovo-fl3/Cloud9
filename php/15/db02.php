<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>DB操作02</title>
    </head>

    <body>
        <?php
        
        $host = 'localhost';
        $username = 'tanakanoboru';
        $password = '';
        $dbname = 'camp';
        $charset  = 'utf8';
        
        // MySQL用のDSN文字列
        $dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
        
        try {
            // データベースに接続
            $dbh = new PDO($dsn, $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            $sql = 'select * from test_post';
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            
            var_dump($rows);
            
        } catch (PDOException $e) {
            echo '接続できませんでした。理由：'.$e->getMessage();
        }
        
        ?>
    </body>
</html>