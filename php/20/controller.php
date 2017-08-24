<?php
// 設定ファイル読み込み
require_once './conf/setting.php';
// 関数ファイル読み込み
require_once './model/model.php';

$comments_data = [];
$err_msg = []; //DBエラー用
$post_error = []; //入力エラーチェック用


//------------------------------------------------------------
// フォームから送られた値があればエラーチェック
//------------------------------------------------------------
if (isset($_POST) === TRUE) {
  $post_error = post_errorcheck();
}


//------------------------------------------------------------
// DBに接続
//------------------------------------------------------------
try {
  // DB接続
  $dbh = get_db_connect();
  
  //------------------------------------------------------------
  // POSTかつエラーがなければ($post_error配列が空であれば)INSERT処理
  //------------------------------------------------------------
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($post_error) === TRUE) {
            
      $name = $_POST['name'];
      $comment = $_POST['comment'];
      $date = date('Y-m-d H:i:s');
      
      //DBに登録
      insert_comment_table_list($dbh, $name, $comment, $date);
      
      //---------- リロード対策 ----------//
      header('location: https://codeincubate-tanakanoboru.c9users.io/php/20/controller.php');
      exit;
      //----------------------------------//
    }
  }


  //------------------------------------------------------------
  // コメント一覧を取得
  //------------------------------------------------------------

  // コメント一覧を取得
  $comments_data = get_comments_table_list($dbh);
  
  // 特殊文字をHTMLエンティティに変換
  $comments_data = entity_assoc_array($comments_data);
  
    
} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}

//-----------------------------------------------------
// ひとこと掲示板テンプレートファイル読み込み
include_once './view/view.php';

?>