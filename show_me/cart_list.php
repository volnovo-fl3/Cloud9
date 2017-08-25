<?php
/*------------------------------------------
カート確認
------------------------------------------*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';


//----- 変数初期化 -----//
$user_id = '';
$user = [];
$categories_master = [];
$skills_master = [];
$carts_unpaid_sum = [];
$carts_unpaid_list = [];

$search_where = '';
//----------------------//

// Cookieにページ遷移履歴を保存
setcookie('last_page', 'mypage.php');

// ユーザーIDを取得
if (isset($_COOKIE['user_id']) === TRUE) {
  $user_id = $_COOKIE['user_id'];
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
      $categories_master = get_categories_table_list($dbh);
      $skills_master = get_skills_table_list($dbh);
      $carts_unpaid_sum = get_carts_unpaid_sum($dbh, $user_id);
      
      // 検索条件のwhere文を作成し、検索
      $search_where =
        "where
          cart.bought_datetime is null
          and cart.deleted_datetime is null
          and cart.buyer_user_id = $user_id";
      $carts_unpaid_list = get_carts_table_list($dbh, $search_where);
      
    } catch (PDOException $e) {
        // 例外をスロー
        throw $e;
    }
  }
} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}


/*=====================================================*/
// カート確認テンプレートファイル読み込み
include_once './view/V_cart_list.php';

?>