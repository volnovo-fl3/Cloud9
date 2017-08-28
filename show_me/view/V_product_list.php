<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | 制作リスト</title>
  </head>

  <body>
    <h1>制作リスト</h1>
    
    <?php
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
      if((isset($products_list) === TRUE) && (count($products_list) > 0)){
    ?>
    <p>作品が <?php print entity_str(count($products_list)); ?> 点登録されています。</p>
    <?php
      } else {
    ?>
    <p>作品が購入されていません。</p>
    <p>商品一覧よりお買い求めください。</p>
    <?php
      }
    ?>
    
    <?php
      if((isset($products_list) === TRUE) && (count($products_list) > 0)){
    ?>
    <ul>
    <?php
        foreach($products_list as $key => $product){
    ?>
      <li>
        <h3><?php print $product['item_name']?></h3>
        <p><?php print entity_str(date("Y/m/d H:i", strtotime($product['bought_datetime'])))?> に購入</p>
        <p>ステータス：<?php print entity_str(product_status_int_to_str($product['product_status']))?></p>
        <p>出品：
          <?php
            print $product['seller_user_name'];
            if((isset($product['seller_user_affiliation']) === TRUE) && (mb_strlen($product['seller_user_affiliation']) > 0)) {
              print '(' . $product['seller_user_affiliation'] . ')';
            }
            print 'さん';
          ?>
        </p>
        <p><?php print $product['category_color']?></p>
        
        <form method="post" enctype="multipart/form-data" action="product_details.php">
          <div>
            <input type="hidden" name="process_kind" value="to_product_detail">
            <input type="hidden" name="target_product_id" value="<?php print $product['product_id']?>">
            <input type="submit" value="詳細を見る">
          </div>
        </form>
      </li>
    <?php
        }
    ?>
    </ul>
    <?php
      }
    ?>

    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/logout.php"><p>ログアウト</p></a>
  </body>
</html>