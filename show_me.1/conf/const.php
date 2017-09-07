<?php
date_default_timezone_set('Asia/Tokyo');

/* ---------- データベースの接続情報 ----------*/
// MySQLのユーザ名
define('DB_USER', 'tanakanoboru');

// MySQLのパスワード
define('DB_PASSWD', '');

// データベースのDSN情報
define('DSN', 'mysql:dbname=show_me;host=localhost;charset=utf8');
/* --------------------------------------------*/

// サイト名
define('SITE_NAME', 'Show me!');

// HTML文字エンコーディング
define('HTML_CHARACTER_SET', 'UTF-8');

// 画像ファイルのアップロード先
define('IMAGE_DIRECTORY', './img/');

// テンプレート画像ファイル
define('NO_IMAGE', 'NoImage.png');

?>