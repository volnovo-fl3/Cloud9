<?php
/*------------------------------------------
ログイン
------------------------------------------*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';

//----- 変数初期化 -----//
$err_msg = [];
$user = [];
$is_logout = '';
$user_name = '';
$password = '';
//----------------------//


//------------------------------------------------------------
// 自分でログアウトした(logout.php から移動した)場合は、メッセージを表示
//------------------------------------------------------------

var_dump($_SERVER['HTTP_REFERER']);
var_dump($is_logout);

if(isset($_SERVER['HTTP_REFERER']) === TRUE){
  if($_SERVER['HTTP_REFERER'] === 'https://codeincubate-tanakanoboru.c9users.io/show_me/logout.php') {
    $is_logout = 'ログアウトしました';
  }
}


//------------------------------------------------------------
// ログインボタンを押した時の処理
//------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  //--- 変数に入力値を代入 ---//
  
  if(isset($_POST['user_name']) === TRUE) {
    $user_name = $_POST['user_name'];
  }
  if(isset($_POST['password']) === TRUE) {
    $password = $_POST['password'];
  }

  //--- 入力フォームエラーチェック ---//
  
  if(check_null($user_name) === FALSE) {
    $err_msg[] = ('ユーザー名を入力してください');
  } else if(check_str_count($user_name, 100) === FALSE) {
    $err_msg[] = 'ユーザー名は100文字以内で入力してください';
  }
  
  if(check_null($password) === FALSE) {
    $err_msg[] = 'パスワードを入力してください';
  } else if(check_str_count($password, 40) === FALSE) {
    $err_msg[] = 'パスワードは40文字以内で入力してください';
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      
      var_dump(isset($err_msg));
      var_dump($err_msg);
      
      // エラーが無ければ
      if (isset($err_msg) === FALSE || count($err_msg) === 0)  {
        
        // ユーザーIDを取得
        try {
          
          $user = get_user_for_login($dbh, $user_name, $password);
          
          // IDを取得できていれば、クッキーにユーザーIDを保存
          if(isset($user) === TRUE && count($user) > 0) {
            if ($user[0]['user_id'] > 0) {
              
              setcookie('user_id', $user[0]['user_id']);
              
              //---------- マイページへ ----------//
              header('location: mypage.php');
              exit;
              //----------------------------------//
              
            } else {
              $err_msg[] = 'ユーザー情報を取得できませんでした。管理者にお問い合わせください。';
            }
            
          } else {
            $err_msg[] = 'ユーザー情報を取得できませんでした。ユーザー名・パスワードをご確認ください。';
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
include_once './view/V_login.php';

?>