<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | 登録完了</title>
  </head>

  <body>
    <p><?php print $item_name ?> <?php print $str_mode ?>しました。</p>
    <form method="post" enctype="multipart/form-data" action='item_details.php'>
      <div>
        <input type="hidden" name="process_kind" value="to_item_details">
        <input type="hidden" name="target_item_id" value="<?php print $item_id?>">
        <input type="submit" value="商品の詳細を確認">
      </div>
    </form>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/mypage.php"><p>マイページへ</p></a>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/item_list.php"><p>商品一覧へ</p></a>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/item_details.php"><p>出品一覧へ</p></a>
  </body>
</html>