<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | <?php print $page_title?></title>
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
        
        <h1><?php print $page_title?></h1>
        
      <?php // エラーがあれば表示
      if (isset($err_msg) && count($err_msg) > 0) {
      ?>
        <div class="error_message_box add_box">
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
      
      // エラーがなければ商品情報表示
      else {
      ?>
      
        <div class="flexbox">
          
          <div class="details flex_3">
            
            <div class="add_row inline_center_width">
              <img src="<?php print IMAGE_DIRECTORY . $look_item_img; ?>" class="user_profiles_image">
            </div>
            <h2>商品名</h2>
            <p><?php print str_is_regist($look_item[0]['item_name'])?></p>
            <h2>価格</h2>
            <p>¥<?php print str_is_regist($look_item[0]['price'])?></p>
            <h2>商品紹介</h2>
            <p>
              <?php print nl2br(str_is_regist($look_item[0]['item_introduction']), false)?>
            </p>
            <h2>商品紹介(詳細)</h2>
            <p>
              <?php print nl2br(str_is_regist($look_item[0]['item_introduction_detail']), false)?>
            </p>
            
            <h2>対応カテゴリ</h2>
            <p><?php print entity_str(str_is_regist(implode(' / ', $categories_name_list))) ?></p>
            
            <h2>使用ソフト</h2>
            <p><?php print entity_str(str_is_regist(implode(' / ', $skills_name_list))) ?></p>
            
            <hr>
            <?php //在庫があれば購入できる
              if($look_item[0]['stock'] > 0) {
            ?>
            <div class="stock">
              <label>在庫 <?php print $look_item[0]['stock']?> 点</label>
            </div>
            <label class="add_colmun_right">
              <form method="post" enctype="multipart/form-data">
                <div>
                  <input type="number" name="item_to_carts_amount" value=<?php print $item_to_carts_amount?>>
                  <input type="hidden" name="item_to_carts_stock" value=<?php print $look_item[0]['stock']?>>
                  <input type="hidden" name="target_item_id" value="<?php print $look_item[0]['item_id']?>">
                  <input type="hidden" name="process_kind" value="item_to_carts">
                  <input type="submit" value="カートに入れる">
                </div>
              </form>
            </label >
            <?php //在庫がなければ売り切れ
              } else {
            ?>
            <label class="Text_Color_red">売り切れ</label>
            <?php
              }
            ?>
            <?php // (カート用の)エラーがあれば表示
            if (isset($err_to_cart) && count($err_to_cart) > 0) {
            ?>
            <div class="error_message_box";>
              <ul>
            <?php
              foreach ($err_to_cart as $key => $c_err) {
            ?>
                <li><p><?php print entity_str($c_err)?></p></li>
            <?php
              }
            ?>
              </ul>
            </div>
            <?php
            }
            ?>
            
          </div>
          
          <div class="flex_1 add_colmun_right details">
            <div class="add_row user_profiles_panel_back">
              <h2>出品者</h2>
              <div class="user_profiles_panel">
                <div class="image_panel_200 block_center_width">
                  <img src="<?php print IMAGE_DIRECTORY . image_link($seller_user[0]['user_img']); ?>" class="image_size_to_panel_radius"></img>
                </div>
                <p class="inline_center_width"><?php print $seller_user[0]['user_name'] ?> さん</p>
                <?php
                  if (mb_strlen($user[0]['user_affiliation']) > 0){
                ?>
                <p class="inline_center_width"><?php print $seller_user[0]['user_affiliation'] ?></p>
                <?php
                  }
                ?>
                
                <hr>
                <h3>対応カテゴリ</h3>
                <p class="name_list"><?php print entity_str(str_is_regist(implode(' / ', $seller_user_categories_name_list))) ?></p>
                
                <h3>使用ソフト</h3>
                <p class="name_list"><?php print entity_str(str_is_regist(implode(' / ', $seller_user_skills_name_list))) ?></p>
  
                <form action='user_profile.php'>
                  <div class="add_row">
                    <input type="hidden" name="process_kind" value="to_user_profile">
                    <input type="hidden" name="target_user_id" value="<?php print $seller_user[0]['user_id']?>">
                    <input type="submit" class="display_block block_center_width" value="プロフィールを見る">
                  </div>
                </form>
              </div>
            </div>
          </div>
          
        </div>
        
          
        <?php
          if ((int)$my_user_id === (int)$look_item[0]['seller_user_id']) {
        ?>
        <hr>
        <form method="post" enctype="multipart/form-data" action='item_info.php'>
          <div>
            <input type="hidden" name="process_kind" value="to_update_item_info">
            <input type="hidden" name="target_item_id" value="<?php print $look_item[0]['item_id']?>">
            <input type="submit" class="display_block block_center_width" value="商品情報を変更する">
          </div>
        </form>
        <form method="post" enctype="multipart/form-data">
          <div class="add_row">
            <input type="hidden" name="process_kind" value="delete_item">
            <input type="hidden" name="target_item_id" value="<?php print $look_item[0]['item_id']?>">
            <input type="submit" class="display_block block_center_width" value="この商品を削除する">
          </div>
        </form>
        <?php
          }
        }
        ?>
      
        <h1 class="add_row">『<?php print $page_title?>』の制作リスト</h1>
        <div class="flex_right_center_height">
          <p class="link">
            <a href="<?php print $url_products_list?>">→ もっと見る</a>
          </p>
        </div>
        <div class="add_row item_plate">
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
                  <p class="seller_name">
                    <?php print $product['productor_user_name']?> さんによる作品</p>
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