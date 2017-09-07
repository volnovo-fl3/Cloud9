<?php
/*------------------------------------------
ユーザー新規登録 / 更新
------------------------------------------*/
header('Content-Type: text/html; charset=UTF-8');

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';


//----- 変数初期化 -----//
$page_name = 'ユーザー新規登録';
$mode = 0; //0:新規登録 1:更新

// 更新時に使用
$target_user_id = 0;
$target_user = []; //更新用
$selected_categories = [];
$selected_skills = [];
$user_img = '';

// 共通
$err_msg = [];
$categories_master = [];
$skills_master = [];

$user_name = '';
$password = '';
$user_affiliation = '';
$user_self_introduction = '';
$categories = '';
$skills = '';
$new_img_filename = '';

$header_user_name = '';
$header_user_img = '';
//----------------------//


// ユーザー情報を更新するボタンで移動してきたときの初期動作
if((isset($_POST['process_kind']) === TRUE) && ($_POST['process_kind'] === 'to_update_user_info')){
  $mode = 1;
  $target_user_id = $_POST['target_user_id'];
  $page_name = 'ユーザー情報更新';
  
}

else
{
  //------------------------------------------------------------
  // ユーザー新規登録ボタンを押した時の処理
  //------------------------------------------------------------
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    //--- 変数に入力値を代入 ---//
    
    if(isset($_POST['user_name']) === TRUE) {
      $user_name = $_POST['user_name'];
    }
    if(isset($_POST['password']) === TRUE) {
      $password = $_POST['password'];
    }
    if(isset($_POST['user_affiliation']) === TRUE) {
      $user_affiliation = $_POST['user_affiliation'];
    }
    if(isset($_POST['user_self_introduction']) === TRUE) {
      $user_self_introduction = $_POST['user_self_introduction'];
    }
    if(isset($_POST['checked_categories']) === TRUE) {
      $categories = implode(',', $_POST['checked_categories']);
    }
    if(isset($_POST['checked_skills']) === TRUE) {
      $skills = implode(',', $_POST['checked_skills']);
    }
    
  
    //--- ユーザー新規登録エラーチェック ---//
    
    if(check_null($user_name) === FALSE) {
      $err_msg[] = ('ユーザー名を入力してください');
    } else if(check_str_count($user_name, 100) === FALSE) {
      $err_msg[] = 'ユーザー名は100文字以内で入力してください';
    }
    
    if(check_null($password) === FALSE) {
      $err_msg[] = 'パスワードを入力してください';
    } else if(check_str_password($password) === FALSE) {
      $err_msg[] = 'パスワードに使用できるのは半角英数字です';
    } else if(mb_strlen($password, HTML_CHARACTER_SET) < 6) {
      $err_msg[] = 'パスワードは6文字以上で入力してください';
    } else if(check_str_count($password, 40) === FALSE) {
      $err_msg[] = 'パスワードは40文字以内で入力してください';
    } else if(check_str_password($password, 40) === FALSE) {
      $err_msg[] = 'パスワードに使用できるのは半角英数字です';
    }
    
    if(check_str_count($user_affiliation, 100) === FALSE) {
      $err_msg[] = '所属企業・団体は100文字以内で入力してください';
    }
    
    if(check_str_count($user_self_introduction, 2000) === FALSE) {
      $err_msg[] = '自己紹介は2000文字以内で入力してください';
    }
    
    
    //--- 以下、画像アップロードチェック ---//
    // 画像なしならチェックをスルー
    if(check_uploadpicture_select('user_img') === TRUE) {
    
      //拡張子を取得 → 照合
      $extension = pathinfo($_FILES['user_img']['name'], PATHINFO_EXTENSION);
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

/*
print "mode : $mode";
print "<br>";
var_dump($_POST);
print "<br>";
*/

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
    

    //----- 登録情報の変更であれば、ユーザー情報を取得 -----//
    if ($mode === 1) {
      
      if (isset($target_user_id)) {
        $target_user = get_user_by_id($dbh, $target_user_id);
        
        if (count($target_user) > 0) {
          
          // フォームに値をいれたい
          $user_name = $target_user[0]['user_name'];
          $password = $target_user[0]['password'];
          $user_affiliation = $target_user[0]['user_affiliation'];
          $user_self_introduction = $target_user[0]['user_self_introduction'];;
          $categories = $target_user[0]['categories'];
          $skills = $target_user[0]['skills'];
          $user_img = image_link($target_user[0]['user_img']);
          
          // ヘッダー用
          $header_user_name = $user_name;
          $header_user_img = $user_img;
          
          // 値が入っていれば配列化
          if(mb_strlen($categories, HTML_CHARACTER_SET) > 0) {
            $selected_categories = explode(',', $categories);
          }
          if(mb_strlen($skills, HTML_CHARACTER_SET) > 0) {
            $selected_skills = explode(',', $skills);
          }
          
        } else {
          $err_msg[] = 'データベースからユーザー情報を取得できませんでした。';
        }
      } else {
        $err_msg[] = 'サーバーからユーザー情報を取得できませんでした。';
      }
    }
    //----------------------------------------------//
    
  }
  
  // POSTで
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 更新ボタンで移動した直後でなければ
    if ((isset($_POST['process_kind']) === TRUE) && ($_POST['process_kind'] !== 'to_update_user_info')) {
    
      // エラー配列に何も入っていなければ、画像 → DB の順で登録
      if (count($err_msg) === 0) {
        
        if
        (
          // ファイルを選択していて
          (check_uploadpicture_select('user_img') === TRUE)
          //【画像】アップロードされたファイルを指定ディレクトリに移動して保存
          // が失敗していれば
          && (move_uploaded_file($_FILES['user_img']['tmp_name'], IMAGE_DIRECTORY . $new_img_filename) !== TRUE)
        ) {
          // エラーを追加
          $err_msg[] = 'ファイルアップロードに失敗しました';
        
        
        // DB処理
        } else {
          
          // 同名ユーザーチェック
          if((isset($_POST['target_user_id']) === TRUE) && (mb_strlen($_POST['target_user_id']) > 0)) {
            $target_user_id = $_POST['target_user_id'];
          }
          if(check_same_name($dbh, $user_name, $target_user_id) === FALSE) {
            $err_msg[] = '同名のユーザーが存在します。ユーザー名を変更してください。';
          }
          
          // エラー配列に何も入っていなければ、DBに登録
          if (count($err_msg) === 0) {
            
            //------------------------------------------------------------
            // 新規登録処理
            //------------------------------------------------------------
            if ($_POST['process_kind'] === 'user_insert') {
              
              var_dump($_POST['process_kind']);
              try {
              
                // DBにINSERTし、返り値として新レコードのIDも取得
                $new_user_id = 0;
                $new_user_id =
                  insert_user_table_list
                  ($dbh, $password, $user_name, $user_affiliation, $user_self_introduction, $new_img_filename, $categories, $skills, $access_datetime);
                  
                var_dump($_POST[$new_user_id]);
                
                // O以上のIDを取得できていれば、クッキーにユーザーIDを保存
                if ($new_user_id > 0) {
                  
                  setcookie('user_id', $new_user_id);
                  setcookie('user_name', $user_name);
                  setcookie('user_img', $new_img_filename);
                  
                  //---------- マイページへ ----------//
                  header('location: mypage.php');
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
            else if ($_POST['process_kind'] === 'user_update') {
              
              var_dump($_POST['target_user_id']);
              
              // トランザクション！！
              $dbh->beginTransaction();
  
              try {
                
                if (isset($_POST['target_user_id']) === TRUE){
                  // 画像以外の基本情報を更新
                  update_user_infomation
                    ($dbh, $_POST['target_user_id'], $password, $user_name, $user_affiliation, $user_self_introduction, $categories, $skills, $access_datetime);
                }
                
                if (isset($_POST['radio_img_change']) === TRUE){
                  // 画像を更新
                  if ($_POST['radio_img_change'] === '1') {
                    update_user_image($dbh, $_POST['target_user_id'], $new_img_filename, $access_datetime);
                  }
                }
                
                // コミット処理
                $dbh->commit();
                print 'コミット';
  
                //---------- マイページへ ----------//
                header('location: mypage.php');
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
  }
  

  
} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
  // 例外をスロー
  throw $e;
}

/*=====================================================*/
// ユーザー登録画面テンプレートファイル読み込み
include_once './view/V_user_info.php';

?>