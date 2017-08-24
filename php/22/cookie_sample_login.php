<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ログイン後</title>
    </head>

    <body>
      <?php

      $now = time();

      if(isset($_POST['cookie_check'])) {
        $cookie_check = $_POST['cookie_check'];
        print 'cookieが保存されています。'."<br>";
      } else {
        $cookie_check = '';
        print 'cookieが保存されていません。'."<br>";
      }
      if(isset($_POST['user_name'])) {
        $cookie_value = $_POST['user_name'];
      } else {
        $cookie_value = '';
      }
      
      // ユーザ名の入力を省略のチェックがONの場合、Cookieを利用する。OFFの場合、Cookieを削除する
      print $cookie_check."<br>";
      print $_POST['cookie_check']."<br>";
      if ($cookie_check === 'checked') {
        // Cookieへ保存する
        setcookie('cookie_check', $cookie_check, $now + 60 * 60 * 24 * 365);
        setcookie('user_name'   , $cookie_value, $now + 60 * 60 * 24 * 365);
        print 'cookieを保存しました。'."<br>";
      } else {
        // Cookieを削除する
        setcookie('cookie_check', '', $now - 3600);
        setcookie('user_name'   , '', $now - 3600);
        print 'cookieを削除しました。'."<br>";
      }
      
      print 'ようこそ';
      
      ?>
    </body>
</html>