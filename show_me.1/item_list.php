<?php
/*------------------------------------------
商品一覧
------------------------------------------*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';

//----- 変数初期化 -----//
$page_type = 'all_list';
$page_name = '商品一覧';

$user_id = '';
$user = [];
$categories_master = [];
$skills_master = [];
$carts_unpaid = [];

$err_msg = [];
$items_list = [];
$search_where = '';

$header_user_name = '';
$header_user_img = '';
//----------------------//

// Cookieにページ遷移履歴を保存
setcookie('last_page', 'login.php');


//------------------------------------------------------------
// CookieにユーザーIDが残っていれば、ID表示
//------------------------------------------------------------
if ((isset($_COOKIE['user_id']) === TRUE) && ($_COOKIE['user_id'] > 0)){
  $user_id = $_COOKIE['user_id'];
  $header_user_name = $_COOKIE['user_name'];
  $header_user_img = $_COOKIE['user_img'];
}

else {
//------------------------------------------------------------
// 残ってなければ、ログイン画面へ
//------------------------------------------------------------
  header('location: login.php');
  exit;
}


//------------------------------------------------------------
// ページタイプを取得
// seller_list であれば $page_type を変更
//------------------------------------------------------------
if((isset($_GET) === TRUE) && (count($_GET) > 0)){
  if((isset($_GET['page_type']) === TRUE) && (mb_strlen($_GET['page_type']) > 0)){
    if($_GET['page_type'] === 'seller_list'){
      $page_name = '出品一覧';
      $page_type = $_GET['page_type'];
    }
  }
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
      $categories_master = get_categories_table_list($dbh);
      $skills_master = get_skills_table_list($dbh);
      $carts_unpaid = get_carts_unpaid_sum($dbh, $user_id);
      
      if ($page_type === 'all_list') {
        // 検索条件のwhere文を作成し、検索
        $search_where = 'where i.deleted_datetime is null';
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


/*=====================================================*/
// 商品一覧画面テンプレートファイル読み込み
include_once './view/V_item_list.php';

?>