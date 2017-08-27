<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | <?php print $page_title?></title>
  </head>

  <body>
    <h1><?php print $page_title?></h1>
    
    <?php // エラーがあれば表示
    if (isset($err_msg) && count($err_msg) > 0) {
      foreach ($err_msg as $key => $error) {
    ?>
    <ul>
      <li><p><?php print entity_str($error)?></p></li>
    </ul>
    <?php
      }
    }
    
    // エラーがなければユーザー情報表示
    else {
    ?>
    
    <h2>基本情報</h2>
    <div>
      <img src="<?php print IMAGE_DIRECTORY . $look_item_img; ?>" height="200">
      <p><?php print $look_item[0]['item_name']?></p>
      <p>¥<?php print $look_item[0]['price']?></p>
      
      <?php //在庫があれば購入できる
        if($look_item[0]['stock'] > 0) {
      ?>
      <p>在庫 <?php print $look_item[0]['stock']?> 点</p>
      <form method="post" enctype="multipart/form-data">
        <div>
          <input type="number" name="item_to_carts_amount" value=<?php print $item_to_carts_amount?>>
          <input type="hidden" name="item_to_carts_stock" value=<?php print $look_item[0]['stock']?>>
          <input type="hidden" name="target_item_id" value="<?php print $look_item[0]['item_id']?>">
          <input type="hidden" name="process_kind" value="item_to_carts">
          <input type="submit" value="カートに入れる">
        </div>
      </form>
      <?php //在庫がなければ売り切れ
        } else {
      ?>
      <p>売り切れ</p>
      <?php
        }
      ?>
      <?php // (カート用の)エラーがあれば表示
      if (isset($err_to_cart) && count($err_to_cart) > 0) {
        foreach ($err_to_cart as $key => $c_err) {
      ?>
      <ul>
        <li><p><?php print entity_str($c_err)?></p></li>
      </ul>
      <?php
        }
      }
      ?>
      
      <p><?php print $look_item[0]['seller_user_name']?> さんによる出品</p>
    </div>
    
    <h3>商品説明</h3>
    <div>
      <?php print $look_item[0]['item_introduction']?>
    </div>
    <div>
      <?php print $look_item[0]['item_introduction_detail']?>
    </div>

    <hr>
    <h3>対象カテゴリ</h3>
    <?php
    // カテゴリ名(配列)を取得できたとき
      if(count($categories_name_list) > 0) {
        foreach($categories_name_list as $key => $category_name) {
    ?>
    <p><?php print entity_str($category_name)?></p>
    <?php
        }
    // できていないとき
      } else {
    ?>
    <p>登録されていません</p>
    <?php
      }
    ?>
    
    <h3>使用ソフト</h3>
    <?php
    // 使用ソフト名(配列)を取得できたとき
      if(count($skills_name_list) > 0) {
        foreach($skills_name_list as $key => $skill_name) {
    ?>
    <p><?php print entity_str($skill_name)?></p>
    <?php
        }
    // できていないとき
      } else {
    ?>
    <p>登録されていません</p>
    <?php
      }
    ?>
    
    <hr>
    
    <?php
      if ((string)$my_user_id === (string)$look_item[0]['seller_user_id']) {
    ?>
    <form method="post" enctype="multipart/form-data" action='item_info.php'>
      <div>
        <input type="hidden" name="process_kind" value="to_update_item_info">
        <input type="hidden" name="target_item_id" value="<?php print $look_item[0]['item_id']?>">
        <input type="submit" value="商品情報を変更する">
      </div>
    </form>
    <form method="post" enctype="multipart/form-data">
      <div>
        <input type="hidden" name="process_kind" value="delete_item">
        <input type="hidden" name="target_item_id" value="<?php print $look_item[0]['item_id']?>">
        <input type="submit" value="この商品を削除する">
      </div>
    </form>
    <?php
      }
    ?>
    
    <?php
    }
    ?>
    
  </body>
</html>