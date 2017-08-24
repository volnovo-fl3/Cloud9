<?php
$host     = 'localhost';
$username = 'tanakanoboru';   // MySQLのユーザ名
$password = '';       // MySQLのパスワード
$dbname   = 'camp';   // MySQLのDB名
$charset  = 'utf8';   // データベースの文字コード

// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;

$img_dir = './img/';
$data = [];
$err_msg = [];
$new_img_filename = '';   // アップロードした新しい画像ファイル名


// アップロード画像ファイルの保存
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // HTTP POST でファイルがアップロードされたかどうかチェック
    if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
        
         // 画像の拡張子を取得
         $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
         
         // 指定の拡張子であるかどうかチェック
         if ($extension === 'jpg' || $extension === 'jpeg'){
             
            // 保存する新しいファイル名の生成（ユニークな値を設定する）
            $new_img_filename = sha1(uniqid(mt_rand(), true)). '.' . $extension;
             
            // 同名ファイルが存在するかどうかチェック
            if (is_file($img_dir . $new_img_filename) !== TRUE) {
                 
                // アップロードされたファイルを指定ディレクトリに移動して保存
                if (move_uploaded_file($_FILES['new_img']['tmp_name'], $img_dir . $new_img_filename) !== TRUE) {
                    $err_msg[] = 'ファイルアップロードに失敗しました';
                }
                 
            } else {
                $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
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
    if (count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST' ){
        try {
            $sql = 'INSERT INTO test_upload_img(img_file_name) VALUES(?)';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $new_img_filename,    PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }
    
    // 既存のアップロードされた画像ファイル名の取得
    try {
        $sql = 'SELECT img_file_name FROM test_upload_img';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $data[] = $row;
        }
    } catch (PDOException $e) {
        throw $e;
    }
    
} catch (PDOException $e) {
    // 接続失敗した場合
    $err_msg['db_connect'] = 'DBエラー：'.$e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>画像アップロードサンプル</title>
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
        <?php foreach ($err_msg as $value) { ?>
            <p><?php print $value; ?></p>
        <?php } ?>
        
        <h1>画像アップロード</h1>
        <form method="post" enctype="multipart/form-data">
            <div><input type="file" name="new_img"></div>
            <div><input type="submit" value="アップロード"></div>
        </form>
        <table>
            <tr>
                <th>画像</th>
            </tr>
        <?php foreach ($data as $value)  { ?>
            <tr>
                <td><img src="<?php print $img_dir . $value['img_file_name']; ?>"></td>
            </tr>
        <?php } ?>
        </table>
    </body>
</html>