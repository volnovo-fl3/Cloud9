<?php
header('Content-Type: text/html; charset=UTF-8');

$get_type = '';

if((isset($_GET) === TRUE) && (count($_GET) > 0)){
  print '$GET。。。';
  var_dump($_GET);
  print "<br>";
  
  if((isset($_GET['page_type']) === TRUE) && (mb_strlen($_GET['page_type']) > 0)){
    print '$GET[page_type]。。。';
    var_dump($_GET['page_type']);
    print "<br>";
    
    $get_type = $_GET['page_type'];
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>

  </head>

  <body>
    <h1>$GETテスト</h1>
    <?php
      if((isset($get_type) === TRUE) && (mb_strlen($get_type) > 0)){
    ?>
    <p>get有 <?php print $get_type?> がクリックされました。</p>
    <?php
      }
    ?>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/_test_get.php"><p>get無し</p></a>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/_test_get.php?page_type=A"><p>get有り A</p></a>
    <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/_test_get.php?page_type=B"><p>get有り B</p></a>
  </body>
</html>