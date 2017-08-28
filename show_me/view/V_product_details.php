<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | 作品詳細</title>
  </head>

  <body>
    <h1>作品詳細</h1>
    
    <?php
    // エラーがあれば表示
      if((isset($err_msg) === TRUE) && (count($err_msg) > 0)){
    ?>
    <ul>
    <?php
        foreach($err_msg as $key => $error) {
    ?>
      <li>
        <p><?php print $error?></p>
      </li>
    <?php
        }
    ?>
    </ul>
    <?php
      }
    ?>
    
    <?php
    // 商品情報を取得できていれば表示
      if((isset($look_product) === TRUE) && (count($look_product) > 0)){
    ?>
    <h2><?php print $look_product[0]['item_name']?></h2>
    <h3>作品情報</h3>
    <img src="<?php print IMAGE_DIRECTORY . $look_product_img; ?>" height="600">
    <p>作品リンク：<?php print $look_product[0]['product_link']?></p>
    <p>制作者：<?php print $look_product[0]['productor_user_name']?> さん</p>
    <p>ステータス：<?php print entity_str(product_status_int_to_str($look_product[0]['product_status']))?></p>
    <p>制作者コメント：<?php print nl2br(htmlspecialchars($look_product[0]['product_comment'], ENT_QUOTES, "UTF-8"), false)?></p>
    
    <hr>
    <h3>商品情報</h3>
    <img src="<?php print IMAGE_DIRECTORY . $look_product_img; ?>" height="200">
    <p>出品者：<?php print $look_product[0]['seller_user_name']?> さん</p>
    <p>商品説明：<?php print $look_product[0]['item_introduction']?></p>
    <p>商品説明(詳細)：<?php print $look_product[0]['item_introduction_detail']?></p>
    <p>対象カテゴリ：<?php print entity_str(implode(' / ', $categories_name_list))?></p>
    <p>使用ソフト：<?php print entity_str(implode(' / ', $skills_name_list))?></p>
    
    <hr>
    
    <?php
      //ログイン者 = 制作者であれば更新・削除できる
        if ((string)$my_user_id === (string)$look_product[0]['productor_user_id']) {
    ?>
    <form method="post" enctype="multipart/form-data" action='product_info.php'>
      <div>
        <input type="hidden" name="process_kind" value="to_update_product_info">
        <input type="hidden" name="target_product_id" value="<?php print $look_product[0]['product_id']?>">
        <input type="submit" value="制作情報を更新する">
      </div>
    </form>
    <form method="post" enctype="multipart/form-data">
      <div>
        <input type="hidden" name="process_kind" value="delete_product">
        <input type="hidden" name="target_product_id" value="<?php print $look_product[0]['product_id']?>">
        <input type="submit" value="この作品を削除する">
      </div>
    </form>
    <?php
        }
    ?>
    <?php
      }
    ?>
    
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/logout.php"><p>ログアウト</p></a>
  </body>
</html>