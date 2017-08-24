<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | <?php print $page_title?></title>
  </head>

  <body>
    <h1><?php print $page_title?></h1>
    
    <?php // エラーがあれば表示
    if (isset($err_msg) && count($err_msg) > 0) {
      foreach ($err_msg as $key => $error) {
    ?>
    <ul>
      <li><p><?php print entity_str($error)?></p></li>
    </ul>
    <?php
      }
    }
    
    // エラーがなければユーザー情報表示
    else {
    ?>
    
    <h2>基本情報</h2>
    <div>
      <img src="<?php print IMAGE_DIRECTORY . $look_user_img; ?>" height="200">
      <label><?php print $look_user[0]['user_name']?>さん</label>
      <label><?php print $look_user[0]['user_affiliation']?></label>
    </div>
    
    <h3>自己紹介</h3>
    <div>
      <?php print $look_user[0]['user_self_introduction']?>
    </div>

    <hr>
    <h3>対応カテゴリ</h3>
    <?php
    // カテゴリ名(配列)を取得できたとき
      if(count($categories_name_list) > 0) {
        foreach($categories_name_list as $key => $category_name) {
    ?>
    <p><?php print entity_str($category_name)?></p>
    <?php
        }
    // できていないとき
      } else {
    ?>
    <p>登録されていません</p>
    <?php
      }
    ?>
    
    <h3>使用ソフト</h3>
    <?php
    // 使用ソフト名(配列)を取得できたとき
      if(count($skills_name_list) > 0) {
        foreach($skills_name_list as $key => $skill_name) {
    ?>
    <p><?php print entity_str($skill_name)?></p>
    <?php
        }
    // できていないとき
      } else {
    ?>
    <p>登録されていません</p>
    <?php
      }
    ?>
    
    <hr>
    
    <?php
      if ($target_user_id === $my_user_id) {
    ?>
    <form method="post" enctype="multipart/form-data" action='user_info.php'>
      <div>
        <input type="hidden" name="process_kind" value="to_update_user_info">
        <input type="hidden" name="target_user_id" value="<?php print $target_user_id?>">
        <input type="submit" value="登録情報を変更する">
      </div>
    </form>
    <?php
      }
    ?>
    
    <?php
    }
    ?>
    
  </body>
</html>