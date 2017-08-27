<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | 購入結果</title>
  </head>
  
  <body>
    <h1>購入結果</h1>
    
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
      } else {
    ?>
    
    <p>以下の商品を購入しました</p>
    <?php
        if((isset($paid_item_list) === TRUE) && (count($paid_item_list) > 0)){
    ?>
    <ul>
    <?php
          foreach($paid_item_list as $key => $paid_item){
    ?>
      <li>
        <h3><?php print $paid_item['item_name']?></h3>
        <p>¥<?php print $paid_item['price']?></p>
        <p><?php print $paid_item['amount']?>個</p>
        <p>出品：<?php print $paid_item['user_name']?>さん</p>
        <p><?php print $paid_item['category_color']?></p>
      </li>
    <?php
          }
    ?>
    </ul>
    <?php
        }
    ?>    
    
    <p>合計：<?php print $cart_amount_paid?>点 ¥<?php print $cart_price_paid?></p>
    <hr>
    <p>お買い上げ頂きありがとうございました。</p>
    <p>購入された商品は制作リストに登録されます。</p>
    <p>We wish all creators be happy!</p>
    
    <?php
      }
    ?>
    
  </body>
</html>