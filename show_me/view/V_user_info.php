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
  </head>

  <body>
    <h1><?php print $page_name ?></h1>
    
    <?php
    if (isset($err_msg) && count($err_msg) > 0) {
      foreach ($err_msg as $key => $error) {
    ?>
    <ul>
      <li><p><?php print entity_str($error)?></p></li>
    </ul>
    <?php
      }
    }
    ?>
    
    <form method="post" enctype="multipart/form-data">
      <h2>基本情報</h2>
      <div>
        <label>ユーザー名：<input type="text" name="user_name" value="<?php print $user_name?>"></label>
      </div>
      <div>
        <label>パスワード：<input type="password" name="password" value="<?php print $password?>"></label>
      </div>
      <div>
        <label>所属企業・団体：<input type="text" name="user_affiliation" value="<?php print $user_affiliation?>"></label>
      </div>
      <div>
        <label>自己紹介文：<input type="text" name="user_self_introduction" value="<?php print $user_self_introduction?>"></label>
      </div>
      <div>
      <?php
      // 新規登録モードなら画像を選択
        if ($mode === 0) {
      ?>
        <label>プロフィール画像：<input type="file" name="user_img"></label>
      <?php
      // 更新モードなら画像を表示
        } else if ($mode === 1) {
      ?>
      <img src="<?php print IMAGE_DIRECTORY . $user_img; ?>" height="200">
      <div>
        <input type="radio" name="radio_img_change" value="1" onclick="change_image(1)"/><label>画像を変更する</label>
        <input type="radio" name="radio_img_change" value="0" onclick="change_image(0)" checked="checked" /><label>画像を変更しない</label>
      </div>
      <div id="change_user_img" style="display:none;">
        <label>プロフィール画像：<input type="file" name="user_img"></label>
      </div>
      <?php
        }
      ?>
      </div>
      
      <hr>
      <h2>対応カテゴリ</h2>
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
      
      <hr>
      <h2>使用ソフト</h2>
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
      <div>
      <?php if($mode === 0) {?>
        <input type="hidden" name="process_kind" value="user_insert">
        <input type="submit" value="新規登録">
      <?php } else if ($mode === 1) {?>
        <input type="hidden" name="target_user_id" value="<?php print $target_user_id?>">
        <input type="hidden" name="process_kind" value="user_update">
        <input type="submit" value="更新">
      <?php }?>
      </div>
    </form>
  </body>
</html>