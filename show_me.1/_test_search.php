<?php
header('Content-Type: text/html; charset=UTF-8');

/*------------------------------------------
【テスト】商品検索
------------------------------------------*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';

//----- 変数初期化 -----//
$page_type = 'all_list';
$page_name = '【テスト】商品検索';

$user_id = '7';
$user = [];
$categories_master = [];
$skills_master = [];
$carts_unpaid = [];

$err_msg = [];
$items_list = [];
$search_where = '';

$selected_categories = [];
$selected_skills = [];

$header_user_name = '';
$header_user_img = '';
//----------------------//



//------------------------------------------------------------
// DBに接続
//------------------------------------------------------------
try {
  // DB接続(時刻も取得)
  $dbh = get_db_connect();
  $access_datetime = date('Y-m-d H:i:s');
  
  // DB接続情報が取得できていて
  if (count($dbh) > 0){

    try {
      
      // ユーザー情報、カテゴリ・使用ソフトマスタを取得
      $user = get_user_by_id($dbh, $user_id);
      $header_user_name = $user[0]['user_name'];
      $header_user_img = image_link($user[0]['user_img']);

      // 値が入っていれば配列化
      if(mb_strlen($user[0]['categories'], HTML_CHARACTER_SET) > 0) {
        $selected_categories = explode(',', $user[0]['categories']);
      }
      if(mb_strlen($user[0]['skills'], HTML_CHARACTER_SET) > 0) {
        $selected_skills = explode(',', $user[0]['skills']);
      }
      
      var_dump($selected_categories);
      print "<br>";
      var_dump($selected_skills);
      print "<br>";
      
      $categories_master = get_categories_table_list($dbh);
      $skills_master = get_skills_table_list($dbh);
      $carts_unpaid = get_carts_unpaid_sum($dbh, $user_id);
      
      if ($page_type === 'all_list') {
        // 検索条件のwhere文を作成し、検索
        $search_where = 'where i.deleted_datetime is null';
        
        $search_where = $search_where . ' and ';
        $i = 1;
        foreach($selected_categories as $key => $t_ca) {
          if ($i !== 1) {
            $search_where = $search_where . ' or ';
          }
          $search_where = $search_where . '(i.categories like \'%,'. $t_ca .',%\' or i.categories like \''. $t_ca .',%\' or i.categories like \'%,'. $t_ca .'\')';
          $i ++;
        }
        
        var_dump($search_where);
      print "<br>";
        
        $items_list = get_items_table_list($dbh, $search_where);
      }
      else if ($page_type === 'seller_list') {
        // 検索条件のwhere文を作成し、検索
        $search_where = 'where i.seller_user_id = ' . $user_id;
        $items_list = get_items_table_list($dbh, $search_where);
      }
      
    } catch (PDOException $e) {
        // 例外をスロー
        throw $e;
    }


  }

} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}

?>


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
                  <div class="item_title">
                    <p><?php print $item['item_name']?></p>
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
                <div class="item_button_parent">
                  <div class="item_button">
                    <form method="post" enctype="multipart/form-data" action='item_details.php'>
                      <input type="hidden" name="process_kind" value="to_item_details">
                      <input type="hidden" name="target_item_id" value="<?php print $item['item_id']?>">
                      <input type="submit" class="block" value="商品の詳細を見る">
                    </form>
                  </div>
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