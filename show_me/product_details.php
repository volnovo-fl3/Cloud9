<?php
/*------------------------------------------
作品詳細（表示のみ）
------------------------------------------*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';

//----- 変数初期化 -----//
$categories_mastar = [];
$skills_mastar = [];

$err_msg = [];
$my_user_id = '';
$target_product_id = '';
$str_where = ''; //データベース検索条件
$look_product = [];

$look_product_img = 'no';
$look_item_img = 'no';
//----------------------//


//------------------------------------------------------------
// CookieにユーザーIDが残っていれば取得、残っていなければログイン画面へ
//------------------------------------------------------------
if ((isset($_COOKIE['user_id']) === TRUE) && ($_COOKIE['user_id'] > 0)){
  
  $my_user_id = $_COOKIE['user_id'];
} else {
  
  //---------- ログインページへ ----------//
  header('location: login.php');
  exit;
  //----------------------------------//  
}


//------------------------------------------------------------
// $_POST
//------------------------------------------------------------
if((isset($_POST)) && (count($_POST) > 0)) {
  
  // 作品IDを取得
  if ((isset($_POST['target_product_id'])) && ($_POST['target_product_id'] > 0)) {
    $target_product_id = $_POST['target_product_id'];
  } else {
    $err_msg[] = 'サーバーから作品情報を取得できませんでした。';
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

    // POSTで
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
      
      // エラーが無ければ
      if (isset($err_msg) === FALSE || count($err_msg) === 0)  {
        
        // カテゴリ・使用ソフトのマスターを取得
        $categories_mastar = get_categories_table_list($dbh);
        $skills_mastar = get_skills_table_list($dbh);
        
        // 商品情報を取得
        try {
          
          $str_where = 'where p.product_id = ' . $target_product_id;
          $look_product = get_products_table_list($dbh, $str_where);
          
          // 取得できている時
          if(isset($look_product) === TRUE && count($look_product) > 0) {
            
            // 画像ファイルを判定
            $look_product_img = image_link($look_product[0]['product_img']);
            $look_item_img = image_link($look_product[0]['item_img']);
            
            // カテゴリ・使用ソフトを名前の配列に変換して取得
            $categories_name_list = id_to_name($categories_mastar, $look_product[0]['categories'], 'category_id', 'category_name');
            $skills_name_list = id_to_name($skills_mastar, $look_product[0]['skills'], 'skill_id', 'skill_name');
            
          // 取得できていない時
          } else {
            $err_msg[] = 'データベースから商品情報を取得できませんでした。';
          }
          
          //-----------------------------------------
          // 作品を削除するとき
          //-----------------------------------------
          if((isset($_POST['process_kind']) === TRUE) && ($_POST['process_kind'] === 'delete_product')){
            delete_products_item($dbh, $target_product_id, $access_datetime);
            
            //---------- 制作リストへ ----------//
            header('location: product_list.php');
            exit;
            //----------------------------------//  
          }
        
        } catch (PDOException $e) {
            // 例外をスロー
            throw $e;
        }
        

      }
    }
  }

} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}


/*=====================================================*/
// 作品詳細画面テンプレートファイル読み込み
include_once './view/V_product_details.php';