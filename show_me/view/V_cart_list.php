<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | カート</title>
    <link rel="shortcut icon" href="font_icon/favicon.ico">
    <link rel="stylesheet" href="css/html5reset-1.6.1.css">
    <link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/show_me.css">
  </head>

  <body class="wf-notosansjapanese Background_Color_default">
    
    <!-- 上段 -->
    <header>
      <div class="contents_line">
        <div class="container flexbox">
          <div class="flex_2 position_set">
            <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/mypage.php"><img src="font_icon/logo02.png" class="logo02_image_size"></a>
          </div>
          <div class="user_name_image flex_1">
            
            <div class="flexbox position_set">
              <div class="user_img">
                <img src="<?php print IMAGE_DIRECTORY . $header_user_img; ?>" class="image_size_to_panel_radius"></img>
              </div>
              
            <?php
            if((mb_strlen($header_user_name) > 0) && (mb_strlen($header_user_img) > 0)) {
            ?>
              <div class="block_center_height">
                <div>
                  <p><?php print $header_user_name?> さん</p>
                  <p class="logout"><a href="https://codeincubate-tanakanoboru.c9users.io/show_me/logout.php">ログアウト</a></p>
                </div>
              </div>
            <?php
            }
            ?>
            
            </div>
          </div>
        </div>
      </div>
      <div class="contents_line Background_Color_white">
        <div class="container">
          <ul class="flexbox_header">
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/mypage.php"><p>マイページ</p></a>
            </li>
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/product_list.php"><p>制作リスト</p></a>
            </li>
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/item_list.php?page_type=all_list"><p>課題を探す</p></a>
            </li>
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/item_info.php"><p>出品する</p></a>
            </li>
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/item_list.php?page_type=seller_list"><p>出品一覧</p></a>
            </li>
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/cart_list.php"><p>カートを確認</p></a>
            </li>
            
          </ul>
        </div>
      </div>
    </header>
    
    <!-- 中段 -->
    <main class="Background_Color_white">
      <div class="container padding_top_10">
        <div class="container">
          
          <h1><?php print 'カート'?></h1>
  
          <?php // エラーがあれば表示
          if (isset($err_msg) && count($err_msg) > 0) {
          ?>
            <div class="add_row error_message_box">
              <ul>
          <?php
            foreach ($err_msg as $key => $error) {
          ?>
                <li><p><?php print entity_str($error)?></p></li>
          <?php
            }
          ?>
              </ul>
            </div>
          <?php
          }
          ?>
          
          <?php
            if((isset($user) === TRUE) && (count($user) > 0)){
          ?>
          <div class="add_row">
            <p class="inline_center_width"><?php print $user[0]['user_name']; ?> さん</p>
          </div>
          <?php
            }
          ?>
          
          <?php
            if((isset($carts_unpaid_sum) === TRUE) && (count($carts_unpaid_sum) > 0)){
          ?>
          <div class="add_row">
            <p class="inline_center_width">カートに <?php print entity_str($carts_unpaid_sum[0]['cart_sum_amount']); ?> 点の商品があります。</p>
          </div>
          <?php
            } else {
          ?>
          <div class="add_row">
            <p class="inline_center_width">カートに商品が入っていません。</p>
          </div>
          <?php
            }
          ?>
          
          <?php
            if((isset($carts_unpaid_sum) === TRUE) && (count($carts_unpaid_sum) > 0)){
          ?>
          <?php
              if((isset($carts_unpaid_list) === TRUE) && (count($carts_unpaid_list) > 0)){
                foreach($carts_unpaid_list as $key => $cart){
          ?>
            <div class="add_row">
              <a href="item_details.php?target_item_id=<?php print $cart['item_id']?>">
                
                <div class="item_panel01" style="background-color:<?php print $cart['category_color']?>;">
                  <div class="item_img">
                    <img src="<?php print IMAGE_DIRECTORY . image_link($cart['item_img']); ?>" class="image_size_to_panel_radius"></img>
                  </div>
                  <div class="item_info">
                    <div class="item_title">
                      <p><?php print $cart['item_name']?></p>
                    </div>
                    <p class="price">¥<?php print $cart['price']?> × <?php print $cart['amount']?> 個</p>
                    <p class="seller_name">
                      <?php print $cart['seller_user_name']?> さん
                      <?php
                        if((isset($cart['seller_user_affiliation']) === TRUE) && (mb_strlen($cart['seller_user_affiliation']) > 0)) {
                          print ' ('. $cart['seller_user_affiliation'] .') ';
                        }
                      ?>
                      による出品</p>
                      
                      <div class="add_row padding_side_10">
                        <form method="post" enctype="multipart/form-data">
                          (
                          <input type="number" class="item_amont" name="amount_change">
                          個に
                          <input type="hidden" name="process_kind" value="cart_item_amount_change">
                          <input type="hidden" name="cart_id" value="<?php print $cart['cart_id']?>">
                          <input type="submit" value="変更">
                          )
                        </form>
                      </div>
                      <div class="add_row padding_side_10">
                        <form method="post" enctype="multipart/form-data">
                          <input type="hidden" name="process_kind" value="cart_item_delete">
                          <input type="hidden" name="cart_id" value="<?php print $cart['cart_id']?>">
                          <input type="submit" class="block" value="カートから削除">
                        </form>
                      </div>
                      
                  </div>
                </div>
                
              </a>
            </div>        
          
          <?php
              }
          ?>
          
          <hr>
          
          <div class="add_row_last flexbox">
            <div class="flex_1 ">
              <p class="flex_right_center_height">合計</p>
            </div>
            <div class="flex_1 add_colmun_right">
              <p>¥<?php print entity_str($carts_unpaid_sum[0]['cart_sum_price']); ?></p>
            </div>
          </div>
          <div class="add_row_last flexbox">
            <div class="flex_1 flex_right_center_height">
              <p>お支払い金額</p>
            </div>
            <div class="flex_1 add_colmun_right">
              <form method="post" enctype="multipart/form-data">
                <div>
                  <label>¥<input type="number" name="pay_money"></label>
                  <input type="hidden" name="process_kind" value="cart_item_pay">
                  <input type="submit" value="購入する">
                </div>
              </form>    
            </div>
          </div>
          
          <?php
            }
          }
          ?>    
        
        </div>
      </div>
    </main>
    
    
    <!-- 下段 -->
    <footer>
      <div class="container">
         <ul class="flexbox_footer">
          <li class="footerlist">
            <a href="#" target="_blank"><p>サイトマップ</p></a>
          </li>
          <li class="footerlist">
            <a href="#" target="_blank"><p>プライバシーポリシー</p></a>
          </li>
          <li class="footerlist">
            <a href="#" target="_blank"><p>お問い合わせ</p></a>
          </li>
          <li class="footerlist">
            <a href="#" target="_blank"><p>ご利用ガイド</p></a>
          </li>
        </ul>
        <p><small>Copyright &copy; Show me! All Rights Reserved.</small></p>
      </div>
    </footer>
    
  </body>
</html>