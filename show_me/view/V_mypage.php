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
        <input type="hidden" name="process_kind" value="to_user_profile">
        <input type="hidden" name="target_user_id" value="<?php print $user_id?>">
        <input type="submit" value="登録情報確認">
      </div>
    </form>
    
    <?php
      if((isset($carts_unpaid) === TRUE) && (count($carts_unpaid) > 0)){
    ?>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/cart_list.php">
      <p>カートに <?php print entity_str($carts_unpaid[0]['cart_sum_amount']); ?> 点の商品があります。</p>
    </a>
    <?php
      } else {
    ?>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/cart_list.php">
      <p>カートを確認</p>
    </a>
    <?php
      }
    ?>
    
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/product_list.php"><p>制作リスト</p></a>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/item_list.php?page_type=all_list"><p>商品一覧</p></a>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/item_info.php"><p>商品を出品する</p></a>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/item_list.php?page_type=seller_list"><p>出品商品一覧</p></a>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/logout.php"><p>ログアウト</p></a>
  </body>
</html>