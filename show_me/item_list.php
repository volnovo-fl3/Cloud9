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
$target_user_id = '';
$categories_master = [];
$skills_master = [];
$carts_unpaid = [];

$err_msg = [];
$items_list = [];
$search_where = '';
$search_array = '';
$search_word = '';

$checked_categories = [];
$checked_skills = [];

$header_user_name = '';
$header_user_img = '';

$recommended_items = [];
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
  $target_user_id = $user_id;
}

else {
//------------------------------------------------------------
// 残ってなければ、ログイン画面へ
//------------------------------------------------------------
  header('location: login.php');
  exit;
}


if((isset($_GET) === TRUE) && (count($_GET) > 0)){
  //------------------------------------------------------------
  // ページタイプを取得
  // seller_list であれば $page_type を変更
  //------------------------------------------------------------
  if((isset($_GET['page_type']) === TRUE) && (mb_strlen($_GET['page_type']) > 0)){
    if($_GET['page_type'] === 'seller_list'){
      $page_name = '出品一覧';
      $page_type = $_GET['page_type'];
      if(isset($_GET['target_user_id'])){
        $target_user_id = $_GET['target_user_id'];
      }
    }
  }
  
  //------------------------------------------------------------
  // 検索条件を取得
  //------------------------------------------------------------
  if ((isset($_GET['checked_categories'])) and (count($_GET['checked_categories']) > 0)){
    $checked_categories = $_GET['checked_categories'];
  }
  if ((isset($_GET['checked_skills'])) and (count($_GET['checked_skills']) > 0)){
    $checked_skills = $_GET['checked_skills'];
  }
  if ((isset($_GET['search_word'])) and (mb_strlen($_GET['search_word']) > 0)){
    $search_word = $_GET['search_word'];
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
      
      if ($page_type === 'all_list') {
        
        // ユーザー情報、カテゴリ・使用ソフトマスタを取得
        $user = get_user_by_id($dbh, $user_id);
        $categories_master = get_categories_table_list($dbh);
        $skills_master = get_skills_table_list($dbh);
        $carts_unpaid = get_carts_unpaid_sum($dbh, $user_id);
        $recommended_items = get_autocomplete($dbh);
        
        // 検索条件のwhere文を作成し、検索
        $search_where = 'where i.deleted_datetime is null';
        
        
        //------------------------------------------
        // 画面上で指定した検索条件をwhere文に追加

        if(count($checked_categories) > 0) {
          $search_array = $search_array . '(' . array_to_str_where('i.categories', $checked_categories) .')';
        }
        if(count($checked_skills) > 0) {
          if (mb_strlen($search_array) > 0) {
            $search_array = $search_array . ' or ';
          }
          $search_array = $search_array . '(' . array_to_str_where('i.skills', $checked_skills) .')';
        }
        if (mb_strlen($search_array) > 0){
          $search_where = $search_where . ' and (' . $search_array . ')';
        }
        
        if (mb_strlen($search_word) > 0){
          $search_where = $search_where . ' and i.item_name like \'%'. $search_word .'%\'';
          $search_where = $search_where . ' or i.item_introduction like \'%'. $search_word .'%\'';
          $search_where = $search_where . ' or i.item_introduction_detail like \'%'. $search_word .'%\'';
          $search_where = $search_where . ' or u.user_name like \'%'. $search_word .'%\'';
          $search_where = $search_where . ' or u.user_affiliation like \'%'. $search_word .'%\'';
          
          // 各マスタと重複するワードでなければ検索履歴を取得する
          mnt_serach_history_table($dbh, $search_word, $access_datetime);
        }
        
        //------------------------------------------
        
        $items_list = get_items_table_list($dbh, $search_where);
      }
      else if ($page_type === 'seller_list') {
        
        $user = get_user_by_id($dbh, $target_user_id);
        $page_name = $user[0]['user_name'] . ' さんの' . $page_name;

        // 検索条件のwhere文を作成し、検索
        $search_where = 'where i.deleted_datetime is null and i.seller_user_id = ' . $target_user_id;
        if($target_user_id !== $user_id){
          $search_where = $search_where . ' and i.item_status <> 0';
        }
        $search_where = $search_where . ' order by i.updated_datetime desc limit 3';
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