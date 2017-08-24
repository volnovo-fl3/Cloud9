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


/*--------------------------------------------------------
/* フォームから送られた値があればエラーチェック
/*--------------------------------------------------------*/
if (isset($_POST) === TRUE) {
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        
        // 1. 名前が空の時
        if (mb_strlen($_POST['name']) === 0) {
            $errormessage[] = '名前を入力してください';
        }
        // 2. 名前が20文字以上の時
        if (mb_strlen($_POST['name']) > 20) {
            $errormessage[] = '名前は20文字以内で入力してください';
        }
        // 3. コメントが空の時
        if (mb_strlen($_POST['comment']) === 0) {
            $errormessage[] = 'コメントを入力してください';
        }
        // 4. コメントが100文字以上の時
        if (mb_strlen($_POST['comment']) > 100) {
            $errormessage[] = 'コメントは100文字以内で入力してください';
        }
    }
}


/*--------------------------------------------------------
/* DBに接続
/*--------------------------------------------------------*/
try {
    // データベースに接続
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    
    /*--------------------------------------------------------
    /* POSTかつエラーがなければINSERT処理
    /*--------------------------------------------------------*/
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // エラーがなければ($errormessage配列が空であれば)データベースにファイルに書き込む
        if (empty($errormessage) === TRUE) {
                
                $name = $_POST['name'];
                $comment = $_POST['comment'];
                $date = date('Y-m-d H:i:s');
                
                //SQL文を作成
                $sql = 'insert into test_bbs(user_name, user_comment, create_datetime) values(?, ?, ?)';
                
                //SQL文を実行する準備
                $stmt = $dbh->prepare($sql);
                
                //SQL文のプレースホルダに値をバインド
                $stmt->bindValue(1, $name, PDO::PARAM_STR);
                $stmt->bindValue(2, $comment, PDO::PARAM_STR);
                $stmt->bindValue(3, $date, PDO::PARAM_STR);
                
                //SQLを実行
                $stmt->execute();
                
                /*---------- リロード対策 ----------*/
                header('location: https://codeincubate-tanakanoboru.c9users.io/php/15/bbs.php');
                exit;
                /*----------------------------------*/
        }
    }
    
    /*--------------------------------------------------------
    /* 投稿内容を取得
    /*--------------------------------------------------------*/
    
    //SQL文を作成
    $sql = 'select * from test_bbs';
    
    //SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    
    //SQLを実行
    $stmt->execute();
    
    //レコードの取得
    $rows = $stmt->fetchAll();


} catch (PDOException $e) {
    echo 'データベースに接続できませんでした。理由：'.$e->getMessage();
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
        <title>ひとこと掲示板</title>
        <style>
        p {
        	color: red;
        }
        </style>
    </head>

    <body>
        <h1>ひとこと掲示板(DB連携ver.)</h1>
    <?php
        if (isset($errormessage) === TRUE) {
    ?>
            <ul>
    <?php
            foreach ($errormessage as $key => $error) {
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
    
        <form method="post">
            <label>名前：</label>
            <input type="text" name="name">
            <label>　ひとこと：</label>
            <input type="text" name="comment">
            <input type="submit" value="送信">
        </form>
        
    <?php
        /* -------------------------
        / $rowsにデータがある時！
        / 投稿内容を表示する
        / ------------------------ */
        if (isset($rows) === TRUE) 
        {
    ?>
            <ul>
    <?php
            foreach ($rows as $key => $row) {
    ?>
                <li>
                <?php print entity($row[1]) . '：' . entity($row[2]). '　- ' . entity($row[3]); ?>
                </li>
    <?php
            }
    ?>
            </ul>
    <?php
        } else {
        /* -------------------------
        / $rowsにデータがない時！
        / ------------------------ */
            print '書き込みがありません。';
        }
    ?>
        
    </body>
</html>