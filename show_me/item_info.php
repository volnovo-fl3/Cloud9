<?php
/*------------------------------------------
出品登録 / 商品情報更新
------------------------------------------*/
header('Content-Type: text/html; charset=UTF-8');

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';


//----- 変数初期化 -----//
$page_name = '出品登録';
$mode = 0; //0:新規登録 1:更新

// 更新時に使用
$target_item_id = '';
$target_item = []; //更新用
$selected_categories = [];
$selected_skills = [];
$item_img = '';
$str_where = ''; //データベース検索条件

// 共通
$my_user_id = '';
$err_msg = [];
$categories_master = [];
$skills_master = [];
$seller_user_id = '';

$item_name = '';
$price = '0';
$item_introduction = '';
$item_introduction_detail = '';
$item_img = '';
$item_status = '1';
$stock = '0';
$main_category = '1';
$checked_categories = [];
$categories = '';
$skills = '';
$new_img_filename = '';

$header_user_name = '';
$header_user_img = '';
//----------------------//


//------------------------------------------------------------
// Cookieに商品IDが残っていれば取得、残っていなければログイン画面へ
//------------------------------------------------------------
if ((isset($_COOKIE['user_id']) === TRUE) && ($_COOKIE['user_id'] > 0)){
  
  $my_user_id = $_COOKIE['user_id'];
  $header_user_name = $_COOKIE['user_name'];
  $header_user_img = $_COOKIE['user_img'];
  
} else {
  
  //---------- ログインページへ ----------//
  header('location: login.php');
  exit;
  //----------------------------------//  
}

// 商品情報更新ボタンで移動してきたときの初期動作
if((isset($_POST['process_kind']) === TRUE) && ($_POST['process_kind'] === 'to_update_item_info')){
  $mode = 1;
  $target_item_id = $_POST['target_item_id'];
  $page_name = '商品情報更新';
  
}

else
{
  //------------------------------------------------------------
  // 出品登録ボタンを押した時の処理
  //------------------------------------------------------------
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    print '【$_POST】';
    var_dump($_POST);
    print "<br>";
    
    //--- 変数に入力値を代入 ---//
    
    if(isset($_POST['item_name']) === TRUE) {
      $item_name = $_POST['item_name'];
    }
    if(isset($_POST['price']) === TRUE) {
      $price = $_POST['price'];
    }
    if(isset($_POST['item_introduction']) === TRUE) {
      $item_introduction = $_POST['item_introduction'];
    }
    if(isset($_POST['item_introduction_detail']) === TRUE) {
      $item_introduction_detail = $_POST['item_introduction_detail'];
    }
    if(isset($_POST['item_status']) === TRUE) {
      $item_status = $_POST['item_status'];
    }
    if(isset($_POST['stock']) === TRUE) {
      $stock = $_POST['stock'];
    }
    if(isset($_POST['main_category']) === TRUE) {
      $main_category = $_POST['main_category'];
      $checked_categories[] = $main_category;
      
      if(isset($_POST['checked_categories']) === TRUE) {
        $checked_categories = array_unique(array_merge($checked_categories, $_POST['checked_categories']));
      }
      
      asort($checked_categories, SORT_NUMERIC);
      $categories = implode(',', $checked_categories);
    }
    if(isset($_POST['checked_skills']) === TRUE) {
      $skills = implode(',', $_POST['checked_skills']);
    }
    
    print '【$checked_categories】';  
    var_dump($checked_categories);
    
    
    //--- 出品登録エラーチェック ---//
    
    if(check_null($item_name) === FALSE) {
      $err_msg[] = ('商品名を入力してください');
    } else if(check_str_count($item_name, 100) === FALSE) {
      $err_msg[] = '商品名は100文字以内で入力してください';
    }
    
    if(check_null($price) === FALSE) {
      $err_msg[] = '価格を入力してください';
    } else if(check_int_minus($price) === FALSE) {
      $err_msg[] = '¥0以上の価格を設定してください';
    }

    if(check_str_count($item_introduction, 2000) === FALSE) {
      $err_msg[] = '商品説明は2000文字以内で入力してください';
    }
    
    if(check_str_count($item_introduction_detail, 2000) === FALSE) {
      $err_msg[] = '商品説明(詳細)は2000文字以内で入力してください';
    }
    
    if(check_null($stock) === FALSE) {
      $err_msg[] = '在庫を入力してください';
    } else if(check_int_minus($stock) === FALSE) {
      $err_msg[] = '在庫は0個以上で設定してください';
    }
    
    
    //--- 以下、画像アップロードチェック ---//
    // 画像なしならチェックをスルー
    if(check_uploadpicture_select('item_img') === TRUE) {
    
      //拡張子を取得 → 照合
      $extension = pathinfo($_FILES['item_img']['name'], PATHINFO_EXTENSION);
      if(check_uploadpicture_extension($extension) === FALSE) {
        $err_msg[] = 'ファイル形式が異なります（jpg、png、gif が利用可能です）';
      } else {
        
        // 保存する新しいファイル名の生成（ユニークな値を設定する） → 照合
        $new_img_filename = sha1(uniqid(mt_rand(), true)). '.' . $extension;
        if(check_uploadpicture_filename($new_img_filename) === FALSE) {
          $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください';
        } else {
          
          // VARCHAR(100)で引っかかった場合もエラー
          if(mb_strlen($new_img_filename, HTML_CHARACTER_SET) > 100) {
            $err_msg[] = '画像ファイルの文字数がデータベースに対応していません。管理者にお知らせください';
          }
        }
      }
      
    }
  }
}

print "mode : $mode";
print "<br>";
var_dump($_POST);
print "<br>";


//------------------------------------------------------------
// DBに接続
//------------------------------------------------------------
try {
  // DB接続(時刻も取得)
  $dbh = get_db_connect();
  $access_datetime = date('Y-m-d H:i:s');
  
  // DB接続情報が取得できていれば
  if (count($dbh) > 0){
    
    //----- カテゴリ・使用ソフトのマスタを取得 -----//
    $categories_master = get_categories_table_list($dbh);
    $skills_master = get_skills_table_list($dbh);
    
    if (count($categories_master) > 0) {
      // 特殊文字をHTMLエンティティに変換
      $categories_master = entity_assoc_array($categories_master);
    }
    if (count($skills_master) > 0) {
      // 特殊文字をHTMLエンティティに変換
      $skills_master = entity_assoc_array($skills_master);
    }
    //----------------------------------------------//
    
    
    //----- 登録情報の変更であれば、商品情報を取得 -----//
    if ($mode === 1) {
      
      if (isset($target_item_id)) {
        
        $str_where = 'where item_id = ' . $target_item_id;
        $target_item = get_items_table_list($dbh, $str_where);
        
        if (count($target_item) > 0) {
          
          // フォームに値をいれたい
          $item_name = $target_item[0]['item_name'];
          $price = $target_item[0]['price'];
          $item_introduction = $target_item[0]['item_introduction'];
          $item_introduction_detail = $target_item[0]['item_introduction_detail'];
          $item_status = $target_item[0]['item_status'];
          $stock = $target_item[0]['stock'];
          $main_category = $target_item[0]['main_category'];
          $categories = $target_item[0]['categories'];
          $skills = $target_item[0]['skills'];
          $item_img = image_link($target_item[0]['item_img']);
          
          // 値が入っていれば配列化
          if(mb_strlen($categories, HTML_CHARACTER_SET) > 0) {
            $selected_categories = explode(',', $categories);
          }
          if(mb_strlen($skills, HTML_CHARACTER_SET) > 0) {
            $selected_skills = explode(',', $skills);
          }
          
        } else {
          $err_msg[] = 'データベースから商品情報を取得できませんでした。';
        }
      } else {
        $err_msg[] = 'サーバーから商品情報を取得できませんでした。';
      }
    }
    //----------------------------------------------//
    
  }
  
  // POSTで
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 更新ボタンで移動した直後でなければ
    if ((isset($_POST['process_kind']) === TRUE) && ($_POST['process_kind'] !== 'to_update_item_info')) {
    
      // エラー配列に何も入っていなければ、画像 → DB の順で登録
      if (count($err_msg) === 0) {
    
        if
        (
          // ファイルを選択していて
          (check_uploadpicture_select('item_img') === TRUE)
          //【画像】アップロードされたファイルを指定ディレクトリに移動して保存
          // が失敗していれば
          && (move_uploaded_file($_FILES['item_img']['tmp_name'], IMAGE_DIRECTORY . $new_img_filename) !== TRUE)
        ) {
          // エラーを追加
          $err_msg[] = 'ファイルアップロードに失敗しました';
        
        
        //【DB】登録処理
        } else {

          //------------------------------------------------------------
          // 新規登録処理
          //------------------------------------------------------------
          if ($_POST['process_kind'] === 'item_insert') {
            
            var_dump($_POST['process_kind']);
            try {
            
              // DBにINSERTし、返り値として新レコードのIDも取得
              $new_item_id = 0;
              $new_item_id =
                insert_item_table_list
                ($dbh, $item_name, $price, $item_introduction, $item_introduction_detail, $new_img_filename, $item_status, $stock, $my_user_id, $access_datetime, $main_category, $categories, $skills);
                
              // O以上のIDを取得できていれば、クッキーに商品IDを保存
              if ($new_item_id > 0) {
                
                setcookie('item_id', $new_item_id);
                setcookie('item_name', $item_name);
                setcookie('item_img', image_link($new_img_filename));
                setcookie('item_info_mode', 0);
                
                //---------- 商品登録結果ページへ ----------//
                header('location: item_result.php');
                exit;
                //----------------------------------//
                
              // IDがOであればエラーのため、ストックテーブルの処理をしない
              } else {
                $err_msg[] = 'データの登録に失敗しました。';
              }
              
            } catch (PDOException $e) {
              
              $err_msg[] = 'データの登録処理に失敗しました。';
              // 例外をスロー
              throw $e;
            }
          }
          
          //------------------------------------------------------------
          // 更新処理
          //------------------------------------------------------------
          else if ($_POST['process_kind'] === 'item_update') {
            
            // トランザクション！！
            $dbh->beginTransaction();
            
            try {
              
              if (isset($_POST['target_item_id']) === TRUE){
                // 画像以外の基本情報を更新
                update_item_infomation
                  ($dbh, $_POST['target_item_id'], $item_name, $price, $item_introduction, $item_introduction_detail, $item_status, $stock, $access_datetime, $main_category, $categories, $skills);
              }
              
              if (isset($_POST['radio_img_change']) === TRUE){
                // 画像を更新
                if ($_POST['radio_img_change'] === '1') {
                  update_item_image
                  ($dbh, $_POST['target_item_id'], $new_img_filename, $access_datetime);
                }
              }
              
              // コミット処理
              $dbh->commit();

              setcookie('item_id', $_POST['target_item_id']);
              setcookie('item_name', $item_name);
              setcookie('item_img', image_link($new_img_filename));
              setcookie('item_info_mode', 1);
              
              //---------- 商品登録結果ページへ ----------//
              header('location: item_result.php');
              exit;
              //----------------------------------//
                
            } catch (PDOException $e) {
              $err_msg[] = 'データの更新処理に失敗しました。';
              // ロールバック
              $dbh->rollback();
              // 例外をスロー
              throw $e;
            }
            
          }
          
        }
      }
      
    }
  }

} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
  // 例外をスロー
  throw $e;
}

/*=====================================================*/
// 出品登録/商品情報更新 画面テンプレートファイル読み込み
include_once './view/V_item_info.php';

?>