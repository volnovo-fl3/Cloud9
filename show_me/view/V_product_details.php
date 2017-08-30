<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | 作品詳細</title>
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
      
      // エラーがなければ商品情報表示
      else {
      ?>

      <?php
      // 商品情報を取得できていれば表示
        if((isset($look_product) === TRUE) && (count($look_product) > 0)){
      ?>
        <h1><?php print $look_product[0]['item_name']?></h1>
        <div class="add_row">
          <p class="inline_center_width"><?php print $look_product[0]['productor_user_name']?> さんの作品です</p>
        </div>
        <div class="add_row">
          <p class="inline_center_width"><?php print entity_str(product_status_int_to_str($look_product[0]['product_status']))?></p>
        </div>
        
        <div class="details">
          
          <div class="add_row inline_center_width">
            <img src="<?php print IMAGE_DIRECTORY . $look_product_img; ?>" class="product_details_image">
          </div>
          
          <h2>作品リンク</h2>
          <?php
            if(mb_strlen($look_product[0]['product_link']) > 0) {
          ?>
            <a href="<?php print $look_product[0]['product_link']?>"><p>→ リンク先へ</p></a>
          <?php
            } else {
          ?>
            <p>登録されていません</p>
          <?php
            }
          ?>

          <h2>制作者コメント</h2>
          <p>
            <?php print nl2br(str_is_regist($look_product[0]['product_comment']), false)?>
          </p>

          <hr>
        </div>
          
        <h1>商品情報</h1>
        <div class="details">

          <div class="add_row">
            <p class="inline_center_width"><?php print $look_product[0]['item_name']?></p>
          </div>
          
          <div class="add_row inline_center_width">
            <img src="<?php print IMAGE_DIRECTORY . $look_item_img; ?>" class="user_profiles_image">
          </div>
          <h2>商品紹介</h2>
          <p>
            <?php print nl2br(str_is_regist($look_product[0]['item_introduction']), false)?>
          </p>
          <h2>商品紹介(詳細)</h2>
          <p>
            <?php print nl2br(str_is_regist($look_product[0]['item_introduction_detail']), false)?>
          </p>
          
          <h2>対応カテゴリ</h2>
          <p><?php print entity_str(str_is_regist(implode(' / ', $categories_name_list))) ?></p>
          
          <h2>使用ソフト</h2>
          <p><?php print entity_str(str_is_regist(implode(' / ', $skills_name_list))) ?></p>
          
          <hr>

        </div>
      

        <?php
          //ログイン者 = 制作者であれば更新・削除できる
            if ((string)$my_user_id === (string)$look_product[0]['productor_user_id']) {
        ?>
        <div class="add_row">
          <form method="post" enctype="multipart/form-data" action='product_info.php'>
            <input type="hidden" name="process_kind" value="to_update_product_info">
            <input type="hidden" name="target_product_id" value="<?php print $look_product[0]['product_id']?>">
            <input type="submit" class="display_block block_center_width" value="制作情報を更新する">
          </form>
        </div>
        <div class="add_row">
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="process_kind" value="delete_product">
            <input type="hidden" name="target_product_id" value="<?php print $look_product[0]['product_id']?>">
            <input type="submit" class="display_block block_center_width" value="この作品を削除する">
          </form>
        </div>
        
        <?php
            }
        ?>
        <?php
          }
        ?>
        
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