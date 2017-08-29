<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | ログイン</title>
    <link rel="shortcut icon" href="font_icon/favicon.ico">
    <link rel="stylesheet" href="css/html5reset-1.6.1.css">
    <link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/show_me.css">
</head>
  
  <body class="Background_Color_default wf-notosansjapanese">
    
    <div id="login">
      
      <img src="font_icon/light.png" class="light_image_size block_center_width"></img>
      <p class="sub_title">クリエイターのための課題販売サイト</p>
      <img src="font_icon/logo01.png" class="logo01_image_size block_center_width"></img>
      
      <div class="login_form">
        <?php //ログアウト後であれば表示
          if (isset($logout_message) && mb_strlen($logout_message, HTML_CHARACTER_SET) > 0)
        ?>
        <div class="message_box">
          <p><?php print entity_str($logout_message)?></p>
        </div>
        <?php
        ?>
        
        <?php //エラーメッセージを表示
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
        
        <form method="post" enctype="multipart/form-data">
          <div class="login_form_input flexbox">
            <p class="flex_1">ユーザー</p>
            <input class="flex_3" type="text" name="user_name">
          </div>
          <div class="login_form_input flexbox">
            <p class="flex_1">パスワード</p>
            <input class="flex_3" type="password" name="password"></label>
          </div>
          <input type="hidden" name="process_kind" value="login">
          <input type="submit" class="display_block block_center_width" value="ログイン">
        </form>
        
        <hr>
        
        <form method="post" enctype="multipart/form-data">
          <div>
            <input type="hidden" name="process_kind" value="to_user_insert">
            <input type="submit" class="display_block block_center_width" value="ユーザー新規登録">
          </div>
        </form>

      </div>
    </div>
  </body>
</html>