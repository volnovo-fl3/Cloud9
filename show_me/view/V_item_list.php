<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | <?php print $page_name ?></title>
  </head>

  <body>
    <h1><?php print $page_name ?></h1>
    
    <?php
      if((isset($carts_unpaid) === TRUE) && (count($carts_unpaid) > 0)){
    ?>
    <p>カートに <?php print entity_str($carts_unpaid[0]['cart_sum_amount']); ?> 点の商品があります。</p>
    <?php
      }
    ?>
    
    <?php
    // 対象商品がないとき
      if (count($items_list) < 1) {
    ?>
    <p>対象の商品が見つかりません。</p>  

    <?php
    // あるときー！
      } else {
        foreach ($items_list as $key => $item){
    ?>
    <div>
      <p><?php print $item['item_name']?></p>
      <p><?php print $item['price']?></p>
      <form method="post" enctype="multipart/form-data" action='item_details.php'>
        <div>
          <input type="hidden" name="process_kind" value="to_item_details">
          <input type="hidden" name="target_item_id" value="<?php print $item['item_id']?>">
          <input type="submit" value="商品の詳細を見る">
        </div>
      </form>

    </div>
    <?php
        }
      }
    ?>

  </body>
</html>