<?php
/*------------------------------------------
作品情報更新
------------------------------------------*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';

//----- 変数初期化 -----//
$categories_mastar = [];
$skills_mastar = [];

$err_msg = [];
$my_user_id = '';
$target_product_id = '';
$str_where = ''; //データベース検索条件
$look_product = [];

$look_product_img = 'no';
$look_item_img = 'no';

$product_link = '';
$product_status = '';
$product_comment = '';

$header_user_name = '';
$header_user_img = '';
//----------------------//


//------------------------------------------------------------
// CookieにユーザーIDが残っていれば取得、残っていなければログイン画面へ
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


//------------------------------------------------------------
// $_POST
//------------------------------------------------------------
if((isset($_POST)) && (count($_POST) > 0)) {
  
  // 作品IDを取得
  if ((isset($_POST['target_product_id'])) && ($_POST['target_product_id'] > 0)) {
    $target_product_id = $_POST['target_product_id'];
  } else {
    $err_msg[] = 'サーバーから作品情報を取得できませんでした。';
  }
  
  // 更新であれば $_POST から値を取得
  if ((isset($_POST['process_kind'])) && ($_POST['process_kind'] === 'update_product_info')) {
    $product_link = $_POST['product_link'];
    $product_status = $_POST['product_status'];
    $product_comment = $_POST['product_comment'];
  }
}


//---------------------------------
// 更新時のエラーチェック
//---------------------------------
if ((isset($_POST['process_kind'])) && ($_POST['process_kind'] === 'update_product_info')) {
  
  if(check_str_count($product_link, 200) === FALSE) {
    $err_msg[] = 'URLを登録できませんでした。管理者にお問い合わせください。';
  }
  if(check_str_count($product_comment, 2000) === FALSE) {
    $err_msg[] = '作品のコメントは2000文字以内で入力してください。';
  }
  
  //--- 以下、画像アップロードチェック ---//
  // 画像なしならチェックをスルー
  if(check_uploadpicture_select('product_img') === TRUE) {
  
    //拡張子を取得 → 照合
    $extension = pathinfo($_FILES['product_img']['name'], PATHINFO_EXTENSION);
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



//------------------------------------------------------------
// DBに接続
//------------------------------------------------------------
try {
  // DB接続(時刻も取得)
  $dbh = get_db_connect();
  $access_datetime = date('Y-m-d H:i:s');
  
  // DB接続情報が取得できていて
  if (count($dbh) > 0){

    // POSTで
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
      
      // エラーが無ければ
      if (isset($err_msg) === FALSE || count($err_msg) === 0)  {
        
        // カテゴリ・使用ソフトのマスターを取得
        $categories_mastar = get_categories_table_list($dbh);
        $skills_mastar = get_skills_table_list($dbh);
        
        // 商品情報を取得
        try {
          
          $str_where = 'where p.product_id = ' . $target_product_id;
          $look_product = get_products_table_list($dbh, $str_where);
          
          // 取得できている時
          if(isset($look_product) === TRUE && count($look_product) > 0) {
            
            // 更新ボタンを押したのではないならばDBから変数セット
            if ((isset($_POST['process_kind'])) && ($_POST['process_kind'] !== 'update_product_info')) {
              $product_link = $look_product[0]['product_link'];
              $product_status = $look_product[0]['product_status'];
              $product_comment = $look_product[0]['product_comment'];
            }
            
            // 画像ファイルを判定
            $look_product_img = image_link($look_product[0]['product_img']);
            $look_item_img = image_link($look_product[0]['item_img']);
            
            // カテゴリ・使用ソフトを名前の配列に変換して取得
            $categories_name_list = id_to_name($categories_mastar, $look_product[0]['categories'], 'category_id', 'category_name');
            $skills_name_list = id_to_name($skills_mastar, $look_product[0]['skills'], 'skill_id', 'skill_name');
            
          // 取得できていない時
          } else {
            $err_msg[] = 'データベースから商品情報を取得できませんでした。';
          }
          
          //---------------------------------
          // 更新処理
          //---------------------------------
          if ((isset($_POST['process_kind'])) && ($_POST['process_kind'] === 'update_product_info')) {
            
            // エラー配列に何も入っていなければ、画像 → DB の順で登録
            if (count($err_msg) === 0) {
          
              if
              (
                // ファイルを選択していて
                (check_uploadpicture_select('product_img') === TRUE)
                //【画像】アップロードされたファイルを指定ディレクトリに移動して保存
                // が失敗していれば
                && (move_uploaded_file($_FILES['product_img']['tmp_name'], IMAGE_DIRECTORY . $new_img_filename) !== TRUE)
              ) {
                // エラーを追加
                $err_msg[] = 'ファイルアップロードに失敗しました';
              
              
              //【DB】更新処理
              } else {
              
                // トランザクション！！
                $dbh->beginTransaction();
                
                try {
                  
                  if (isset($_POST['target_product_id']) === TRUE){
                    // 画像以外の基本情報を更新
                    update_product_infomation
                      ($dbh, $_POST['target_product_id'], $product_link, $product_comment, $product_status, $access_datetime);
                  }
                  
                  if (isset($_POST['radio_img_change']) === TRUE){
                    // 画像を更新
                    if ($_POST['radio_img_change'] === '1') {
                      update_product_image
                      ($dbh, $_POST['target_product_id'], $new_img_filename, $access_datetime);
                    }
                  }
                  
                  // コミット処理
                  $dbh->commit();
                  
                  //---------- 制作リストへ ----------//
                  header('location: product_list.php');
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
// 作品詳細画面テンプレートファイル読み込み
include_once './view/V_product_info.php';