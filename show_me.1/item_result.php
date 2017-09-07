<?php
/*------------------------------------------
商品登録結果
------------------------------------------*/
header('Content-Type: text/html; charset=UTF-8');

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';


//----- 変数初期化 -----//
$item_id = '';
$item_name = '';
$item_img = '';
$str_mode = '';

$header_user_name = '';
$header_user_img = '';
//----------------------//


//------------------------------------------------------------
// Cookieに商品IDが残っていれば取得、残っていなければログイン画面へ
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


// 登録できていれば処理続行
if (isset($_COOKIE['item_id']) === TRUE) {
  $item_id = $_COOKIE['item_id'];
  $item_name = $_COOKIE['item_name'];
  $item_img = $_COOKIE['item_img'];
  $str_mode = $_COOKIE['item_info_mode'];
  
  if((int)$str_mode === 0){
    $str_mode = 'を出品';
  } else {
    $str_mode = 'の情報を変更';
  }
}
// 登録できていなければ
else {
  print 'IDを取得できませんでした。';
}

// クッキーを削除
setcookie('item_id', '');
setcookie('item_name', '');

/*=====================================================*/
// 商品登録結果画面テンプレートファイル読み込み
include_once './view/V_item_result.php';

?>