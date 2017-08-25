<?php
/*------------------------------------------
商品詳細（表示のみ）
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
$target_item_id = '';
$str_where = ''; //データベース検索条件
$look_item = [];
$categories_name_list = [];
$skills_name_list = [];

$page_title = '商品詳細';
$look_item_img = 'no';

$err_to_cart = []; //カート用のエラー
$item_to_carts_amount = 0;
$item_to_carts_stock = 0;
//----------------------//


//------------------------------------------------------------
// Cookieに商品IDが残っていれば取得、残っていなければログイン画面へ
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
  
  // 商品IDを取得
  if ((isset($_POST['target_item_id'])) && ($_POST['target_item_id'] > 0)) {
    $target_item_id = $_POST['target_item_id'];
  } else {
    $err_msg[] = 'サーバーから商品情報を取得できませんでした。';
  }
  
  
  //------- カートに入れるとき -------//
  if($_POST['process_kind'] === 'item_to_carts')
  {
    if(
        ((isset($_POST['target_item_id'])) && ($_POST['target_item_id'] > 0))
        && ((isset($_POST['item_to_carts_amount'])) && ($_POST['item_to_carts_amount'] > 0))
      )
    {
      $target_item_id = $_POST['target_item_id'];
      $item_to_carts_amount = $_POST['item_to_carts_amount'];
      $item_to_carts_stock = $_POST['item_to_carts_stock'];
      
      //在庫チェック
      if ($item_to_carts_amount > $item_to_carts_stock) {
        $err_to_cart[] = '現在の在庫（' . $item_to_carts_stock . '個）よりたくさん買うことはできません。';
      }
    }
    
    else {
      $err_msg[] = 'サーバーから商品情報を取得できませんでした。';
    }
  }
  
} else {
  $err_msg[] = '情報がありません。';
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
          
          $str_where = 'where item_id = ' . $target_item_id;
          $look_item = get_items_table_list($dbh, $str_where);
          
          // 取得できている時
          if(isset($look_item) === TRUE && count($look_item) > 0) {
            
            $page_title = $look_item[0]['item_name'];
            
            // 削除された商品かどうか
            if (isset($look_item[0]['deleted_datetime']) === TRUE) {
              $err_msg[] = 'この商品は削除されています。';
            } else {
              // 画像ファイルを判定
              $look_item_img = image_link($look_item[0]['item_img']);
              
              // カテゴリ・使用ソフトを名前の配列に変換して取得
              $categories_name_list = id_to_name($categories_mastar, $look_item[0]['categories'], 'category_id', 'category_name');
              $skills_name_list = id_to_name($skills_mastar, $look_item[0]['skills'], 'skill_id', 'skill_name');
            }
            
          // 取得できていない時
          } else {
            $err_msg[] = 'データベースから商品情報を取得できませんでした。';
          }
        } catch (PDOException $e) {
            // 例外をスロー
            throw $e;
        }
        
        
        //------- カートに入れる -------//
        if($_POST['process_kind'] === 'item_to_carts')
        {
          // (カート用の)エラーが無ければ
          if (isset($err_to_cart) === FALSE || count($err_to_cart) === 0)  {
          
            // カートに入れるという作業
            try {
              insert_carts_table_list
                ($dbh, $my_user_id, $target_item_id, $item_to_carts_amount, $access_datetime);
                
            //---------- 商品一覧ページへ ----------//
            header('location: item_list.php');
            exit;
            //----------------------------------//  
              
            } catch (PDOException $e) {
              $err_msg[] = 'カートの登録に失敗しました。';
              // 例外をスロー
              throw $e;
            }
          }
        }
        
      }
    
    }
  }

} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}


/*=====================================================*/
// ログイン画面テンプレートファイル読み込み
include_once './view/V_item_details.php';