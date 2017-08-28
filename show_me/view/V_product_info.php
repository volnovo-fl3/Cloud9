<script>
  // ---------------------------------------------
  // (更新時のみ)画像を変更するかどうか
  // 0:変更しない 1:変更する
  // ---------------------------------------------
  var is_change_image;
  is_change_image = 0;
  
  function change_image(flg_int) {
    
    // フラグを変更
    is_change_image = flg_int;
    
    // 表示・非表示切り替え
    if (is_change_image === 0) {
      document.getElementById("change_item_img").style.display="none";
    }
    if (is_change_image === 1) {
      document.getElementById("change_item_img").style.display="block";
    }
  }
</script>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | 作品情報更新</title>
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
    
    <form method="post" enctype="multipart/form-data">
      
      <img src="<?php print IMAGE_DIRECTORY . $look_product_img; ?>" height="600">
      <div>
        <input type="radio" name="radio_img_change" value="1" onclick="change_image(1)"/><label>画像を変更する</label>
        <input type="radio" name="radio_img_change" value="0" onclick="change_image(0)" checked="checked" /><label>画像を変更しない</label>
      </div>
      <div id="change_item_img" style="display:none;">
        <label>作品サムネイル：<input type="file" name="product_img"></label>
      </div>
      
      <div>
        作品リンク：
        <input type="text" name='product_link' value="<?php print $product_link?>">
      </div>
      <div>
        ステータス：
        <select name="product_status">
          <option value="0" <?php if($product_status === 0){print selected;} ?>>未完成</option>
          <option value="1" <?php if($product_status === 1){print selected;} ?>>完成(公開中)</option>
          <option value="2" <?php if($product_status === 2){print selected;} ?>>完成(非公開)</option>
        </select>
      </div>
      <div>
        制作者コメント：
        <textarea name="product_comment"><?php print nl2br(htmlspecialchars($product_comment, ENT_QUOTES, "UTF-8"), false)?></textarea>
      </div>

      <div>
        <input type="hidden" name="process_kind" value="update_product_info">
        <input type="hidden" name="target_product_id" value="<?php print $look_product[0]['product_id']?>">
        <input type="submit" value="更新">
      </div>

    </form>
    
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
      }
    ?>
    
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/logout.php"><p>ログアウト</p></a>
  </body>
</html>