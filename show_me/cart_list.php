<?php
/*------------------------------------------
カート確認
------------------------------------------*/
header('Content-Type: text/html; charset=UTF-8');

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';


//----- 変数初期化 -----//
$err_msg = [];
$user_id = '';
$user = [];
$categories_master = [];
$skills_master = [];
$carts_unpaid_sum = [];
$carts_unpaid_list = [];
$search_where = '';
$pay_money = ''; //支払い金額

$cart_id = ''; // 削除・更新用
$amount_change = '';

$cart_ids_paid = []; //購入後のカートIDを配列で
//----------------------//


// Cookieにページ遷移履歴を保存
setcookie('last_page', 'mypage.php');

// ユーザーIDを取得
if (isset($_COOKIE['user_id']) === TRUE) {
  $user_id = $_COOKIE['user_id'];
}
// 取得できなければ、ログイン画面へ
else {
  //---------- ログインページへ ----------//
  header('location: login.php');
  exit;
  //----------------------------------//
}


//------------------------------------------------------------
// POST処理
//------------------------------------------------------------
if((isset($_POST)) && (count($_POST) > 0)) {
  
  if((isset($_POST['process_kind'])) && (mb_strlen($_POST['process_kind']) > 0)) {
    
    //--- カート内の商品を購入 ---//
    if($_POST['process_kind'] === 'cart_item_pay') {
      
      if((isset($_POST['pay_money'])) && (mb_strlen($_POST['pay_money']) > 0)) {
        $pay_money = $_POST['pay_money'];
      }
    }
    
    //--- カートの個数を変更 ---//
    else if($_POST['process_kind'] === 'cart_item_amount_change') {
      
      var_dump($_POST);
      print "<br>";
      
      if((isset($_POST['cart_id'])) && (mb_strlen($_POST['cart_id']) > 0)) {
        $cart_id = $_POST['cart_id'];
        $amount_change = $_POST['amount_change'];
        
      } else {
        $err_msg[] = 'カート情報を取得できませんでした。';
      }
    }
    
    //--- カートの中身を削除 ---//
    else if($_POST['process_kind'] === 'cart_item_delete') {
      
      if((isset($_POST['cart_id'])) && (mb_strlen($_POST['cart_id']) > 0)) {
        $cart_id = $_POST['cart_id'];
        print "cart_id：$cart_id ";
        print '個数を変更';
      } else {
        $err_msg[] = 'カート情報を取得できませんでした。';
      }
    }
    
  } else {
    $err_msg[] = '情報がありません。';
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

    try {
      
      // ユーザー情報、カテゴリ・使用ソフトマスタを取得
      $user = get_user_by_id($dbh, $user_id);
      $categories_master = get_categories_table_list($dbh);
      $skills_master = get_skills_table_list($dbh);
      $carts_unpaid_sum = get_carts_unpaid_sum($dbh, $user_id);
      
      // 検索条件のwhere文を作成し、検索
      $search_where =
        "where
          cart.bought_datetime is null
          and cart.deleted_datetime is null
          and cart.buyer_user_id = $user_id";
      $carts_unpaid_list = get_carts_table_list($dbh, $search_where);
      
      
      if((isset($_POST)) && (count($_POST) > 0)) {
        if((isset($_POST['process_kind'])) && (mb_strlen($_POST['process_kind']) > 0)) {
          
          //-------------------------------------------
          // カート内の商品を購入する時
          //-------------------------------------------
          if($_POST['process_kind'] === 'cart_item_pay') {
            
            // 在庫・ステータスチェック
            foreach($carts_unpaid_list as $key => $unpaid_item){
              if ($unpaid_item['item_status'] === '0') {
                $err_msg[] = $unpaid_item['item_name'] . ' が非公開に変更されました。カートから削除してください。';
              }
              if ($unpaid_item['stock'] < $unpaid_item['amount']) {
                $err_msg[] = $unpaid_item['item_name'] . ' の在庫が足りません。個数を減らすか、カートから削除してください。';
              }
            }
            
            // 金額チェック
            if(check_null($pay_money) === FALSE) {
              $err_msg[] = 'お支払い金額を入力してください。';
            } else if(check_int_minus($pay_money) === FALSE) {
              $err_msg[] = '¥0以上の金額を入力してください。';
            } else if ($carts_unpaid_sum[0]['cart_sum_price'] > $pay_money){
              $err_msg[] = 'お支払い金額が不足しています。';
            }
            
            // エラーがなければBD処理
            if(count($err_msg) === 0) {
              
              // トランザクション！！
              $dbh->beginTransaction();
              
              try {
                
                foreach($carts_unpaid_list as $key => $unpaid_item){
                  
                  //1. 作品テーブルに個単位で行追加
                  for($i = 1; $i <= $unpaid_item['amount']; $i++){
                    insert_products_table_list($dbh, $unpaid_item['cart_id'], $access_datetime);
                  }
                  
                  //2. カートの"購入日"を入力
                  bought_carts_item($dbh, $unpaid_item['cart_id'], $access_datetime);
                  
                  //3. 商品の在庫を減らす
                  change_item_stock_by_bought
                    ($dbh, $unpaid_item['item_id'], $unpaid_item['amount'], $access_datetime);
                    
                  $cart_ids_paid[] = $unpaid_item['cart_id'];
                }
                
                // コミット処理
                $dbh->commit();
                
                setcookie('cart_ids_paid', implode(',', $cart_ids_paid));
                setcookie('cart_price_paid', $carts_unpaid_sum[0]['cart_sum_price']);
                setcookie('cart_amount_paid', $carts_unpaid_sum[0]['cart_sum_amount']);
                
                //----- 4. 登録完了ページへ -----//
                header('location: cart_paid_result.php');
                exit;
                //-------------------------------//
                
              } catch(PDOException $e) {
                $err_msg[] = '購入処理に失敗しました。';
                // ロールバック
                $dbh->rollback();
                // 例外をスロー
                throw $e;
              }
              
            }
          }
          
          //-------------------------------------------
          // カート内商品の個数を変更する時
          //-------------------------------------------
          else if($_POST['process_kind'] === 'cart_item_amount_change') {
            
            if(check_null($amount_change) === FALSE) {
              $err_msg[] = '個数を入力してください';
            } else if(check_int_minus($amount_change) === FALSE) {
              $err_msg[] = '個数は0個以上で設定してください';
            }
            
            // エラーがなければBD処理
            if(count($err_msg) === 0) {
              change_cart_item_amount($dbh, $cart_id, $amount_change, $access_datetime);
              
              //---------- ページ再読み込み ----------//
              header('location: cart_list.php');
              exit;
              //----------------------------------//
            }
            
          }
          
          //-------------------------------------------
          // カート内商品を削除する時
          //-------------------------------------------
          else if($_POST['process_kind'] === 'cart_item_delete') {
            delete_carts_item($dbh, $cart_id, $access_datetime);
              
            //---------- ページ再読み込み ----------//
            header('location: cart_list.php');
            exit;
            //----------------------------------//
          }
          
        }
      }
      
    } catch (PDOException $e) {
        // 例外をスロー
        throw $e;
    }
  }
} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}


/*=====================================================*/
// カート確認テンプレートファイル読み込み
include_once './view/V_cart_list.php';

?>