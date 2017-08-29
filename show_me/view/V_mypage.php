<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | マイページ</title>
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
                <img src="<?php print IMAGE_DIRECTORY . $header_user_img; ?>" class="image_size_to_panel"></img>
              </div>
              <div class="block_center_height">
                <div>
                  <p><?php print $header_user_name?> さん</p>
                  <p class="logout"><a href="https://codeincubate-tanakanoboru.c9users.io/show_me/logout.php">ログアウト</a></p>
                </div>
              </div>
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
      <div class="container flexbox">
      
        <!-- 左側 -->
        <div class="flex_1">
          <div class="add_row user_profiles_panel_back">
            <div class="user_profiles_panel">
              <div class="image_panel_200 block_center_width">
                <img src="<?php print IMAGE_DIRECTORY . $user_img; ?>" class="image_size_to_panel"></img>
              </div>
              <p class="inline_center_width"><?php print $user[0]['user_name'] ?> さん</p>
              <?php
                if (mb_strlen($user[0]['user_affiliation']) > 0){
              ?>
              <p class="inline_center_width"><?php print $user[0]['user_affiliation'] ?></p>
              <?php
                }
              ?>
              
              <hr>
              <h3>対応カテゴリ</h3>
              <p class="name_list"><?php print entity_str(str_is_regist(implode(' / ', $categories_name_list))) ?></p>
              
              <h3>使用ソフト</h3>
              <p class="name_list"><?php print entity_str(str_is_regist(implode(' / ', $skills_name_list))) ?></p>

              <form method="post" enctype="multipart/form-data" action='user_profile.php'>
                <div class="add_row">
                  <input type="hidden" name="process_kind" value="to_user_profile">
                  <input type="hidden" name="target_user_id" value="<?php print $user_id?>">
                  <input type="submit" class="display_block block_center_width" value="登録情報確認">
                </div>
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