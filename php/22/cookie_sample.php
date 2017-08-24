<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>Cookie</title>
  </head>
  
  <body>
    <?php
    
    // cookieが設定されていなければ(初回アクセス)、cookieを設定する
    if(!isset($_COOKIE['visit_count'])) {
      // cookie
      setcookie('visit_count', 1, time() + 3600); //クッキーの期限は1時間
      print("訪問回数は1回");
    }
    
    // cookieがすでに設定されていれば(2回目以降のアクセス)、cookieで設定した数値を加算する
    else {
      $count = $_COOKIE['visit_count'] + 1;
      setcookie('visit_count', $count, time() + 3600);
      print("訪問回数は" . $count . "回<br>");
    }
    ?>
  </body>
</html>