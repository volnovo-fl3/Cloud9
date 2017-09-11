<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | 制作リスト</title>
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

        <h1><?php print $page_name?></h1>
        
        <?php
          if($page_type === 'by_user'){
            if((mb_strlen($target_user_id) > 0) && ($target_user_id === $user_id)){
        ?>
        <div class='add_row message_box'>
          <p>本人による閲覧のため、非公開の作品も表示されます。</p>
        </div>
        <?php
            }
          }
        ?>
        
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
          if((isset($products_list) === TRUE) && (count($products_list) > 0)){
        ?>
        <div class="add_row">
          <p class="inline_center_width">作品が <?php print entity_str(count($products_list)); ?> 点登録されています。</p>
        </div>
        <?php
          } else {
        ?>
        <div class="add_row">
          <p class="inline_center_width">作品が購入されていません。</p>
          <p class="inline_center_width">商品一覧よりお買い求めください。</p>
        </div>
        <?php
          }
        ?>
        
        
        <div class="item_plate">
        <?php
          if((isset($products_list) === TRUE) && (count($products_list) > 0)){
            foreach($products_list as $key => $product){
        ?>
          <div class="add_row">
            <div class="item_panel02" style="background-color:<?php print $product['category_color']?>;">
              <a href="product_details.php?target_product_id=<?php print $product['product_id']?>">
                <div class="item_img">
                  <img src="<?php print IMAGE_DIRECTORY . image_link($product['product_img']); ?>" class="image_size_to_panel_radius"></img>
                </div>
                
                <div class="item_info">
                  <div class="item_title">
                    <p><?php print $product['item_name']?> / <?php print product_status_int_to_str($product['product_status'])?></p>
                  </div>
                  <?php
                    if($page_type === 'by_user'){
                  ?>
                  <p class="seller_name">
                    <?php print $product['seller_user_name']?> さん
                    <?php
                      if((isset($product['seller_user_affiliation']) === TRUE) && (mb_strlen($product['seller_user_affiliation']) > 0)) {
                        print ' ('. $product['seller_user_affiliation'] .') ';
                      }
                    ?>
                    による出品</p>
                  <?php
                    } else if($page_type === 'by_item') {
                  ?>
                  <p class="seller_name">
                    <?php print $product['productor_user_name']?> さんによる作品</p>
                  <?php
                    }
                  ?>
                </div>
              </a>
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