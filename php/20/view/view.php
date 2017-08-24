<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>ひとこと掲示板</title>
    <style>
    p {
    	color: red;
    }
    </style>
  </head>

  <body>
    <h1>ひとこと掲示板(MVCver.)</h1>
  <?php
    if (isset($post_error) === TRUE) {
  ?>
      <ul>
  <?php
      foreach ($post_error as $key => $error) {
  ?>
        <li><p>
        <?php print entity_str($error); ?>
        </p></li>
  <?php
      }
  ?>
      </ul>
  <?php
    }
  ?>
  
    <form method="post">
      <label>名前：</label>
      <input type="text" name="name">
      <label>　ひとこと：</label>
      <input type="text" name="comment">
      <input type="submit" value="送信">
    </form>
      
  <?php
    /* -------------------------
    / $comments_dataにデータがある時！
    / 投稿内容を表示する
    / ------------------------ */
    if (isset($comments_data) === TRUE) 
    {
  ?>
      <ul>
  <?php
      foreach ($comments_data as $key => $comment) {
  ?>
        <li>
        <?php print $comment[1] . '：' . $comment[2]. '　- ' . $comment[3]; ?>
        </li>
  <?php
      }
  ?>
      </ul>
  <?php
    } else {
    /* -------------------------
    / $comments_dataにデータがない時！
    / ------------------------ */
        print '書き込みがありません。';
    }
  ?>
      
  </body>
</html>