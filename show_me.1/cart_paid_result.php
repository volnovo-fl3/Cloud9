<?php
/*------------------------------------------
購入結果
------------------------------------------*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';

//----- 変数初期化 -----//
$err_msg = [];

$cart_ids_paid_str = '';
$cart_price_paid = 0;
$cart_amount_paid = 0;

$search_where = '';
$paid_item_list = [];

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
// Cookieから情報を取得
//------------------------------------------------------------
if(
  (isset($_COOKIE['cart_ids_paid']) === TRUE) && (mb_strlen($_COOKIE['cart_ids_paid']) > 0)
  && (isset($_COOKIE['cart_price_paid']) === TRUE) && (mb_strlen($_COOKIE['cart_price_paid']) > 0)
  && (isset($_COOKIE['cart_amount_paid']) === TRUE) && (mb_strlen($_COOKIE['cart_amount_paid']) > 0)
) {
  $cart_ids_paid_str = $_COOKIE['cart_ids_paid'];
  $cart_price_paid = $_COOKIE['cart_price_paid'];
  $cart_amount_paid = $_COOKIE['cart_amount_paid'];
}
else {
  $err_msg[] = 'Cookieを取得できませんでした。';
}

// クッキーを削除
setcookie('cart_ids_paid', '');
setcookie('cart_price_paid', '');
setcookie('cart_amount_paid', '');


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
      $search_where = str_id_to_where_id($cart_ids_paid_str, 'cart.cart_id');
      $paid_item_list = get_carts_table_list($dbh, $search_where);
    }
  }

} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}


/*=====================================================*/
// 購入結果画面テンプレートファイル読み込み
include_once './view/V_cart_paid_result.php';

?>