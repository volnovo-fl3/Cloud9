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
$logout_message = '';
$user_name = '';
$password = '';
$iink_page = ''; //ログインが切れる前にクッキーに保存されていたページ

$header_user_name = '';
$header_user_img = '';
//----------------------//


//------------------------------------------------------------
// CookieにユーザーIDが残っていれば、ID表示
//------------------------------------------------------------
if ((isset($_COOKIE['user_id']) === TRUE) && ($_COOKIE['user_id'] > 0)){
  $header_user_name = entity_str($_COOKIE['user_name']);
  $header_user_img = entity_str($_COOKIE['user_img']);
}

else {
//------------------------------------------------------------
// 残ってなければ、再ログインのメッセージを表示
//------------------------------------------------------------

  if(isset($_COOKIE['last_page']) === TRUE){
    
    // 自分でログアウトした時
    if($_COOKIE['last_page'] === 'logout.php') {
      $logout_message = 'ログアウトしました';
    }
    
    // その他
    else {
      $iink_page = $_COOKIE['last_page'];
      $logout_message = 'ログイン情報が失われました。再度ログインしてください。';
    }
  }
}


// Cookieにページ遷移履歴を保存
setcookie('last_page', 'login.php');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //------------------------------------------------------------
  // ログインボタンを押した時の処理
  //------------------------------------------------------------
  if ($_POST['process_kind'] === 'login') {
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
  // ユーザー新規登録ボタンを押した時の処理
  //------------------------------------------------------------
  else if ($_POST['process_kind'] === 'to_user_insert') {
    
    //---------- 新規登録ページへ ----------//
    header('location: user_info.php');
    exit;
    //----------------------------------//
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

    // POST かつ ログインボタンを押していて
    if (($_SERVER['REQUEST_METHOD'] === 'POST') && ($_POST['process_kind'] === 'login')){
      
      // エラーが無ければ
      if (isset($err_msg) === FALSE || count($err_msg) === 0)  {
        
        // ユーザーIDを取得
        try {
          
          $user = get_user_for_login($dbh, $password, $user_name);

          // IDを取得できていれば、クッキーにユーザーIDを保存
          if(isset($user) === TRUE && count($user) > 0) {
            if ($user[0]['user_id'] > 0) {
              
              setcookie('user_id', $user[0]['user_id']);
              setcookie('user_name', $user[0]['user_name']);
              setcookie('user_img', image_link($user[0]['user_img']));
              
              //---------- マイページへ ----------//
              header('location: mypage.php');
              exit;
              //----------------------------------//
              
              /*
              //---------- マイページへ(クッキーに保存されているページがあればそちらへ) ※不完全 ----------//
              if ((isset($iink_page) === TRUE) && (mb_strlen($iink_page, HTML_CHARACTER_SET))) {
                header('location: ' . $iink_page);
                exit;
              }
              else {
                header('location: mypage.php');
                exit;
              }
              //----------------------------------//
              */
              
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