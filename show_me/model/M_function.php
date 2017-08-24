<?php

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
  
  // 空の配列であればスキップ
  if (count($assoc_array) > 0) {
    foreach ($assoc_array as $key => $value) {
      foreach ($value as $keys => $values) {
        // 特殊文字をHTMLエンティティに変換
        $assoc_array[$key][$keys] = entity_str($values);
      }
    }
  }

  return $assoc_array;
}


//------------------------------------------------------------
// データベース操作系
// (汎用)
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
  
  if (isset($rows) === TRUE) {
    return $rows;
  }
}

/**
* カテゴリ一覧を取得する
*
* @param obj $dbh DBハンドル
* @return array カテゴリ一覧配列データ
*/
function get_categories_table_list($dbh) {

  //SQL文を作成
  $sql = '
    select
      category_id
      ,category_name
      ,category_color
    from
      categories_mastar
    ;';
    
  // クエリ実行
  return get_as_array($dbh, $sql);
}
/**
* スキル(使用ソフト)一覧を取得する
*
* @param obj $dbh DBハンドル
* @return array スキル(使用ソフト)一覧配列データ
*/
function get_skills_table_list($dbh) {

  //SQL文を作成
  $sql = '
    select
      skill_id
      ,skill_name
    from
      skills_mastar
    ;';
    
  // クエリ実行
  return get_as_array($dbh, $sql);
}


//------------------------------------------------------------
// データベース操作系
// (ユーザー)
//------------------------------------------------------------
/**
* ユーザーを新規登録する
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function insert_user_table_list
  ($dbh, $password, $user_name, $user_affiliation, $user_self_introduction, $user_img, $categories, $skills, $access_datetime) {
  
  $new_drinkid = 0;

  //SQL文を作成
  $sql = '
    insert into users
    (
      password
      ,user_name
      ,user_affiliation
      ,user_self_introduction
      ,user_img
      ,created_datetime
      ,updated_datetime
      ,last_login_datetime
      ,categories
      ,skills
    )
    values
    (
      ?
      ,?
      ,?
      ,?
      ,?
      ,?
      ,?
      ,?
      ,?
      ,?
    )
    ;';
    
  //SQL実行準備
  $stmt = $dbh->prepare($sql);
  
  //値をバインド
  $stmt->bindValue(1, $password, PDO::PARAM_STR);
  $stmt->bindValue(2, $user_name, PDO::PARAM_STR);
  $stmt->bindValue(3, $user_affiliation, PDO::PARAM_STR);
  $stmt->bindValue(4, $user_self_introduction, PDO::PARAM_STR);
  $stmt->bindValue(5, $user_img, PDO::PARAM_STR);
  $stmt->bindValue(6, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(7, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(8, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(9, $categories, PDO::PARAM_STR);
  $stmt->bindValue(10, $skills, PDO::PARAM_STR);

  //SQLを実行
  $stmt->execute();
  
  //追加したレコードののIDを取得
  $new_user_id = $dbh->lastInsertId();
  
  return $new_user_id;
    
}

/**
* ユーザー情報を更新する
* （画像以外の基本情報）
*
* @param obj $dbh DBハンドル
*/
function update_user_infomation
  ($dbh, $user_id, $password, $user_name, $user_affiliation, $user_self_introduction, $categories, $skills, $access_datetime) {
  
  //SQL文を作成
  $sql = '
    update users
    set
      password = ?
      ,user_name = ?
      ,user_affiliation = ?
      ,user_self_introduction = ?
      ,updated_datetime = ?
      ,last_login_datetime = ?
      ,categories = ?
      ,skills = ?
    where
      user_id = ?
    ;';
    
  //SQL実行準備
  $stmt = $dbh->prepare($sql);
  
  //値をバインド
  $stmt->bindValue(1, $password, PDO::PARAM_STR);
  $stmt->bindValue(2, $user_name, PDO::PARAM_STR);
  $stmt->bindValue(3, $user_affiliation, PDO::PARAM_STR);
  $stmt->bindValue(4, $user_self_introduction, PDO::PARAM_STR);
  $stmt->bindValue(5, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(6, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(7, $categories, PDO::PARAM_STR);
  $stmt->bindValue(8, $skills, PDO::PARAM_STR);
  $stmt->bindValue(9, $user_id, PDO::PARAM_INT);

  //SQLを実行
  $stmt->execute();
}
/**
* ユーザー情報を更新する
* （画像のみ）
*
* @param obj $dbh DBハンドル
*/
function update_user_image
  ($dbh, $user_id, $user_img, $access_datetime) {
  
  //SQL文を作成
  $sql = '
    update users
    set
      user_img = ?
      ,updated_datetime = ?
      ,last_login_datetime = ?
    where
      user_id = ?
    ;';
    
  //SQL実行準備
  $stmt = $dbh->prepare($sql);
  
  //値をバインド
  $stmt->bindValue(1, $user_img, PDO::PARAM_STR);
  $stmt->bindValue(2, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(3, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(4, $user_id, PDO::PARAM_INT);

  //SQLを実行
  $stmt->execute();
}
/**
* ユーザー情報を更新する
* （最終ログイン日時のみ）
*
* @param obj $dbh DBハンドル
*/
function update_user_last_login_datetime
  ($dbh, $user_id, $access_datetime) {
  
  //SQL文を作成
  $sql = '
    update users
    set
      last_login_datetime = ?
    where
      user_id = ?
    ;';
    
  //SQL実行準備
  $stmt = $dbh->prepare($sql);
  
  //値をバインド
  $stmt->bindValue(1, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(2, $user_id, PDO::PARAM_INT);

  //SQLを実行
  $stmt->execute();
}


/**
* 【ログイン】
* ユーザー名とパスワードから、ユーザーIDを取得する
*
* @param obj $dbh DBハンドル
* @param str $password パスワード
* @param str $user_name ユーザー名
* @return  ユーザー情報
*/
function get_user_for_login($dbh, $password, $user_name) {

  //SQL文を作成
  $sql =
    "select user_id from users where password = '$password' and user_name = '$user_name';";
  var_dump($sql);
    

  // クエリ実行
  return get_as_array($dbh, $sql);
    
}
/**
* 【ログイン】
* ユーザーIDからユーザーを特定し、最終ログイン日時を更新
*
* @param obj $dbh DBハンドル
* @param str $user_id ユーザーID
* @return  ユーザー情報
*/
function update_last_login_datetime($dbh, $user_id, $access_datetime) {

  //SQL文を作成
  $sql = 'update users set last_login_datetime = ? where user_id = ?;';

  //SQL実行準備
  $stmt = $dbh->prepare($sql);
  
  //値をバインド
  $stmt->bindValue(1, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(2, $user_id, PDO::PARAM_STR);

  //SQLを実行
  $stmt->execute();
}

/**
* ユーザーIDからユーザー情報を取得
*
* @param obj $dbh DBハンドル
* @param str $user_id ユーザーID
* @return  ユーザー情報
*/
function get_user_by_id($dbh, $user_id) {

  //SQL文を作成
  $sql = "
    select
      user_id
      ,password
      ,user_name
      ,user_affiliation
      ,user_self_introduction
      ,user_img
      ,is_admin
      ,created_datetime
      ,updated_datetime
      ,last_login_datetime
      ,deleted_datetime
      ,categories
      ,skills
    from
      users
    where
      user_id = $user_id
    ;";

  // クエリ実行
  return get_as_array($dbh, $sql);
    
}


//------------------------------------------------------------
// データベース操作系
// (商品)
//------------------------------------------------------------
/**
* 商品一覧を取得する
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function get_drinks_table_list($dbh) {

  //SQL文を作成
  $sql = '
    select
      dm.drink_id as drink_id
      ,drink_name
      ,price
      ,img
      ,status
      ,stock
      ,dm.update_datetime as master_update
      ,ds.update_datetime as stock_update
    from
      drink_master as dm
      inner join drink_stock as ds on dm.drink_id = ds.drink_id
    ;';
    
  // クエリ実行
  return get_as_array($dbh, $sql);
    
}

/**
* 商品を単品で取得する（id指定）
*
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function get_drink_by_id($dbh, $drink_id) {

  //SQL文を作成
  $sql = '
    select
      dm.drink_id as drink_id
      ,drink_name
      ,price
      ,img
      ,status
      ,stock
      ,dm.update_datetime as master_update
      ,ds.update_datetime as stock_update
    from
      drink_master as dm
      inner join drink_stock as ds on dm.drink_id = ds.drink_id
    where
      dm.drink_id = ' . $drink_id . ';';
    
  // クエリ実行
  return get_as_array($dbh, $sql);
    
}

/**
* 商品一覧に新規登録
* 新規登録できた場合は、drink_idを返す
*
* @param obj $dbh DBハンドル
* @param str $new_img_filename アップロードした画像のパス
* @param str $access_datetime 登録・更新日時
* @return int $new_drinkid 新しく登録した商品のID (0であれば登録失敗している可能性大！)
*/
function insert_drink_table_list($dbh, $new_img_filename, $access_datetime) {
  
  $new_drinkid = 0;

  //SQL文を作成
  $sql = '
    insert into drink_master
    (
      drink_name
      ,price
      ,img
      ,status
      ,create_datetime
      ,update_datetime
    )
    values
    (
      ?
      ,?
      ,?
      ,?
      ,?
      ,?
    )
    ;';
    
  //SQL実行準備
  $stmt = $dbh->prepare($sql);
  
  //値をバインド
  $stmt->bindValue(1, $_POST[TOOL_INS_NAME], PDO::PARAM_STR);
  $stmt->bindValue(2, $_POST[TOOL_INS_PRICE], PDO::PARAM_INT);
  $stmt->bindValue(3, $new_img_filename, PDO::PARAM_STR);
  $stmt->bindValue(4, $_POST[TOOL_INS_STATUS], PDO::PARAM_STR);
  $stmt->bindValue(5, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(6, $access_datetime, PDO::PARAM_STR);
  
  //SQLを実行
  $stmt->execute();
  
  //追加したレコードののIDを取得
  $new_drinkid = $dbh->lastInsertId();
  
  return $new_drinkid;
    
}

/**
* 商品ごとの個数を新規登録
*
* @param obj $dbh DBハンドル
* @param int $drink_id 商品のID
* @param str $access_datetime 登録・更新日時
*/
function insert_drinkstock_table_list($dbh, $drink_id, $access_datetime) {
  
  //SQL文を作成
  $sql = '
    insert into drink_stock
    (
      drink_id
      ,stock
      ,create_datetime
      ,update_datetime
    )
    values
    (
      ?
      ,?
      ,?
      ,?
    )
    ;';
    
  //SQL実行準備
  $stmt = $dbh->prepare($sql);
  
  //値をバインド
  $stmt->bindValue(1, $drink_id, PDO::PARAM_INT);
  $stmt->bindValue(2, $_POST[TOOL_INS_STOCK], PDO::PARAM_INT);
  $stmt->bindValue(3, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(4, $access_datetime, PDO::PARAM_STR);
  
  //SQLを実行
  $stmt->execute();
  
}


/**
* 商品ごとの個数を変更
*
* @param obj $dbh DBハンドル
* @param str $access_datetime 登録・更新日時
*/
function update_drinkstock($dbh, $stock, $access_datetime, $drink_id) {
  
  //SQL文を作成
  $sql = '
    update drink_stock
    set
      stock = ?
      ,update_datetime = ?
    where
      drink_id = ?
    ;';
    
  //SQL実行準備
  $stmt = $dbh->prepare($sql);
  
  //値をバインド
  $stmt->bindValue(1, $stock, PDO::PARAM_INT);
  $stmt->bindValue(2, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(3, $drink_id, PDO::PARAM_INT);

  //SQLを実行
  $stmt->execute();

}

/**
* 商品ごとの公開ステータスを変更
*
* @param obj $dbh DBハンドル
* @param str $access_datetime 登録・更新日時
*/
function update_drinkstatus($dbh, $access_datetime) {
  
  //SQL文を作成
  $sql = '
    update drink_master
    set
      status = ?
      ,update_datetime = ?
    where
      drink_id = ?
    ;';
    
  //SQL実行準備
  $stmt = $dbh->prepare($sql);
  
  //値をバインド
  $stmt->bindValue(1, $_POST[TOOL_UPD_STATUS], PDO::PARAM_INT);
  $stmt->bindValue(2, $access_datetime, PDO::PARAM_STR);
  $stmt->bindValue(3, $_POST[TOOL_GET_ID], PDO::PARAM_INT);

  //SQLを実行
  $stmt->execute();

}

/**
* 購入履歴を新規登録
*
* @param obj $dbh DBハンドル
* @param int $drink_id 商品のID
* @param str $access_datetime 登録・更新日時
*/
function insert_drink_history($dbh, $drink_id, $access_datetime) {
  
  //SQL文を作成
  $sql = '
    insert into drink_history
    (
      drink_id
      ,create_datetime
    )
    values
    (
      ?
      ,?
    )
    ;';
    
  //SQL実行準備
  $stmt = $dbh->prepare($sql);
  
  //値をバインド
  $stmt->bindValue(1, $drink_id, PDO::PARAM_INT);
  $stmt->bindValue(2, $access_datetime, PDO::PARAM_STR);

  //SQLを実行
  $stmt->execute();
  
}


//------------------------------------------------------------
// ログアウト処理
//------------------------------------------------------------
/**
* 配列の要素をコンマ区切りの文字列に変換
*
*/
function logout() {
  // Cookieを削除する
  setcookie('user_id', '');
}


//------------------------------------------------------------
// 文字列生成
//------------------------------------------------------------
/**
* 【カテゴリ・使用ソフト】
* コンマ区切りのID(文字列)を
* マスタと照らし合わせて名前(配列)で返す
*
* @param str $str_selected コンマ区切りのID
* @param array $array_master 照らし合わせるマスタ
* @param str $column_id idの列名
* @param str $column_name 名前の列名
* @return array $array_result 配列化された名前
*/
function id_to_name($array_master, $str_selected, $column_id, $column_name)
{
  $array_selected = [];
  $array_result = [];
  
  // $str_selected が無ければスルー
  if((isset($str_selected) === TRUE) && (mb_strlen($str_selected, HTML_CHARACTER_SET) > 0)) {
    
    // 選択されたIDを配列化
    $array_selected = explode(',', $str_selected);
    
    // マスタの項目数くりかえし
    foreach($array_master as $key => $master_item){
      
      // チェックの数だけ繰り返し
      foreach($array_selected as $key => $id){
        
        if($master_item[$column_id] == $id) {
          $array_result[] = $master_item[$column_name];
        }
      }
    }
  }
  
  return $array_result;
}



//------------------------------------------------------------
// エラーチェック（値）
//------------------------------------------------------------
/**
* 値が入力されているかどうかのチェック
*
* @param str $chk_value 送られてきた値
* @return bool TRUE:エラーなし FALSEエラーあり
*/
function check_null($chk_value)
{
  if (isset($chk_value) !== TRUE || mb_strlen($chk_value, HTML_CHARACTER_SET) === 0){
    return FALSE;
  } else {
    return TRUE;
  }
}

/**
* 値の文字数チェック（値がnullではないことが前提）
*
* @param str $chk_value 送られてきた値の名前
* @param int $max_str_count 送られてきた値文字数
* @return bool TRUE:エラーなし FALSEエラーあり
*/
function check_str_count($chk_value, $max_str_count)
{
  if (mb_strlen($chk_value, HTML_CHARACTER_SET) > $max_str_count){
    return FALSE;
  } else {
    return TRUE;
  }
}

/**
* 値の負数チェック（値がnullではないことが前提）
*
* @param int $chk_value 送られてきた値の名前
* @return bool TRUE:エラーなし FALSEエラーあり
*/
function check_int_minus($chk_value)
{
  if ($chk_value < 0){
    return FALSE;
  } else {
    return TRUE;
  }
}

/**
* 公開ステータスのチェック（0か1のみ許容）
*
* @param str $chk_value 送られてきた値
* @return bool TRUE:エラーなし FALSEエラーあり
*/
function check_int_status($chk_value)
{
  if (($chk_value === '0') || ($chk_value === '1')){
    return TRUE;
  } else {
    return FALSE;
  }
}


//------------------------------------------------------------
// エラーチェック（画像UP）
//------------------------------------------------------------
/**
* 画像アップロード
* HTTP POST でファイルがアップロードされたかどうかチェック
* 
* @return bool TRUE:エラーなし FALSEエラーあり
*/
function check_uploadpicture_select($image_name){
  if (is_uploaded_file($_FILES[$image_name]['tmp_name']) === TRUE) {
    return TRUE;
  } else {
    return FALSE;
  }
}

/**
* 画像アップロード
* 指定の拡張子であるかどうかチェック
* 
* @param str $extension 拡張子
* @return bool TRUE:エラーなし FALSEエラーあり
*/
function check_uploadpicture_extension($extension){
  if (
    $extension === 'jpg'
    || $extension === 'jpeg'
    || $extension === 'png'
    || $extension === 'gif'
    )
    {
    return TRUE;
  } else {
    return FALSE;
  }
}

/**
* 画像アップロード
* 同名ファイルが存在するかどうかチェック
* 
* @param str $new_img_filename DBに登録するユニークなファイル名
* @return bool TRUE:重複しない FALSE:重複する
*/
function check_uploadpicture_filename($new_img_filename){
  if (is_file(IMAGE_DIRECTORY . $new_img_filename) !== TRUE) {
    return TRUE;
  } else {
    return FALSE;
  }
}

//------------------------------------------------------------
// 画像呼び出し（なければ NoImage.png を返す）
//------------------------------------------------------------
/**
* 登録画像の有無チェック
* 
* @param str $chk_img_name 呼び出そうとしている画像の名前
* @return str $image_name 結果の画像
* 
*/
function image_link($chk_img_name) {
  
  $image_name = '';
  
  // 画像ファイルがあるかどうか(FALSE = ある)
  if (check_uploadpicture_filename($chk_img_name) === FALSE) {
    $image_name = $chk_img_name;

  } else {
    $image_name = NO_IMAGE;
  }
  
  return $image_name;
}

//============================================================
// テキストファイル操作
//------------------------------------------------------------
//リロード対策で
//
//  header('location: URL');
//  exit;
//
//を使用していると、新規登録完了などのメッセージを設定しても
//処理完了と同時に初期化されてしまうため
//さらにその対策としてメッセージ専門のファイルを作ってみました。
//============================================================
/**
* テキストファイルを読み込み
* 
* @param str $filename テキストファイル名
* @return array $data テキストファイルの中身
*/
function read_text($filename){
  
  if (($fp = fopen($filename, 'r')) !== FALSE) {
    while (($tmp = fgets($fp)) !== FALSE) {
      $data[] = entity_str($tmp);
    }
    fclose($fp);
    
    return $data;
  }
}

/**
* テキストファイルの内容削除
* (一旦読み込んでから使うこと)
* 
* @param str $filename テキストファイル名
* @param array $data (読み込んでおいた)テキストファイルの中身
*/
function delete_text($filename, $data){
  
  // 配列のどこかにデータがあるならば
  if (empty($data) === FALSE) {
    
    // n 回分、先頭行ののデータを削除
    $n = 0;
    while (empty($data[$n]) === FALSE)
    {
      // ↓↓ 参考にしました https://teratail.com/questions/2451 ↓↓ //
      $file = file($filename);
      unset($file[0]);
      file_put_contents($filename, $file);
      
      $n++;
    }
  }
}

/**
* テキストファイルに書き込み
* 
* @param str $filename テキストファイル名
* @param str $str 書き込む内容
*/
function write_text($filename, $str){
  
  if((isset($str) === TRUE) && (mb_strlen($str) > 0)) {
    if (($fp = fopen($filename, 'a')) !== FALSE) {
      if (fwrite($fp, $str."\n") === FALSE) {
        //print 'ファイル書き込み失敗:  ' . $filename;
      }
      fclose($fp);
    }
  }
  
}


?>