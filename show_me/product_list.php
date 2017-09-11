<?php
/*------------------------------------------
制作リスト
------------------------------------------*/
// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';

//----- 変数初期化 -----//
$page_type = 'by_user';
$page_name = '制作リスト';

$err_msg = [];
$user_id = '';
$target_user_id = '';
$target_user = []; 

$target_item_id = '';
$target_item = []; 

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
  $target_user_id = $user_id;
}

else {
//------------------------------------------------------------
// 残ってなければ、ログイン画面へ
//------------------------------------------------------------
  header('location: login.php');
  exit;
}


//------------------------------------------------------------
// $_GET
//------------------------------------------------------------
if((isset($_GET) === TRUE) && (count($_GET) > 0)){
  if((isset($_GET['target_user_id']) === TRUE) && (mb_strlen($_GET['target_user_id']) > 0)){
    $target_user_id = $_GET['target_user_id'];
  }
  
  if((isset($_GET['page_type']) === TRUE) && (mb_strlen($_GET['page_type']) > 0)){
    if($_GET['page_type'] === 'by_item'){
      $page_type = 'by_item';
      $target_item_id = $_GET['target_item_id'];
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
    // エラーがなければ
    if(count($err_msg) === 0) {
      
      if($page_type === 'by_user'){
        // ユーザー情報を取得
        $target_user = get_user_by_id($dbh, $target_user_id);
        $page_name = $target_user[0]['user_name'] . ' さんの' . $page_name;
        
        $search_where = str_id_to_where_id($target_user_id, 'cart.buyer_user_id');
        $search_where = $search_where . ' and p.deleted_datetime is null';
        if($target_user_id !== $user_id){
          $search_where = $search_where . ' and p.product_status <> 2';
        }
        $search_where = $search_where . ' order by (CASE WHEN product_status = 1 THEN 1 WHEN product_status = 0 THEN 2 ELSE 9 END) asc, p.updated_datetime desc';
      }
      
      else if($page_type === 'by_item'){
        // 商品情報を取得
        $str_where = "where i.item_id = $target_item_id";
        $target_item = get_items_table_list($dbh, $str_where);
        $page_name = '『' . $target_item[0]['item_name'] . '』の' . $page_name;
        
        $search_where = str_id_to_where_id($target_item_id, 'i.item_id');
        $search_where = $search_where . ' and p.deleted_datetime is null and p.product_status <> 2';
        $search_where = $search_where . ' order by (CASE WHEN product_status = 1 THEN 1 WHEN product_status = 0 THEN 2 ELSE 9 END) asc, p.updated_datetime desc';
      }
      
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