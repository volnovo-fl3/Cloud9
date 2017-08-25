<?php
/*------------------------------------------
商品一覧
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
$carts_unpaid = [];

$err_msg = [];
$items_list = [];
$search_where = '';
//----------------------//

// Cookieにページ遷移履歴を保存
setcookie('last_page', 'login.php');


//------------------------------------------------------------
// CookieにユーザーIDが残っていれば、ID表示
//------------------------------------------------------------
if ((isset($_COOKIE['user_id']) === TRUE) && ($_COOKIE['user_id'] > 0)){
  $user_id = $_COOKIE['user_id'];
}

else {
//------------------------------------------------------------
// 残ってなければ、ログイン画面へ
//------------------------------------------------------------
  
  header('location: login.php');
  exit;

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
// ログイン画面テンプレートファイル読み込み
include_once './view/V_item_list.php';

?>