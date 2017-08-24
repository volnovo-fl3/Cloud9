<?php
date_default_timezone_set('Asia/Tokyo');
define('HTML_CHARACTER_SET', 'UTF-8');

$access_datetime = '';
$message = '';
$last_access = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Cookieを削除
  if (isset($_COOKIE['visit_count'])) {
    setcookie('visit_count', '');
    setcookie('last_access', '');
  }

  // ログアウトの処理が完了したらこのページへリダイレクト
  header('Location: challenge_cookie.php');
  exit;
}


$access_datetime = date("Y/m/d H:i:s");


// 初回アクセス：cookieを設定する
if(!isset($_COOKIE['visit_count'])) {

  // 訪問回数
  setcookie('visit_count', 1);
  $message = '初めての訪問です。';
  
  // 訪問履歴を取得
  setcookie('last_access', $access_datetime);
}


// 2回目以降のアクセス
else {
  
  // 訪問回数
  $count = $_COOKIE['visit_count'] + 1;
  setcookie('visit_count', $count);
  $message = $count . ' 回目の訪問です。';

  // 訪問履歴を取得
  $last_access = $_COOKIE['last_access'];
  setcookie('last_access', $access_datetime);
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
    <title>Cookieとセッション 課題(Cookie)</title>
  </head>
  
  <body>
    <p><?php print entity_str($message) ?></p>
    <p><?php print entity_str(date("Y年m月d日 H時i分s秒", strtotime($access_datetime))) . ' (現在日時)' ?></p>
    <?php
    if(isset($_COOKIE['visit_count']) === TRUE) {
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