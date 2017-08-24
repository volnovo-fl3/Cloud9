<?php
date_default_timezone_set('Asia/Tokyo');

/* ---------- データベースの接続情報 ----------*/
// MySQLのユーザ名
define('DB_USER', 'tanakanoboru');

// MySQLのパスワード
define('DB_PASSWD', '');

// データベースのDSN情報
define('DSN', 'mysql:dbname=camp;host=localhost;charset=utf8');
/* --------------------------------------------*/

// HTML文字エンコーディング
define('HTML_CHARACTER_SET', 'UTF-8');

// 画像ファイルのアップロード先
define('IMAGE_DIRECTORY', './img/');

// 管理画面メッセージ用のテキストファイル
define('TOOL_MESSAGE', './tool_message.txt');



/* ========== 各種フォームたち ========== */

// ----- index ----- //
define('INDEX_MONEY', 'index_money');
define('INDEX_GET_ID', 'get_drinkid');


// ----- result -----//


// ----- tool -----//
// 新規登録フォーム
define('TOOL_INS_NAME', 'ins_drinkname');
define('TOOL_INS_PRICE', 'ins_drinkprice');
define('TOOL_INS_STOCK', 'ins_drinkstock');
define('TOOL_INS_IMG', 'ins_drinkimage');
define('TOOL_INS_STATUS', 'ins_drinkstatus');

// 更新フォーム
define('TOOL_GET_ID', 'get_drinkid');
define('TOOL_GET_NAME', 'get_drinkname');
define('TOOL_UPD_STOCK', 'upd_drinkstock');
define('TOOL_UPD_STATUS', 'upd_drinkstatus');


?>