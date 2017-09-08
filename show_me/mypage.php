<?php
header('Content-Type: text/html; charset=UTF-8');
/*------------------------------------------
マイページ
------------------------------------------*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';


//----- 変数初期化 -----//
$user_id = '';
$user = [];
$user_img = '';

$categories_master = [];
$skills_master = [];
$categories_name_list = '';
$skills_name_list = '';

$carts_unpaid = [];
$user_products = [];

$selected_categories = [];
$selected_skills = [];
$recommended_items = [];
$url_recommended_items_list = 'item_list.php';
$url_recommended_items_list_material = '';

$search_array = '';
$search_where = '';
$header_user_name = '';
$header_user_img = '';
//----------------------//

// Cookieにページ遷移履歴を保存
setcookie('last_page', 'mypage.php');

// ユーザーIDを取得
if (isset($_COOKIE['user_id']) === TRUE) {
  $user_id = $_COOKIE['user_id'];
  $header_user_name = $_COOKIE['user_name'];
  $header_user_img = $_COOKIE['user_img'];
}
// 取得できなければ、ログイン画面へ
else {
  //---------- ログインページへ ----------//
  header('location: login.php');
  exit;
  //----------------------------------//
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

    try {
      
      // ユーザー情報、カテゴリ・使用ソフトマスタを取得
      $user = get_user_by_id($dbh, $user_id);
      $user_img = image_link($user[0]['user_img']);
      $categories_master = get_categories_table_list($dbh);
      $skills_master = get_skills_table_list($dbh);
      $carts_unpaid = get_carts_unpaid_sum($dbh, $user_id);
      
      // カテゴリ・使用ソフトを名前の配列に変換して取得
      $categories_name_list = id_to_name($categories_master, $user[0]['categories'], 'category_id', 'category_name');
      $skills_name_list = id_to_name($skills_master, $user[0]['skills'], 'skill_id', 'skill_name');
      
      // 値が入っていれば配列化
      if(mb_strlen($user[0]['categories'], HTML_CHARACTER_SET) > 0) {
        $selected_categories = explode(',', $user[0]['categories']);
      }
      if(mb_strlen($user[0]['skills'], HTML_CHARACTER_SET) > 0) {
        $selected_skills = explode(',', $user[0]['skills']);
      }
      
      //---------------------------------------------------------------------------------------------
      // 制作したものリスト
      $search_where = "where p.deleted_datetime is null and p.product_status <> 2 and productor.user_id = $user_id order by p.updated_datetime desc limit 4";
      $user_products = get_products_table_list($dbh, $search_where);
      
      //---------------------------------------------------------------------------------------------
      // あなたへのおすすめ(登録したカテゴリ・使用ソフトが含まれる商品を抽出)
      $search_where = "where i.deleted_datetime is null and i.item_status <> 0";
      
      // カテゴリが選択されていれば
      if(count($selected_categories) > 0) {
        $search_array = $search_array . '(' . array_to_str_where('i.categories', $selected_categories) .')';
        $url_recommended_items_list_material = array_to_str_url('checked_categories', $selected_categories);
      }
      
      // 使用ソフトが選択されていれば
      if(count($selected_skills) > 0) {
        if (mb_strlen($search_array) > 0) {
          $search_array = $search_array . ' or ';
          $url_recommended_items_list_material = $url_recommended_items_list_material . '&';
        }
        $search_array = $search_array . '(' . array_to_str_where('i.skills', $selected_skills) .')';
        $url_recommended_items_list_material = $url_recommended_items_list_material . array_to_str_url('checked_skills', $selected_skills);
      }
      
      if (mb_strlen($search_array) > 0){
        $search_where = $search_where . ' and (' . $search_array . ')';
      }
      
      $search_where = $search_where . 'order by i.updated_datetime desc limit 4';
      $recommended_items = get_items_table_list($dbh, $search_where);
      
      if (mb_strlen($url_recommended_items_list_material) > 0){
        $url_recommended_items_list = $url_recommended_items_list . '?' . $url_recommended_items_list_material;
      }
      
      //---------------------------------------------------------------------------------------------
      
    } catch (PDOException $e) {
        // 例外をスロー
        throw $e;
    }
  }
} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}


/*=====================================================*/
// マイページテンプレートファイル読み込み
include_once './view/V_mypage.php';

?>