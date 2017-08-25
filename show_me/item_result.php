<?php
/*------------------------------------------
商品登録結果
------------------------------------------*/
header('Content-Type: text/html; charset=UTF-8');

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';


$item_id = '';
$item_name = '';
$str_mode = '';

// 登録できていれば処理続行
if (isset($_COOKIE['item_id']) === TRUE) {
  $item_id = $_COOKIE['item_id'];
  $item_name = $_COOKIE['item_name'];
  $str_mode = $_COOKIE['item_info_mode'];
  
  if((int)$str_mode === 0){
    $str_mode = 'を出品';
  } else {
    $str_mode = 'の情報を変更';
  }
  
  print 'item_ID: ' . $item_id;
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