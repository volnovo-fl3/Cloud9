<?php
date_default_timezone_set('Asia/Tokyo');

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

// 商品登録用の変数
$img_dir = './img/';
$data = [];
$err_msg = [];
$new_img_filename = '';   // アップロードした新しい画像ファイル名


/*--------------------------------------------------------
/* 押されたボタンを判別
/*--------------------------------------------------------*/
$process_kind = "";

if ( isset($_POST['process_kind']) ) {
  $process_kind = $_POST['process_kind'];
}


/*--------------------------------------------------------
/* エラーチェック
/*--------------------------------------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    unset($err_msg);
    
    /*------------- 新規登録時 -------------*/
    if ($process_kind === 'insert_item'){
        
        /*--------------------------------------------------------
        /* 商品名チェック
        /*--------------------------------------------------------*/
        if (isset($_POST['drinkname']) !== TRUE || mb_strlen($_POST['drinkname']) === 0){
            $err_msg[] = '商品名を入力してください。';
        }
    
        /*--------------------------------------------------------
        /* 価格チェック
        /*--------------------------------------------------------*/
        if (isset($_POST['drinkprice']) !== TRUE || mb_strlen($_POST['drinkprice']) === 0){
            $err_msg[] = '価格を入力してください。';
        }
    
        /*--------------------------------------------------------
        /* 個数チェック
        /*--------------------------------------------------------*/
        if (isset($_POST['drinkstock']) !== TRUE || mb_strlen($_POST['drinkstock']) === 0){
            $err_msg[] = '個数を入力してください。';
        }
    
        /*--------------------------------------------------------
        /* UPする画像チェック
        /*--------------------------------------------------------*/
        // HTTP POST でファイルがアップロードされたかどうかチェック
        if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
            
             // 画像の拡張子を取得
             $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
             
             // 指定の拡張子であるかどうかチェック
             if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png'){
                 
                // 保存する新しいファイル名の生成（ユニークな値を設定する）
                $new_img_filename = sha1(uniqid(mt_rand(), true)). '.' . $extension;
    
                // 同名ファイルが存在するかどうかチェック
                if (is_file($img_dir . $new_img_filename) !== TRUE) {
    
                    //ここではアップロード処理は行わない
                }
    
            } else {
                $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEGのみ利用可能です。';
            }
         
        } else {
            $err_msg[] = 'ファイルを選択してください';
        }
        
        
    /*------------- 個数更新時 -------------*/
    } else if ($process_kind === 'update_stock'){
        if (isset($_POST['drinkstock_update']) !== TRUE || mb_strlen($_POST['drinkstock_update']) === 0){
            $err_msg[] = '個数を入力してください。';
        }
    }
}


/*--------------------------------------------------------
/* データベースに接続
/*--------------------------------------------------------*/
try {
    // データベースに接続
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    $accessdatetime = date('Y-m-d H:i:s');
    
    
    /*-------- 新規登録 --------*/
    if ($process_kind === 'insert_item'){
        
        // エラーがなければデータを新規登録する
        if (empty($err_msg) === TRUE && $_SERVER['REQUEST_METHOD'] === 'POST' ){
            
            // アップロードされたファイルを指定ディレクトリに移動して保存
            if (move_uploaded_file($_FILES['new_img']['tmp_name'], $img_dir . $new_img_filename) !== TRUE) {
                $err_msg[] = 'ファイルアップロードに失敗しました';
            } else {
                
                // トランザクション開始
                $dbh->beginTransaction();
                
                try {
                    $sql =
                    '
                    INSERT INTO test_drink_master
                    (
                        drink_name
                        , price
                        , img
                        , create_datetime
                    )
                    VALUES
                    (
                        ?
                        , ?
                        , ?
                        , ?
                    )
                    ';
                    
                    $stmt = $dbh->prepare($sql);
                    
                    $stmt->bindValue(1, $_POST['drinkname'], PDO::PARAM_STR);
                    $stmt->bindValue(2, $_POST['drinkprice'], PDO::PARAM_INT);
                    $stmt->bindValue(3, $new_img_filename, PDO::PARAM_STR);
                    $stmt->bindValue(4, $accessdatetime, PDO::PARAM_STR);
                    
                    $stmt->execute();
                    
                    // 登録した商品のIDを取得
                    $new_drinkid = $dbh->lastInsertId();

                    $sql =
                    '
                    INSERT INTO test_drink_stock
                    (
                        drink_id
                        , stock
                        , create_datetime
                        , update_datetime
                    )
                    VALUES
                    (
                        ?
                        , ?
                        , ?
                        , ?
                    )
                    ';
                    
                    $stmt = $dbh->prepare($sql);
                    
                    $stmt->bindValue(1, $new_drinkid, PDO::PARAM_INT);
                    $stmt->bindValue(2, $_POST['drinkstock'], PDO::PARAM_INT);
                    $stmt->bindValue(3, $accessdatetime, PDO::PARAM_STR);
                    $stmt->bindValue(4, $accessdatetime, PDO::PARAM_STR);
                    
                    $stmt->execute();
                    
                    // コミット処理
                    $dbh->commit();

                    
                    /*---------- リロード対策 ----------*/
                    header('location: https://codeincubate-tanakanoboru.c9users.io/php/16/manage.php');
                    exit;
                    /*----------------------------------*/
    
                } catch (PDOException $e) {
                    // ロールバック処理
                    $dbh->rollback();
                    // 例外をスロー
                    throw $e;
                }
            }
        }
        
    /*-------- 個数を更新 --------*/
    } else if ($process_kind === 'update_stock'){
        
        // 押されたボタンのドリンクIDを取得
        $drinkid = $_POST['drinkid'];
        
        // エラーがなければデータを更新する
        if (empty($err_msg) === TRUE && $_SERVER['REQUEST_METHOD'] === 'POST' ){
            
            // トランザクション開始
            $dbh->beginTransaction();
            
            try {
                $sql =
                '
                UPDATE
                    test_drink_stock
                SET
                    stock = ?
                    , update_datetime = ?
                WHERE
                    drink_id = ?;
                ';
                
                $stmt = $dbh->prepare($sql);

                $stmt->bindValue(1, $_POST['drinkstock_update'], PDO::PARAM_INT);
                $stmt->bindValue(2, $accessdatetime, PDO::PARAM_STR);
                $stmt->bindValue(3, $drinkid, PDO::PARAM_INT);

                $stmt->execute();
                
                // コミット処理
                $dbh->commit();

                /*---------- リロード対策 ----------*/
                header('location: https://codeincubate-tanakanoboru.c9users.io/php/16/manage.php');
                exit;
                /*----------------------------------*/

            } catch (PDOException $e) {
                // ロールバック処理
                $dbh->rollback();
                // 例外をスロー
                throw $e;
            }
        }

    }
    
    // レコードを取得
    $sql =
        '
        SELECT
            dm.drink_id as drink_id
            , drink_name
            , price
            , img
            , stock
        FROM
            test_drink_master as dm
            inner join test_drink_stock as ds on dm.drink_id = ds.drink_id;
        ';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
        $data[] = $row;
    }
    
} catch (PDOException $e) {
    // 接続失敗した場合
    $err_msg['db_connect'] = 'DBエラー：'.$e->getMessage();
}

/*--------------------------------------------------------
/* HTML文字化け回避(エスケープ)用の関数
/*--------------------------------------------------------*/
function entity($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>データ操作 課題</title>
        <style>
            table {
                width: 660px;
                border-collapse: collapse;
            }
            table, tr, th, td {
                border: solid 1px;
                padding: 10px;
                text-align: center;
            }
        </style>
    </head>

    <body>
        <h1>自動販売機管理ツール</h1>
        <hr>
        <h2>商品新規登録</h2>
        
    <?php
        if (empty($err_msg) === FALSE) {
    ?>
            <ul>
    <?php
            foreach ($err_msg as $key => $error) {
    ?>
                <li><p>
                <?php print entity($error); ?>
                </p></li>
    <?php
            }
    ?>
            </ul>
    <?php
        }
    ?>        
        
        
        <form method="post" enctype="multipart/form-data">
            <div><label>名前：<input type="text" name="drinkname"></label></div>
            <div><label>価格：<input type="number" name="drinkprice" value="120"></label></div>
            <div><label>個数：<input type="number" name="drinkstock" value="0"></label></div>
            <div><input type="file" name="new_img"></div>
            <div><input type="submit" value="商品を追加"></div>
            <input type="hidden" name="process_kind" value="insert_item">
        </form>
        <hr>
        
        <h2>商品情報変更</h2>
        
    <?php
        if (empty($data) === FALSE) {
    ?>
        <p>商品一覧</p>
        <table>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>個数</th>
            </tr>
        <?php foreach ($data as $value)  { ?>
            <tr>
                <td><img src="<?php print $img_dir . $value['img']; ?>" height="200"></td>
                <td><?php print entity($value['drink_name']); ?></td>
                <td><?php print '¥' . entity($value['price']); ?></td>
                <td>
                    <form method="post" enctype="multipart/form-data">
                        <div>
                            <input
                                type="number"
                                name="drinkstock_update"
                                value="<?php print entity($value['stock']); ?>"
                            >
                            <label>個</label>
                            <input type="submit" value="変更">
                        </div>
                        <input type="hidden" name="drinkid" value="<?php print entity($value['drink_id']); ?>">
                        <input type="hidden" name="process_kind" value="update_stock">
                    </form>
                </td>
            </tr>
        <?php } ?>
        </table>
    <?php
        } else {
            print entity('登録されたデータがありません。');
        }
    ?>
    </body>
</html>