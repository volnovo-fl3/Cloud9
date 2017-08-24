<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | マイページ</title>
  </head>

  <body>
    <h1>マイページ</h1>
    <p>ユーザーIDは <?php print htmlspecialchars($user_id) ?> です。</p>
    
    <form method="post" enctype="multipart/form-data" action='user_profile.php'>
      <div>
        <input type="hidden" name="process_kind" value="user_profile">
        <input type="hidden" name="target_user_id" value="<?php print $user_id?>">
        <input type="submit" value="登録情報確認">
      </div>
    </form>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/logout.php"><p>ログアウト</p></a>
  </body>
</html>