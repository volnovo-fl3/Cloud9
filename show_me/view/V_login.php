<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | ログイン</title>
  </head>

  <body>
    <h1>ログイン</h1>
    
    <?php //ログアウト後であれば表示
      if (isset($logout_message) && mb_strlen($logout_message, HTML_CHARACTER_SET) > 0)
    ?>
    <p><?php print entity_str($logout_message)?></p>
    <?php
    ?>
    
    <?php //エラーメッセージを表示
    if (isset($err_msg) && count($err_msg) > 0) {
      foreach ($err_msg as $key => $error) {
    ?>
    <ul>
      <li><p><?php print entity_str($error)?></p></li>
    </ul>
    <?php
      }
    }
    ?>
    
    <form method="post" enctype="multipart/form-data">
      <div>
        <label>ユーザー名：<input type="text" name="user_name"></label>
      </div>
      <div>
        <label>パスワード：<input type="password" name="password"></label>
      </div>
      <div>
        <input type="hidden" name="process_kind" value="login">
        <input type="submit" value="ログイン">
      </div>
    </form>
    
    <form method="post" enctype="multipart/form-data">
      <div>
        <input type="hidden" name="process_kind" value="to_user_insert">
        <input type="submit" value="ユーザー新規登録">
      </div>
    </form>
    
  </body>
</html>