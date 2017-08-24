<?php
/*------------------------------------------
ユーザー情報（表示のみ）
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
$target_user_id = '';
$look_user = [];
$categories_name_list = [];
$skills_name_list = [];

$page_title = 'ユーザー情報';
$look_user_img = 'no';
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
// $_POSTから閲覧対象のユーザーIDを取得
//------------------------------------------------------------
if((isset($_POST)) && (count($_POST) > 0)) {
  if ((isset($_POST['target_user_id'])) && ($_POST['target_user_id'] > 0)) {
    $target_user_id = $_POST['target_user_id'];
  } else {
    $err_msg[] = 'サーバーからユーザー情報を取得できませんでした。';
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
        
        // ユーザー情報を取得
        try {
          
          $look_user = get_user_by_id($dbh, $target_user_id);
          
          // 取得できている時
          if(isset($look_user) === TRUE && count($look_user) > 0) {
            
            $page_title = $page_title . '(' . $look_user[0]['user_name'] . ' さん)';
            
            // 削除されたユーザーかどうか
            if (isset($look_user[0]['deleted_datetime']) === TRUE) {
              $err_msg[] = 'このユーザーは削除されています。';
            } else {
              // 画像ファイルを判定
              $look_user_img = image_link($look_user[0]['user_img']);
              
              // カテゴリ・使用ソフトを名前の配列に変換して取得
              $categories_name_list = id_to_name($categories_mastar, $look_user[0]['categories'], 'category_id', 'category_name');
              $skills_name_list = id_to_name($skills_mastar, $look_user[0]['skills'], 'skill_id', 'skill_name');
            }
            
          // 取得できていない時
          } else {
            $err_msg[] = 'データベースからユーザー情報を取得できませんでした。';
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
// ログイン画面テンプレートファイル読み込み
include_once './view/V_user_profile.php';