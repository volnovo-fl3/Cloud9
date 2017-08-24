<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>BBStest</title>
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
            
            //SQL文を作成
            $sql = 'select * from test_bbs';
            
            //SQL文を実行する準備
            $stmt = $dbh->prepare($sql);
            
            //SQLを実行
            $stmt->execute();
            
            //レコードの取得
            $rows = $stmt->fetchAll();
            

            /* -------------------------
            / $rowsにデータがある時！
            / 投稿内容を表示する
            / ------------------------ */
            if (isset($rows) === TRUE) {
                print "<ul>";
                foreach ($rows as $key => $row) {
                    print "<li>";
                    print $row[1] . '：' . $row[2]. '　- ' . $row[3];
                    print "</li>";
                }
                print "</ul>";
            } else {
            /* -------------------------
            / $rowsにデータがない時！
            / ------------------------ */
                print '書き込みがありません。';
            }
            
            
        } catch (PDOException $e) {
            echo '接続できませんでした。理由：'.$e->getMessage();
        }
        ?>
        
    </body>
</html>