<?php
header('Content-Type: text/html; charset=UTF-8');

$test_text = '';

if((isset($_POST) === TRUE) && (count($_POST) > 0)){
  $test_text = $_POST['test_text'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<title>テキストエリアの高さをリサイズ</title>
<script type="text/javascript">
  function resize(Tarea){
    var areaH = Tarea.style.height;
    if(Tarea.value == ""){areaH=26+"px";}
     
    areaH = parseInt(areaH) - 30;
    if(areaH < 30){areaH = 30}
    Tarea.style.height = areaH + "px";
    Tarea.style.height = (parseInt(Tarea.scrollHeight)) + "px";
  }
</script>
</head>

<body>
  <h1>textareaのテスト</h1>
  <?php
    if(mb_strlen($test_text) > 0){
  ?>
  <h3>入力されたテキスト</h3>
  <p><?php print nl2br(htmlspecialchars($test_text, ENT_QUOTES, "UTF-8"), false)?></p>
  <?php
    }
  ?>
  <form method="post" enctype="multipart/form-data">
    <textarea name='test_text'></textarea>
    <!--
    <textarea style="width:300px;height:26px;padding:6px 5px;overflow:hidden;" onkeyup="resize(this)"></textarea>
    -->
    <input type="submit" value='内容を確認する'>
  </form>
</body>
</html>