<?php
/*------------------------------------------
ユーザー情報（表示のみ）
------------------------------------------*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';

//----- 変数初期化 -----//
$categories_mastar = [];
$skills_mastar = [];

$err_msg = [];
$my_user_id = '';
$target_user_id = '';
$look_user = [];
$categories_name_list = [];
$skills_name_list = [];

$search_where = '';
$seller_list = [];
$product_list = [];
$url_seller_items_list = 'item_list.php?page_type=seller_list';
$url_product_list = 'product_list.php';

$page_title = 'ユーザー情報';
$look_user_img = 'no';

$header_user_name = '';
$header_user_img = '';
//----------------------//


//------------------------------------------------------------
// CookieにユーザーIDが残っていれば取得、残っていなければログイン画面へ
//------------------------------------------------------------
if ((isset($_COOKIE['user_id']) === TRUE) && ($_COOKIE['user_id'] > 0)){
  
  $my_user_id = $_COOKIE['user_id'];
  $header_user_name = $_COOKIE['user_name'];
  $header_user_img = $_COOKIE['user_img'];
} else {
  
  //---------- ログインページへ ----------//
  header('location: login.php');
  exit;
  //----------------------------------//  
}


//------------------------------------------------------------
// $_GETから閲覧対象のユーザーIDを取得
//------------------------------------------------------------
if((isset($_GET)) && (count($_GET) > 0)) {
  if ((isset($_GET['target_user_id'])) && ($_GET['target_user_id'] > 0)) {
    $target_user_id = $_GET['target_user_id'];
    $url_seller_items_list = $url_seller_items_list.'&target_user_id='.$target_user_id;
    $url_product_list = $url_product_list . '?target_user_id='.$target_user_id;
  } else {
    $err_msg[] = 'サーバーからユーザー情報を取得できませんでした。';
  }
} else {
  $err_msg[] = '情報がありません。';
}


//------------------------------------------------------------
// DBに接続
//------------------------------------------------------------
try {
  // DB接続(時刻も取得)
  $dbh = get_db_connect();
  $access_datetime = date('Y-m-d H:i:s');
  
  // DB接続情報が取得できていて
  if (count($dbh) > 0){

    // エラーが無ければ
    if (isset($err_msg) === FALSE || count($err_msg) === 0)  {
      
      // カテゴリ・使用ソフトのマスターを取得
      $categories_mastar = get_categories_table_list($dbh);
      $skills_mastar = get_skills_table_list($dbh);
      
      // ユーザー情報を取得
      try {
        
        $look_user = get_user_by_id($dbh, $target_user_id);
        
        // 取得できている時
        if(isset($look_user) === TRUE && count($look_user) > 0) {
          
          $page_title = $look_user[0]['user_name'] . ' さん';
          
          // 削除されたユーザーかどうか
          if (isset($look_user[0]['deleted_datetime']) === TRUE) {
            $err_msg[] = 'このユーザーは削除されています。';
          } else {
            // 画像ファイルを判定
            $look_user_img = image_link($look_user[0]['user_img']);
            
            // カテゴリ・使用ソフトを名前の配列に変換して取得
            $categories_name_list = id_to_name($categories_mastar, $look_user[0]['categories'], 'category_id', 'category_name');
            $skills_name_list = id_to_name($skills_mastar, $look_user[0]['skills'], 'skill_id', 'skill_name');
            
            //-----------------------------------------------------------------
            // 出品リストを取得
            $search_where = 'where i.deleted_datetime is null and i.seller_user_id = ' . $target_user_id;
            if($target_user_id !== $my_user_id){
              $search_where = $search_where . ' and i.item_status <> 0';
            }
            $search_where = $search_where . ' order by i.updated_datetime desc limit 3';
            $seller_list = get_items_table_list($dbh, $search_where);
            //-----------------------------------------------------------------
            // 制作リストを取得
            $search_where = 'where p.deleted_datetime is null and productor.user_id = ' . $target_user_id;
            if($target_user_id !== $my_user_id){
              $search_where = $search_where . ' and p.product_status <> 2';
            }
            
            $search_where = $search_where . ' order by (CASE WHEN product_status = 1 THEN 1 WHEN product_status = 0 THEN 2 ELSE 9 END) asc, p.updated_datetime desc limit 3';
            $product_list = get_products_table_list($dbh, $search_where);
            //-----------------------------------------------------------------
          }
          
        // 取得できていない時
        } else {
          $err_msg[] = 'データベースからユーザー情報を取得できませんでした。';
        }
      } catch (PDOException $e) {
          // 例外をスロー
          throw $e;
      }
    }
  }

} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}


/*=====================================================*/
// ログイン画面テンプレートファイル読み込み
include_once './view/V_user_profile.php';