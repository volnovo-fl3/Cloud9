<script>
  // ---------------------------------------------
  // (更新時のみ)画像を変更するかどうか
  // 0:変更しない 1:変更する
  // ---------------------------------------------
  var is_change_image;
  is_change_image = 0;
  
  function change_image(flg_int) {
    
    // フラグを変更
    is_change_image = flg_int;
    
    // 表示・非表示切り替え
    if (is_change_image === 0) {
      document.getElementById("change_product_img").style.display="none";
    }
    if (is_change_image === 1) {
      document.getElementById("change_product_img").style.display="block";
    }
  }
</script>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | 作品情報更新</title>
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
      ?>
      
      <?php
      // 商品情報を取得できていれば表示
        if((isset($look_product) === TRUE) && (count($look_product) > 0)){
      ?>

      <h1>作品情報</h1>
      
      <div class="insert_update_form">
        
        <form method="post" enctype="multipart/form-data">
        
          <div class="block">
            <div class="flexbox">
              <div class="flex_1 subject">
                <div class="flexbox">
                  <p class="block_center_height">商品名</p>
                </div>
              </div>
              <div class="flex_3">
                <p><?php print $look_product[0]['item_name']?></p>
              </div>
            </div>
          </div>
          
          <div class="block">
            <div class="flexbox">
              <div class="flex_1 subject">
                <div class="flexbox">
                  <p class="block_center_height">出品者</p>
                </div>
              </div>
              <div class="flex_3">
                <p><?php print $look_product[0]['seller_user_name']?> さん</p>
              </div>
            </div>
          </div>

          <div class="block">
            <div class="flexbox">
              <div class="flex_1 subject">
                <p class="block_center_height">制作者コメント</p>
              </div>
              <div class="flex_3">
                <textarea name="product_comment"><?php print nl2br($product_comment, false)?></textarea>
              </div>
            </div>
            <p class="tips inline_center_width">制作者コメントは 2000文字以内 で入力してください</p>
          </div>
          
          <div class="block">
            <div class="flexbox">
              <div class="flex_1 subject">
                <div class="flexbox">
                  <p class="block_center_height">ステータス</p>
                </div>
              </div>
              <div class="flex_3">
                <select name="product_status">
                  <option value="0" <?php if($product_status === 0){print selected;} ?>>未完成</option>
                  <option value="1" <?php if($product_status === 1){print selected;} ?>>完成(公開中)</option>
                  <option value="2" <?php if($product_status === 2){print selected;} ?>>完成(非公開)</option>
                </select>
              </div>
            </div>
          </div>
          
          <div class="block">
            <div class="flexbox">
              <div class="flex_1 subject">
                <div class="flexbox">
                  <p class="block_center_height">作品リンク</p>
                </div>
              </div>
              <div class="flex_3">
                <input type="text" class="text_width" name="product_link" value="<?php print $product_link?>">
              </div>
            </div>
          </div>
          

          <div class="block">
              
            <div class="flexbox">
              <div class="flex_1 subject">
                <p class="block_center_height">作品サムネイル</p>
              </div>
              <div class="flex_3">
                <img src="<?php print IMAGE_DIRECTORY . $look_product_img; ?>" height="200">
                <div>
                  <input type="radio" name="radio_img_change" value="1" onclick="change_image(1)"/><label>画像を変更する</label>
                  <input type="radio" name="radio_img_change" value="0" onclick="change_image(0)" checked="checked" /><label>画像を変更しない</label>
                </div>
                <div id="change_product_img" style="display:none;">
                  <input type="file" name="product_img">
                </div>
              </div>
            </div>
            <p class="tips inline_center_width">.jpg / .png / .gif に対応しています。</p>
            
          </div>
          
          <hr>
          
          <div>
            <input type="hidden" name="process_kind" value="update_product_info">
            <input type="hidden" name="target_product_id" value="<?php print $look_product[0]['product_id']?>">
            <input type="submit" class="display_block block_center_width" value="更新">
          </div>
    
        </form>
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