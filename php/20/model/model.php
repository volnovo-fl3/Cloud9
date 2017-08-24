<?php

//------------------------------------------------------------
// データベース操作系
//------------------------------------------------------------
/**
* DBハンドルを取得
* @return obj $dbh DBハンドル
*/
function get_db_connect() {

  try {
    // データベースに接続
    $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  } catch (PDOException $e) {
    echo 'データベースに接続できませんでした。理由：'.$e->getMessage();
  }

  return $dbh;
}

/**
* クエリを実行しその結果を配列で取得する
*
* @param obj  $dbh DBハンドル
* @param str  $sql SQL文
* @return array 結果配列データ
*/
function get_as_array($dbh, $sql) {

  try {
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    
    // SQLを実行
    $stmt->execute();
    
    // レコードの取得
    $rows = $stmt->fetchAll();
    
  } catch (PDOException $e) {
    echo 'データを取得できませんでした。理由：'.$e->getMessage();
  }

  return $rows;
}

/**
* 投稿の一覧を取得する
*
* @param obj $dbh DBハンドル
* @return array 投稿一覧配列データ
*/
function get_comments_table_list($dbh) {

  //SQL文を作成
  $sql = 'select * from test_bbs';
    
  // クエリ実行
  return get_as_array($dbh, $sql);
    
}

/**
* 投稿データをデータベースに登録する
*
* @param obj $dbh DBハンドル
* @param str $name 投稿者
* @param str $comment ひとこと
* @param datetime $date 投稿日時
*
*/
function insert_comment_table_list($dbh, $name, $comment, $date) {
  
  //----- トランザクション開始！！ -----//
  $dbh->beginTransaction();
  
  try {
    //SQL文を作成
    $sql = '
      insert into test_bbs
      (
        user_name
        , user_comment
        , create_datetime
      )
      values
      (
        ?
        , ?
        , ?
      )';
    
    //SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    
    //SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $name, PDO::PARAM_STR);
    $stmt->bindValue(2, $comment, PDO::PARAM_STR);
    $stmt->bindValue(3, $date, PDO::PARAM_STR);
    
    //SQLを実行
    $stmt->execute();
    
    //----- コミット！！ ------//
    $dbh->commit();
    
  } catch (PDOException $e) {
    //----- ロールバック！！ -----//
    $dbh->rollback();
    echo 'データを登録できませんでした。理由：'.$e->getMessage();
  }
}


//------------------------------------------------------------
// HTMLエスケープ
//------------------------------------------------------------
/**
* 特殊文字をHTMLエンティティに変換する
* @param str  $str 変換前文字
* @return str 変換後文字
*/
function entity_str($str) {

  return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

/**
* 特殊文字をHTMLエンティティに変換する(2次元配列の値)
* @param array  $assoc_array 変換前配列
* @return array 変換後配列
*/
function entity_assoc_array($assoc_array) {

  foreach ($assoc_array as $key => $value) {
    foreach ($value as $keys => $values) {
      // 特殊文字をHTMLエンティティに変換
      $assoc_array[$key][$keys] = entity_str($values);
    }
  }

  return $assoc_array;
}


//------------------------------------------------------------
// エラーチェック
//------------------------------------------------------------
/**
* POST値のエラーチェック
*
* @return array エラーメッセージ
*/
function post_errorcheck() {
  if (isset($_POST) === TRUE) {
    
    $errormessag = [];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
      
      // 1. 名前が空の時
      if (mb_strlen($_POST['name']) === 0) {
        $errormessage[] = '名前を入力してください';
      }
      // 2. 名前が20文字以上の時
      if (mb_strlen($_POST['name']) > 20) {
        $errormessage[] = '名前は20文字以内で入力してください';
      }
      // 3. コメントが空の時
      if (mb_strlen($_POST['comment']) === 0) {
        $errormessage[] = 'コメントを入力してください';
      }
      // 4. コメントが100文字以上の時
      if (mb_strlen($_POST['comment']) > 100) {
        $errormessage[] = 'コメントは100文字以内で入力してください';
      }
      
      if (empty($errormessage) === FALSE) {
        return $errormessage;
      }
    }
  }
}



?>