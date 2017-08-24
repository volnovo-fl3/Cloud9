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
/* エラーチェック
/*--------------------------------------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    unset($err_msg);
    
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
}


// アップロードした新しい画像ファイル名の登録、既存の画像ファイル名の取得
try {
    // データベースに接続
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // エラーがなければ、アップロードした新しい画像ファイル名を保存
    if (empty($err_msg) === TRUE && $_SERVER['REQUEST_METHOD'] === 'POST' ){
        
        // アップロードされたファイルを指定ディレクトリに移動して保存
        if (move_uploaded_file($_FILES['new_img']['tmp_name'], $img_dir . $new_img_filename) !== TRUE) {
            $err_msg[] = 'ファイルアップロードに失敗しました';
        } else {
            
            try {
                $sql = 'INSERT INTO test_drink_master(drink_name, price, img, create_datetime) VALUES(?, ?, ?, ?)';
                
                $stmt = $dbh->prepare($sql);

                $createdatetime = date('Y-m-d H:i:s');
                $stmt->bindValue(1, $_POST['drinkname'], PDO::PARAM_STR);
                $stmt->bindValue(2, $_POST['drinkprice'], PDO::PARAM_INT);
                $stmt->bindValue(3, $new_img_filename, PDO::PARAM_STR);
                $stmt->bindValue(4, $createdatetime, PDO::PARAM_STR);
                
                $stmt->execute();
                
                /*---------- リロード対策 ----------*/
                header('location: https://codeincubate-tanakanoboru.c9users.io/php/16/manage.php');
                exit;
                /*----------------------------------*/

            } catch (PDOException $e) {
                throw $e;
            }
        }
    }
    
    // レコードを取得
    $sql = 'SELECT drink_name, price, img, create_datetime FROM test_drink_master';
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
            <div><label>価格：<input type="number" name="drinkprice"></label></div>
            <div><input type="file" name="new_img"></div>
            <div><input type="submit" value="商品を追加"></div>
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
                <th>登録日</th>
            </tr>
        <?php foreach ($data as $value)  { ?>
            <tr>
                <td><img src="<?php print $img_dir . $value['img']; ?>" height="200"></td>
                <td><?php print entity($value['drink_name']); ?></td>
                <td><?php print '¥' . entity($value['price']); ?></td>
                <td><?php print entity($value['create_datetime']); ?></td>
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