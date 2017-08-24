<?php
date_default_timezone_set('Asia/Tokyo');
$filename = './review.txt';

$errormessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    // 1. 名前が空の時
    if (mb_strlen($_POST['name']) === 0) {
        $errormessage = $errormessage . "<li><p>名前を入力してください</p></li>";
    }
    // 2. 名前が20文字以上の時
    if (mb_strlen($_POST['name']) > 20) {
        $errormessage = $errormessage . "<li><p>名前は20文字以内で入力してください</p></li>";
    }
    // 3. コメントが空の時
    if (mb_strlen($_POST['comment']) === 0) {
        $errormessage = $errormessage . "<li><p>コメントを入力してください</p></li>";
    }
    // 4. コメントが100文字以上の時
    if (mb_strlen($_POST['comment']) > 100) {
        $errormessage = $errormessage . "<li><p>コメントは100文字以内で入力してください</p></li>";
    }
    
    // エラーがなければファイルに書き込む(CSV形式)
    if (mb_strlen($errormessage) === 0) {
        $name = $_POST['name'];
        $comment = $_POST['comment'];
        $hitokoto = '"' . $name . '","' . $comment . '","' . date('Y-m-d H:i:s') . '"' . "\n";
        
        if (($fp = fopen($filename, 'a')) !== FALSE) {
            if (fwrite($fp, $hitokoto) === FALSE) {
                print 'ファイル書き込み失敗:  ' . $filename;
            }
        fclose($fp);
        }
    }
}

$data = [];

if (is_readable($filename) === TRUE) {
    if (($fp = fopen($filename, 'r')) !== FALSE) {
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
        }
        fclose($fp);
    }
} else {
    $data[] = 'ファイルがありません';
}

/* ------------------------ */
/* HTML文字化け回避用の関数 */
/* ------------------------ */
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
        <h1>ひとこと掲示板</h1>
        <?php
        if (mb_strlen($errormessage) > 0) {
        ?>
            <ul>
                <?php print $errormessage; ?>
            </ul>
            <br>
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
        if (is_readable($filename) === TRUE) {
            if (($fp = fopen($filename, 'r')) !== FALSE) {
                while (($hitokoto_row = fgetcsv($fp)) !== FALSE) {
        ?>
        <ul>
            <li>
                <?php
                print entity($hitokoto_row[0]) . '：' . entity($hitokoto_row[1]). '　- ' . entity($hitokoto_row[2]);
                ?>
            </li>
        </ul>
        <?php
                }
                fclose($fp);
            }
        } else {
            print 'ファイルがありません';
        }
        ?>        
    </body>
</html>