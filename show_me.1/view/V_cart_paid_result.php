<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | 購入結果</title>
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
        
        <h1>購入結果</h1>

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
          } else {
        ?>
        
        <div class="add_row">
          <p class="inline_center_width">以下の商品を購入しました</p>
        </div>
        <?php
            if((isset($paid_item_list) === TRUE) && (count($paid_item_list) > 0)){
              foreach($paid_item_list as $key => $paid_item){
        ?>
        
          <div class="add_row">
            <div class="item_panel01" style="background-color:<?php print $paid_item['category_color']?>;">
              <div class="item_img">
                <img src="<?php print IMAGE_DIRECTORY . image_link($paid_item['item_img']); ?>" class="image_size_to_panel_radius"></img>
              </div>
              <div class="item_info">
                <div class="item_title">
                  <p><?php print $paid_item['item_name']?></p>
                </div>
                <p class="price">¥<?php print $paid_item['price']?> × <?php print $paid_item['amount']?> 個</p>
                <p class="seller_name">
                  <?php print $paid_item['seller_user_name']?> さん
                  <?php
                    if((isset($paid_item['seller_user_affiliation']) === TRUE) && (mb_strlen($paid_item['seller_user_affiliation']) > 0)) {
                      print ' ('. $paid_item['seller_user_affiliation'] .') ';
                    }
                  ?>
                  による出品</p>
              </div>
            </div>
          </div>
              
        <?php
              }
            }
        ?>
        
        <div class="add_row">
          <p class="inline_center_width">合計 <?php print $cart_amount_paid?>点 ¥<?php print $cart_price_paid?></p>
        </div>
        
        <hr>
        <div class="message_box">
          <p class="inline_center_width">お買い上げ頂きありがとうございました。</p>
          <p class="inline_center_width">
            購入された商品は
            <span><a href="https://codeincubate-tanakanoboru.c9users.io/show_me/product_list.php">制作リスト</a></span>
            に登録されます。
          </p>
          <p class="inline_center_width">We wish all creators be happy!</p>
        </div>
    
    <?php
      }
    ?>
        
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