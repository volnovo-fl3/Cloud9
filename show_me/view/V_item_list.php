<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | <?php print $page_name ?></title>
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
        
        <h1><?php print $page_name ?></h1>
        <div class="flexbox">
        
          <!-- 左側 -->
          <div class="flex_1">
            <div class="add_row user_profiles_panel_back">
              <div class="user_profiles_panel">
                <h2 class="inline_center_width">検索条件</h2>
                
                <form>
                  
                  <h3>カテゴリ</h3>
                  <?php
                    if (count($categories_master) > 0) {
                      foreach($categories_master as $key => $category) {
                  ?>
                  <div>
                    <input
                      type="checkbox"
                      name="checked_categories[]"
                      value="<?php print $category['category_id']?>"
                      <?php
                        // 更新時、既に登録されている項目にはチェックを入れておく
                        if ($mode === 1){
                          if (count($selected_categories) > 0){
                            foreach($selected_categories as $key => $selected_category_id) {
                              if ($category['category_id'] === $selected_category_id) {
                      ?>
                      checked="checked"
                      <?php
                              }
                            }
                          }
                        }
                      ?>
                    ><?php print $category['category_name']?>
                  </div>
                  <?php
                      }
                    }
                  ?>                
                  
                  <h3>使用ソフト</h3>
                  <?php
                    if (count($skills_master) > 0) {
                      foreach($skills_master as $key => $skill) {
                  ?>
                  <div>
                    <input
                      type="checkbox"
                      name="checked_skills[]"
                      value="<?php print $skill['skill_id']?>"
                      <?php
                        // 更新時、既に登録されている項目にはチェックを入れておく
                        if ($mode === 1){
                          if (count($selected_skills) > 0){
                            foreach($selected_skills as $key => $selected_skill_id) {
                              if ($skill['skill_id'] === $selected_skill_id) {
                      ?>
                      checked="checked"
                      <?php
                              }
                            }
                          }
                        }
                      ?>
                      ><?php print $skill['skill_name']?>
                  </div>
                  <?php
                      }
                    }
                  ?>

                  <hr>
                  
                  <input type="hidden" name="process_kind" value="item_search">
                  <input type="submit" class="display_block block_center_width" value="この条件で検索">

                </form>
                

              </div>
            </div>
          </div>
          
          <!-- 右側 -->
          <div class="flex_3 add_colmun_right">

            <?php
              if((isset($carts_unpaid) === TRUE) && (count($carts_unpaid) > 0)){
            ?>
            <div class='add_row message_box'>
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/cart_list.php">
                <p class="Text_Color_red">カートに <?php print entity_str($carts_unpaid[0]['cart_sum_amount']); ?> 点の商品があります。</p>
              </a>
            </div>
            <?php
              }
            ?>
            
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
          ?>
            
          <?php
          // 対象商品がないとき
            if (count($items_list) < 1) {
          ?>
            <div class="add_row">
              <p>対象の商品が見つかりません。</p>  
            </div>
            
          <?php
          // あるとき！ → 商品を表示
            } else {
              foreach ($items_list as $key => $item){
          ?>
            <div class="add_row">
              <div class="item_panel01" style="background-color:<?php print $item['category_color']?>;">
                <div class="item_img">
                  <img src="<?php print IMAGE_DIRECTORY . image_link($item['item_img']); ?>" class="image_size_to_panel_radius"></img>
                </div>
                <div class="item_info">
                  <div class="item_title flexbox">
                    <p><?php print $item['item_name']?></p>
                    <form method="post" enctype="multipart/form-data" action='item_details.php'>
                      <input type="hidden" name="process_kind" value="to_item_details">
                      <input type="hidden" name="target_item_id" value="<?php print $item['item_id']?>">
                      <input type="submit" class="item_detail" value="商品の詳細を見る">
                    </form>
                  </div>
                  <p class="price">¥<?php print $item['price']?></p>
                  <p class="seller_name">
                    <?php print $item['seller_user_name']?> さん
                    <?php
                      if((isset($item['seller_user_affiliation']) === TRUE) && (mb_strlen($item['seller_user_affiliation']) > 0)) {
                        print ' ('. $item['seller_user_affiliation'] .') ';
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
            
          </div>
          
          
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