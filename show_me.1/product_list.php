<?php
/*------------------------------------------
制作リスト
------------------------------------------*/
// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';

//----- 変数初期化 -----//
$err_msg = [];
$user_id = '';

$search_where = '';
$products_list = [];

$header_user_name = '';
$header_user_img = '';
//----------------------//


// Cookieにページ遷移履歴を保存
setcookie('product_list', 'login.php');


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
// DBに接続
//------------------------------------------------------------
try {
  // DB接続(時刻も取得)
  $dbh = get_db_connect();
  $access_datetime = date('Y-m-d H:i:s');
  
  // DB接続情報が取得できていて
  if (count($dbh) > 0){
    // エラーがなければ
    if(count($err_msg) === 0) {
      $search_where = str_id_to_where_id($user_id, 'cart.buyer_user_id');
      $search_where = $search_where . ' and p.deleted_datetime is null';
      $products_list = get_products_table_list($dbh, $search_where);
    }
  }

} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}


/*=====================================================*/
// 制作リスト画面テンプレートファイル読み込み
include_once './view/V_product_list.php';

?>