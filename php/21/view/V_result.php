<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>自動販売機(購入結果)</title>
    <style>
    .contena {
      width: 350px;
      margin: 10px;
    }
    .drink {
      display: block;
      border: solid 1px;
      text-align: center;
      padding: 10px;
    }
    .drink p {
      word-wrap: break-word;
    }
    .red {
      color: #FF0000;
    }
    .image {
      max-width:70%;
      max-height:250px;
    }
      
    </style>
  </head>

  <body>
    <a href="https://codeincubate-tanakanoboru.c9users.io/php/21/index.php"><p>購入画面に戻る</p></a>
    <h1>購入結果</h1>
    
    <?php
      // $_POSTが無いとき（直リンクなど）
      if (empty($_POST) === TRUE) {
        print 'エラー : 購入画面にて商品を購入してください。';
        
      // $_POSTがあれば通常動作
      } else {
    ?>

    <?php
        // DBエラーがあれば表示
        if (empty($err_msg) === FALSE) {
          foreach($err_msg as $key => $db_err) {
    ?>
    <p><?php print entity_str($db_err)?></p>
    <?php
          }
        }
    ?>
    
    <section>
    <?php
        // エラーメッセージがあれば表示
        if(empty($shopping_err) === FALSE) {
          foreach($shopping_err as $key => $s_err) {
    ?>
      <ul>
        <li><span class="red"><?php print entity_str($s_err)?></span></li>
      </ul>
    <?php
          }
        }
    ?>
    
    <?php
        // 取得した商品情報を表示
        if(empty($drink_data) === FALSE) {
    ?>
      <div class="contena">
        <div class="drink">
          <img src="<?php print IMAGE_DIRECTORY . $drink_data[0]['img']; ?>" class="image">
          <p><?php print $drink_data[0]['drink_name']?></p>
          <p>¥<?php print $drink_data[0]['price']?></p>
        </div>
      </div>
    <?php
        // 商品の情報を取得できていなければエラー
        } else {
          print 'エラー : 商品情報を取得できませんでした。';
        }
    ?>
    </section>
    <?php
      }
    ?>
    
    <?php
    // 問題なく買い物を終えられたとき
      if($isOK === TRUE) {
    ?>
    <section>
      <p>がしゃん！【<?php print $drink_data[0]['drink_name']?>】が買えました！</p>
      <p>おつりは【<?php print ($_POST[INDEX_MONEY] - $drink_data[0]['price']) ?>】円です。</p>
    </section>
    <?php
      }
    ?>

  </body>
</html>