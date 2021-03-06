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
        
      <?php // エラーがあれば表示
      if (isset($err_msg) && count($err_msg) > 0) {
      ?>
        <div class="error_message_box">
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
      
      // エラーがなければユーザー情報表示
      else {
      ?>
      
        <h1><?php print $look_user[0]['user_name']?> さんのプロフィール</h1>
        
        <div class="flexbox">
          
          <div class="details">
            
            <div class="add_row inline_center_width">
              <img src="<?php print IMAGE_DIRECTORY . $look_user_img; ?>" class="user_profiles_image">
            </div>
            <h2>ユーザー名</h2>
            <p><?php print str_is_regist($look_user[0]['user_name'])?></p>
            <h2>所属企業・団体</h2>
            <p><?php print str_is_regist($look_user[0]['user_affiliation'])?></p>
            <h2>自己紹介</h2>
            <p>
              <?php print nl2br(str_is_regist($look_user[0]['user_self_introduction']), false)?>
            </p>
            
            <h2>対応カテゴリ</h2>
            <p><?php print entity_str(str_is_regist(implode(' / ', $categories_name_list))) ?></p>
            
            <h2>使用ソフト</h2>
            <p><?php print entity_str(str_is_regist(implode(' / ', $skills_name_list))) ?></p>
            
          </div>
          
          <div class="details">
            <h2><?php print $look_user[0]['user_name']?> さんの出品リスト</h2>
            <div class="add_row panel_board">
              <?php
                if(count($seller_list) > 0){
              ?>
              <p class="link">
                <a href="<?php print $url_seller_items_list?>">→ もっと見る</a>
              </p>
              <div class="panel_list">
              <?php
                  foreach($seller_list as $key => $item){
              ?>
                <div class="item_panel" style="background-color:<?php print $item['category_color']?>">
                  <a href="item_details.php?target_item_id=<?php print $item['item_id']?>">
                    <img src="<?php print IMAGE_DIRECTORY . image_link($item['item_img']); ?>"></img>
                    <p><?php print $item['item_name']?></p>
                  </a>
                </div>
              <?php
                  }
              ?>
              </div>
              <?php
                } else {
              ?>
              <p>データがありません</p>
              <?php
                }
              ?>
            </div>
            
            <h2><?php print $look_user[0]['user_name']?> さんの制作リスト</h2>
            <div class="add_row panel_board">
              <?php
                if(count($product_list) > 0){
              ?>
              <p class="link">
                <a href="<?php print $url_product_list?>">→ もっと見る</a>
              </p>
              <div class="panel_list">
              <?php
                  foreach($product_list as $key => $product){
              ?>
                <div class="item_panel" style="background-color:<?php print $product['category_color']?>">
                  <a href="product_details.php?target_product_id=<?php print $product['product_id']?>">
                    <img src="<?php print IMAGE_DIRECTORY . image_link($product['product_img']); ?>"></img>
                    <p><?php print $product['item_name']?> / <?php print product_status_int_to_str($product['product_status'])?></p>
                  </a>
                </div>
              <?php
                  }
              ?>
              </div>
              <?php
                } else {
              ?>
              <p>データがありません</p>
              <?php
                }
              ?>
            </div>
          </div>
          
        </div>
        
        <?php
          if ($target_user_id === $my_user_id) {
        ?>
        
        <hr>
        <form method="post" enctype="multipart/form-data" action='user_info.php'>
          <div>
            <input type="hidden" name="process_kind" value="to_update_user_info">
            <input type="hidden" name="target_user_id" value="<?php print $target_user_id?>">
            <input type="submit" class="display_block block_center_width" value="登録情報を変更する">
          </div>
        </form>
        <?php
          }
        ?>
        
      </div>
      
      <?php
      }
      ?>
      
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