<?php
date_default_timezone_set('Asia/Tokyo');
define('HTML_CHARACTER_SET', 'UTF-8');

$access_datetime = '';
$message = '';
$last_access = '';


// セッション開始
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // セッション名取得 ※デフォルトはPHPSESSID
  $session_name = session_name();
  // セッション変数を全て削除
  $_SESSION = array();

  // ユーザのCookieに保存されているセッションIDを削除
  if (isset($_COOKIE[$session_name])) {
    setcookie($session_name, '', time() - 42000);
  }

  // セッションIDを無効化
  session_destroy();

  // ログアウトの処理が完了したらこのページへリダイレクト
  header('Location: challenge_session.php');
  exit;
}


$access_datetime = date("Y/m/d H:i:s");


// 初回アクセス(セッション情報がない)
if (!isset($_SESSION['visit_count_session'])) {

  // 訪問回数
  $_SESSION['visit_count_session'] = 1;
  $message = '初めての訪問です。';
  
  // 訪問履歴を取得
  $_SESSION['last_access_session'] = $access_datetime;
}

// 2回目以降のアクセス(セッション情報がある)
else {
  
  // 訪問回数
  $count = $_SESSION['visit_count_session'] + 1;
  $_SESSION['visit_count_session'] = $count;
  $message = $count . ' 回目の訪問です。';

  // 訪問履歴を取得
  $last_access = $_SESSION['last_access_session'];
  $_SESSION['last_access_session'] = $access_datetime;
}


//------------------------------------------------------------
// HTMLエスケープ
//------------------------------------------------------------
/**
* 特殊文字をHTMLエンティティに変換する
* @param str  $str 変換前文字
* @return str 変換後文字
*/
function entity_str($str) {

  return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

/**
* 特殊文字をHTMLエンティティに変換する(2次元配列の値)
* @param array  $assoc_array 変換前配列
* @return array 変換後配列
*/
function entity_assoc_array($assoc_array) {

  foreach ($assoc_array as $key => $value) {
    foreach ($value as $keys => $values) {
      // 特殊文字をHTMLエンティティに変換
      $assoc_array[$key][$keys] = entity_str($values);
    }
  }

  return $assoc_array;
}

//------------------------------------------------------------


?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>Cookieとセッション 課題(セッション)</title>
  </head>
  
  <body>
    <p><?php print entity_str($message) ?></p>
    <p><?php print entity_str(date("Y年m月d日 H時i分s秒", strtotime($access_datetime))) . ' (現在日時)' ?></p>
    <?php
    if($_SESSION['visit_count_session'] > 1) {
    ?>
    <p><?php print entity_str(date("Y年m月d日 H時i分s秒", strtotime($last_access))) . ' (前回のアクセス日時)' ?></p>
    <?php
    }
    ?>
  
    <form method="post">
      <input type="submit" value="履歴を削除する" />
    </form>
  </body>
</html>