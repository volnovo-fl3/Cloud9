<?php
/*=====================================================
/ 自動販売機 商品管理画面
/=====================================================*/

// このページのURL
$own_URL = 'https://codeincubate-tanakanoboru.c9users.io/php/21/tool.php';

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';


$err_msg = []; //DBエラー用
$insert_err = []; //入力エラーチェック用
$update_err = []; //更新エラーチェック用
$drink_data = [];
$new_img_filename = ''; // アップロードした新しい画像ファイル名
$tool_message = []; //登録・更新時のメッセージ


// 同一ページ内でPOSTを走らせた後のみ、登録更新メッセージを取得しようとする
if(isset($_SERVER['HTTP_REFERER']) === TRUE){
  if($_SERVER['HTTP_REFERER'] === $own_URL) {
    $tool_message = read_text(TOOL_MESSAGE);
  }
}

//------------------------------------------------------------
// POSTを受け取ったとき
//------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  // 押されたボタンを判別
  $process_kind = "";
  if (isset($_POST['process_kind']) ) {
    $process_kind = $_POST['process_kind'];
  }
  
  //登録・更新後のメッセージを初期化
  $tool_message = read_text(TOOL_MESSAGE);
  delete_text(TOOL_MESSAGE, $tool_message);
  $tool_message = [];

  //------------------------------------------------------------
  // エラーチェック
  // 発生したエラーは配列にどんどん追加する
  //------------------------------------------------------------
  unset($insert_err);
  unset($update_err);

  //----- ★☆★ 新規登録 ★☆★ -----//
  if ($process_kind === 'insert_item'){
    
    if(check_null(TOOL_INS_NAME) === FALSE) {
      $insert_err[] = '商品名を入力してください';
    } else if(check_str_count(TOOL_INS_NAME, 100) === FALSE) {
      $insert_err[] = '商品名は100文字以内で入力してください';
    }
    
    if(check_null(TOOL_INS_PRICE) === FALSE) {
      $insert_err[] = '価格を入力してください';
    } else if (check_int_minus(TOOL_INS_PRICE) === FALSE) {
      $insert_err[] = '価格は¥0以上で入力してください';
    }
    
    if(check_null(TOOL_INS_STOCK) === FALSE) {
      $insert_err[] = '個数を入力してください';
    } else if (check_int_minus(TOOL_INS_STOCK) === FALSE) {
      $insert_err[] = '個数は0個以上で入力してください';
    }
    
    if(check_int_status(TOOL_INS_STATUS) === FALSE) {
      $insert_err[] = 'ステータスは「公開」か「非公開」を選択してください';
    }
    
    
    //--- 以下、画像アップロードチェック ---//
    
    if(check_uploadpicture_select() === FALSE) {
      $insert_err[] = 'ファイルを選択してください';
    } else {
      
      //拡張子を取得 → 照合
      $extension = pathinfo($_FILES[TOOL_INS_IMG]['name'], PATHINFO_EXTENSION);
      if(check_uploadpicture_extension($extension) === FALSE) {
        $insert_err[] = 'ファイル形式が異なります（JPEG、PNG が利用可能です）';
      } else {
        
        // 保存する新しいファイル名の生成（ユニークな値を設定する） → 照合
        $new_img_filename = sha1(uniqid(mt_rand(), true)). '.' . $extension;
        if(check_uploadpicture_filename($new_img_filename) === FALSE) {
          $insert_err[] = 'ファイルアップロードに失敗しました。再度お試しください';
        } else {
          
          // VARCHAR(100)で引っかかった場合もエラー
          if(mb_strlen($new_img_filename, HTML_CHARACTER_SET) > 100) {
            $insert_err[] = '画像ファイルの文字数がデータベースに対応していません。管理者にお知らせください';
          }
        }
      }
    }
    
  //----- ★☆★ 商品の在庫数を変更する時 ★☆★ -----//
  } else if ($process_kind === 'update_stock'){
    
    if(check_null(TOOL_UPD_STOCK) === FALSE) {
      $update_err[] = '個数を入力してください';
    } else if (check_int_minus(TOOL_UPD_STOCK) === FALSE) {
      $update_err[] = '個数は0個以上で入力してください';
    }
  }
}


//------------------------------------------------------------
// DBに接続
//------------------------------------------------------------
try {
  // DB接続
  $dbh = get_db_connect();

  // アクセス日時を取得
  $access_datetime = date('Y-m-d H:i:s');
  
  // DB接続情報が取得できていて
  if (empty($dbh) === FALSE){
    // POSTで
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      
      // 新規登録で
      if ($process_kind === 'insert_item') {
        // エラー配列に何も入っていなければ、画像 → DB の順で登録
        if (empty($insert_err) === TRUE) {
          
          //【画像】アップロードされたファイルを指定ディレクトリに移動して保存
          if (move_uploaded_file($_FILES[TOOL_INS_IMG]['tmp_name'], IMAGE_DIRECTORY . $new_img_filename) !== TRUE) {
            $insert_err[] = 'ファイルアップロードに失敗しました';
            
          //【DB】トランザクション処理でINSERT
          } else {
            
            // トランザクション！！
            $dbh->beginTransaction();
            
            try {
              
              // DBにINSERTし、返り値として新レコードのIDも取得
              $new_drinkid = 0;
              $new_drinkid = insert_drink_table_list($dbh, $new_img_filename, $access_datetime);
              
              // O以上のIDを取得できていれば、そのままストックテーブルにも登録
              if ($new_drinkid > 0) {
                
                insert_drinkstock_table_list($dbh, $new_drinkid, $access_datetime);
                
                // コミット処理
                $dbh->commit();
                write_text(TOOL_MESSAGE, '『' . $_POST[TOOL_INS_NAME] . '』を新規登録しました');
                
                //---------- リロード対策 ----------//
                header('location: '.$own_URL);
                exit;
                //----------------------------------//
                
              // IDがOであればエラーのため、ストックテーブルの処理をしない
              } else {
                // ロールバック
                $dbh->rollback();
                $insert_err = 'データの登録に失敗しました。';
              }
            } catch (PDOException $e) {
                // ロールバック
                $dbh->rollback();
                // 例外をスロー
                throw $e;
            }
          }
        }
        
      // 在庫数変更で
      } else if ($process_kind === 'update_stock') {
        // エラー配列に何も入っていなければ、DB を更新
        if (empty($update_err) === TRUE) {
          
          // トランザクション！！
          $dbh->beginTransaction();
          
          try {
            update_drinkstock($dbh, $_POST[TOOL_UPD_STOCK], $access_datetime, $_POST[TOOL_GET_ID]);
            
            // コミット処理
            $dbh->commit();
            write_text(TOOL_MESSAGE, '『' . $_POST[TOOL_GET_NAME] . '』の在庫を '. $_POST[TOOL_UPD_STOCK] .'個 に変更しました');

            //---------- リロード対策 ----------//
            header('location: '.$own_URL);
            exit;
            //----------------------------------//
            
          } catch (PDOException $e) {
            // ロールバック
            $dbh->rollback();
            // 例外をスロー
            throw $e;
          }
          
        }
        
      // 公開ステータス変更で
      } else if ($process_kind === 'update_status') {
        
        // トランザクション！！
        $dbh->beginTransaction();
        
        try {
          update_drinkstatus($dbh, $access_datetime);
          
          // コミット処理
          $dbh->commit();
          
          if ($_POST[TOOL_UPD_STATUS] == 0){
            $status_msg = '非公開';
          } else {
            $status_msg = '公開';
          }
          write_text(TOOL_MESSAGE, '『' . $_POST[TOOL_GET_NAME] . '』の公開ステータスを「' . $status_msg . '」に変更しました');
          
          //---------- リロード対策 ----------//
          header('location: '.$own_URL);
          exit;
          //----------------------------------//
          
        } catch (PDOException $e) {
          // ロールバック
          $dbh->rollback();
          // 例外をスロー
          throw $e;
        }
          
      }
    }
    

    //------------------------------------------------------------
    // 商品一覧を取得
    //------------------------------------------------------------
  
    // 商品一覧を取得
    $drink_data = get_drinks_table_list($dbh);
    
    if (empty($drink_data) === FALSE) {
        // 特殊文字をHTMLエンティティに変換
        $drink_data = entity_assoc_array($drink_data);
    }
  
  }
} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}


/*=====================================================*/
// 商品管理画面テンプレートファイル読み込み
include_once './view/V_tool.php';


?>