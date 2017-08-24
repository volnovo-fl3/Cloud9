<?php
/*------------------------------------------
マイページ
------------------------------------------*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';


//----- 変数初期化 -----//
$user_id = '';
//----------------------//

// Cookieにページ遷移履歴を保存
setcookie('last_page', 'mypage.php');

// ユーザーIDを取得
if (isset($_COOKIE['user_id']) === TRUE) {
  $user_id = $_COOKIE['user_id'];
}
// 取得できなければ、ログイン画面へ
else {
  //---------- ログインページへ ----------//
  header('location: login.php');
  exit;
  //----------------------------------//
}


/*=====================================================*/
// マイページテンプレートファイル読み込み
include_once './view/V_mypage.php';

?>