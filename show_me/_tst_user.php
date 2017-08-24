<?php
/*------------------------------------------
ユーザー新規登録
------------------------------------------*/
header('Content-Type: text/html; charset=UTF-8');
// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/M_function.php';


// このファイル(リダイレクト用)
$own_URL = 'user.php';

$err_msg = [];
$categories_data = [];
$skills_data = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  $categories = '';
  $skills = '';

  if(count($_POST['checked_categories']) > 0) {
    $categories = implode(',', $_POST['checked_categories']);
  }
  if(count($_POST['checked_skills']) > 0) {
    $skills = implode(',', $_POST['checked_skills']);
  }
  
  var_dump($categories);
  var_dump($skills);

/*  
  $array01 = explode(',', $categories);
  $array02 = explode(',', $skills);
  var_dump($array01);
  var_dump($array02);
  
  $categories_name = [];
  $skills_name = [];
  
  foreach($array01 as $key => $id) {
    $categories_name = $categories_data[$id-1];
  }
  foreach($array02 as $key => $id) {
    $skills_name = $skills_data[$id-1];
  }
  var_dump($categories_name);
  var_dump($skills_name);
*/
  
}




//------------------------------------------------------------
// DBに接続
//------------------------------------------------------------
try {
  // DB接続
  $dbh = get_db_connect();
  
  // DB接続情報が取得できていれば
  if (count($dbh) > 0){

    $categories_data = get_categories_table_list($dbh);
    $skills_data = get_skills_table_list($dbh);
    
    if (count($categories_data) > 0) {
      // 特殊文字をHTMLエンティティに変換
      $categories_data = entity_assoc_array($categories_data);
    }
    if (count($skills_data) > 0) {
      // 特殊文字をHTMLエンティティに変換
      $skills_data = entity_assoc_array($skills_data);
    }
    
    //実験
    
    print '実験開始'."<br>";
    
    $categories_test = [];
    $test_array = array(1,3,4,6,9);
    
    foreach($categories_data as $key => $category){
      
      foreach($test_array as $key => $id){

        if($category['category_id'] == $id) {
          print entity_str('ID一致 ID: '. $id) . "<br>";
          $categories_test[] = entity_str($category['category_name']);
        }
      }
    }
    
    var_dump($categories_test);
    
  }
} catch (Exception $e) {
  $err_msg[] = $e->getMessage();
}


?>