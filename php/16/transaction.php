<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>トランザクション</title>
    </head>

    <body>
        <?php
        /*--------------------------------------------------------
        /* 変数初期化
        /*--------------------------------------------------------*/
        $host = 'localhost';
        $username = 'tanakanoboru';
        $password = '';
        $dbname = 'camp';
        $charset  = 'utf8';
        
        // MySQL用のDSN文字列
        $dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
        
        try {
            $dbh = new PDO($dsn, $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            /*--------------------------------------------------------
            /* トランザクション開始
            /*--------------------------------------------------------*/
            $dbh->beginTransaction();
            
            try{
                // 現在日時を取得
                $now_date = date('Y-m-d H:i:s');
                
                // 商品情報テーブルにデータ作成
                $sql = 'insert into test_item_master(id, item_name, price, create_datetime) values(1, "PHP入門", 2000, "' . $now_date . '" );';
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                
                // 在庫情報テーブルにデータ作成
                $sql = 'insert into test_item_stock(item_id, stock, create_datetime) values(1, 100, "' . $now_date . '" );';
                $stmt = $dbh->prepare($sql);
                $stmt->execute();                
                
                
                /*--------------------------------------------------------
                /* コミット処理
                /*--------------------------------------------------------*/
                $dbh->commit();
                echo 'データが登録できました';
                
                
            } catch (PDOException $e) {
                
                /*--------------------------------------------------------
                /* ロールバック処理
                /*--------------------------------------------------------*/
                $dbh->rollback();
                throw $e; // 例外をスロー
            }
            
        } catch (PDOException $e) {
            echo 'データベース処理でエラーが発生しました。理由：'.$e->getMessage();
        }
        
        ?>
    </body>
</html>