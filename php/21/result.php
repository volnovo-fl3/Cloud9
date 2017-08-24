<?php
/*=====================================================
/ 自動販売機 購入結果画面
/=====================================================*/

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';


$err_msg = []; //DBエラー用
$shopping_err = []; //購入エラーチェック
$drink_data = [];
$new_img_filename = ''; // アップロードした新しい画像ファイル名
$isOK = FALSE; //ちゃんと買えたかどうかのフラグ

//------------------------------------------------------------
// POSTを受け取ったときのみ処理
//------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  unset($shopping_err);
  $isOK = FALSE;
  
  // エラーチェック(IDのみ)
  if(check_null(INDEX_GET_ID) === FALSE) {
    $shopping_err[] = '商品を選択してください';
  }
}


//------------------------------------------------------------
// データベース！
//------------------------------------------------------------
try {
  // DB接続
  $dbh = get_db_connect();
  
  // アクセス日時を取得
  $access_datetime = date('Y-m-d H:i:s');
  
  // DB接続情報が取得できていて
  if (empty($dbh) === FALSE){
    
    // エラー（商品が選択されていない）がなければ
    if (empty($shopping_err) === TRUE) {
      
      // IDから商品情報を取得
      $drink_data = get_drink_by_id($dbh, $_POST[INDEX_GET_ID]);
      
      if (empty($drink_data) === FALSE) {
        // 特殊文字をHTMLエンティティに変換
        $drink_data = entity_assoc_array($drink_data);
      }
      
      
      //------------------------------------------------------------
      // そのままエラーチェック！！
      //------------------------------------------------------------
      if (empty($drink_data) === FALSE) {
        
        // 在庫
        if($drink_data[0]['stock'] <= 0) {
          $shopping_err[] = date("m/d H:i",strtotime($drink_data[0]['stock_update'])) . ' に売り切れてしまいました';
        }
        
        // ステータス
        if($drink_data[0]['status'] != 1) {
          $shopping_err[] = date("m/d H:i",strtotime($drink_data[0]['master_update'])) . ' に取り扱いを中止しました（非公開商品となりました）';
        }

        // 金額
        if(check_null(INDEX_MONEY) === FALSE) {
          $shopping_err[] = 'お金を入れてください';
        } else {
          if (check_int_minus(INDEX_MONEY) === FALSE) {
            $shopping_err[] = '¥0 以上の金額を入力してください';
          }
          if ($_POST[INDEX_MONEY] < $drink_data[0]['price']) {
            $shopping_err[] = '¥'. $_POST[INDEX_MONEY] .' ではお金が足りません';
          }
        }
      }
      
      
      //------------------------------------------------------------
      // エラーがなければ購入処理
      //------------------------------------------------------------
      if (empty($shopping_err) === TRUE) {
        
        // トランザクション！！
        $dbh->beginTransaction();
        
        try {
          
          // 購入履歴を追加
          insert_drink_history($dbh, $_POST[INDEX_GET_ID], $access_datetime);
          
          // 在庫から -1
          update_drinkstock(
            $dbh
            , $drink_data[0]['stock'] - 1
            , $access_datetime
            , $_POST[INDEX_GET_ID]
          );
          
          // コミット処理
          $dbh->commit();
          $isOK = TRUE;
            
        } catch (PDOException $e) {
          // ロールバック
          $dbh->rollback();
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
// 購入結果画面テンプレートファイル読み込み
include_once './view/V_result.php';


?>