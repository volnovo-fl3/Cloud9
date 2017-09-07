<?php
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
      
      // 検索条件のwhere文を作成し、検索
      $items_list = get_items_table_list($dbh, $search_where);
      
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