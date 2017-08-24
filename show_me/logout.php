<?php
/*------------------------------------------
ログアウト（処理のみ）
------------------------------------------*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';

setcookie('last_page', 'logout.php');

logout();

//---------- ログインページへ ----------//
header('location: login.php');
exit;
//----------------------------------//

?>