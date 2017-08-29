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
      document.getElementById("change_user_img").style.display="none";
    }
    if (is_change_image === 1) {
      document.getElementById("change_user_img").style.display="block";
    }
  }
</script>


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
                <img src="<?php print IMAGE_DIRECTORY . $header_user_img; ?>" class="image_size_to_panel"></img>
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
      
      <h1><?php print $page_name ?></h1>
      
      <div class="insert_update_form">
        
        <form method="post" enctype="multipart/form-data">
          <h2>基本情報</h2>
          
          <div class="block">
            <div class="flexbox">
              <div class="flex_1 subject">
                <div class="flexbox">
                  <p class="must block_center_height">(必須)</p>
                  <p class="block_center_height">ユーザー名</p>
                </div>
              </div>
              <div class="flex_3">
                <input type="text" class="text_width" name="user_name" value="<?php print $user_name?>">
              </div>
            </div>
            <p class="tips inline_center_width">ユーザー名は 100文字以内 で入力してください</p>
          </div>
          
          <div class="block">
            <div class="flexbox">
              <div class="flex_1 subject">
                <div class="flexbox">
                  <p class="must block_center_height">(必須)</p>
                  <p class="block_center_height">パスワード</p>
                </div>
              </div>
              <div class="flex_3">
                <input type="password" class="text_width" name="password" value="<?php print $password?>">
              </div>
            </div>
            <p class="tips inline_center_width">パスワードは 半角英数字 6文字～40文字 で入力してください</p>
          </div>
          
          <div class="block">
            <div class="flexbox">
              <div class="flex_1 subject">
                <p class="block_center_height">所属企業・団体</p>
              </div>
              <div class="flex_3">
                <input type="text" class="text_width" name="user_affiliation" value="<?php print $user_affiliation?>">
              </div>
            </div>
            <p class="tips inline_center_width">所属企業・団体は 100文字以内 で入力してください</p>
          </div>
          
          <div class="block">
            <div class="flexbox">
              <div class="flex_1 subject">
                <p class="block_center_height">自己紹介</p>
              </div>
              <div class="flex_3">
                <textarea name="user_self_introduction"><?php print nl2br(htmlspecialchars($user_self_introduction, ENT_QUOTES, "UTF-8"), false)?></textarea>
              </div>
            </div>
            <p class="tips inline_center_width">自己紹介は 2000文字以内 で入力してください</p>
          </div>
          
          <div class="block">
              
            <?php
            // 新規登録モードなら画像を選択
              if ($mode === 0) {
            ?>
            <div class="flexbox">
              <div class="flex_1 subject">
                <p class="block_center_height">プロフィール画像</p>
              </div>
              <div class="flex_3">
                <input type="file" name="user_img">
              </div>
            </div>
            <p class="tips inline_center_width">.jpg / .png / .gif に対応しています</p>
            
            <?php
            // 更新モードなら画像を表示
              } else if ($mode === 1) {
            ?>
            <div class="flexbox">
              <div class="flex_1 subject">
                <p class="block_center_height">プロフィール画像</p>
              </div>
              <div class="flex_3">
                <img src="<?php print IMAGE_DIRECTORY . $user_img; ?>" height="200">
                <div>
                  <input type="radio" name="radio_img_change" value="1" onclick="change_image(1)"/><label>画像を変更する</label>
                  <input type="radio" name="radio_img_change" value="0" onclick="change_image(0)" checked="checked" /><label>画像を変更しない</label>
                </div>
                <div id="change_user_img" style="display:none;">
                  <input type="file" name="user_img">
                </div>
              </div>
            </div>
            <p class="tips inline_center_width">.jpg / .png / .gif に対応しています。</p>
            <?php
              }
            ?>
            
          </div>
          
          <hr>
          <h2>対応カテゴリ</h2>
          <div class="chkbox_list block_center_width">
          <?php
            if (count($categories_master) > 0) {
              foreach($categories_master as $key => $category) {
          ?>
            <div class="chkbox">
              <input
                type="checkbox"
                name="checked_categories[]"
                value="<?php print $category['category_id']?>"
                <?php
                  // 更新時、既に登録されている項目にはチェックを入れておく
                  if ($mode == 1){
                    if (count($selected_categories) > 0){
                      foreach($selected_categories as $key => $selected_category_id) {
                        if ($category['category_id'] === (int)$selected_category_id) {
                ?>
                checked="checked";
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
          </div>

          <h2>使用ソフト</h2>
          <div class="chkbox_list block_center_width">
          <?php
            if (count($skills_master) > 0) {
              foreach($skills_master as $key => $skill) {
          ?>
            <div class="chkbox">
              <input
                type="checkbox"
                name="checked_skills[]"
                value="<?php print $skill['skill_id']?>"
                <?php
                  // 更新時、既に登録されている項目にはチェックを入れておく
                  if ($mode === 1){
                    if (count($selected_skills) > 0){
                      foreach($selected_skills as $key => $selected_skill_id) {
                        if ($skill['skill_id'] === (int)$selected_skill_id) {
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
          </div>
          
          <hr>
          <div>
          <?php if($mode === 0) {?>
            <input type="hidden" name="process_kind" value="user_insert">
            <input type="submit" class="display_block block_center_width" value="新規登録">
          <?php } else if ($mode === 1) {?>
            <input type="hidden" name="target_user_id" value="<?php print $target_user_id?>">
            <input type="hidden" name="process_kind" value="user_update">
            <input type="submit" class="display_block block_center_width" value="更新">
          <?php }?>
          </div>
        </form>
        
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