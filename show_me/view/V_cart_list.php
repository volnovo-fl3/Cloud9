<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | カート確認</title>
  </head>

  <body>
    <h1>カート確認</h1>
    
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
      if((isset($user) === TRUE) && (count($user) > 0)){
    ?>
    <p><?php print $user[0]['user_name']; ?> さん</p>
    <?php
      }
    ?>
    
    <?php
      if((isset($carts_unpaid_sum) === TRUE) && (count($carts_unpaid_sum) > 0)){
    ?>
    <p>カートに <?php print entity_str($carts_unpaid_sum[0]['cart_sum_amount']); ?> 点の商品があります。</p>
    <?php
      } else {
    ?>
    <p>カートに商品が入っていません。</p>
    <?php
      }
    ?>
    
    <?php
      if((isset($carts_unpaid_sum) === TRUE) && (count($carts_unpaid_sum) > 0)){
    ?>
    <?php
        if((isset($carts_unpaid_list) === TRUE) && (count($carts_unpaid_list) > 0)){
    ?>
    <ul>
    <?php
          foreach($carts_unpaid_list as $key => $cart){
    ?>
      <li>
        <h3><?php print $cart['item_name']?></h3>
        <p>¥<?php print $cart['price']?></p>
        <p><?php print $cart['amount']?>個</p>
        <form method="post" enctype="multipart/form-data">
          (
          <input type="number" name="amount_change">
          個に変更
          <input type="hidden" name="process_kind" value="cart_item_amount_change">
          <input type="hidden" name="cart_id" value="<?php print $cart['cart_id']?>">
          <input type="submit" value="変更">
          )
        </form>
        <p>出品：<?php print $cart['user_name']?>さん</p>
        <p><?php print $cart['category_color']?></p>
        <form method="post" enctype="multipart/form-data">
          <div>
            <input type="hidden" name="process_kind" value="cart_item_delete">
            <input type="hidden" name="cart_id" value="<?php print $cart['cart_id']?>">
            <input type="submit" value="カートから削除">
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
    <p>合計： ¥<?php print entity_str($carts_unpaid_sum[0]['cart_sum_price']); ?></p>
    <form method="post" enctype="multipart/form-data">
      <div>
        <label>支払い金額： ¥
          <input type="number" name="pay_money">
        </label>
        <input type="hidden" name="process_kind" value="cart_item_pay">
        <input type="submit" value="購入する">
      </div>
    </form>    
    <?php
      }
    ?>    
    
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/logout.php"><p>ログアウト</p></a>
  </body>
</html>