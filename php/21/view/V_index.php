<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>自動販売機</title>
    <style>
      #flex {
        display: flex;
        width: 1000px;
        
        /* ↓↓ 参考にしました http://sutara79.hatenablog.com/entry/2016/09/27/112337 ↓↓ */
        /* 横並びに表示する */
        flex-direction: row;
        /* 画面幅に収まらない場合は折り返す */
        flex-wrap: wrap;        
      }
      
      #flex .drink {
        text-align: center;
        padding: 10px;
        float: left; 
      }
      
      #flex span {
        width: 200px;
        display: block;
        margin: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      
      .img_size {
        height: 200px;
      }
      
      .red {
        color: #FF0000;
      }
      
    </style>
  </head>

  <body>
    <a href="https://codeincubate-tanakanoboru.c9users.io/php/21/tool.php"><p>商品管理画面へ</p></a>
    <h1>自動販売機</h1>

    <section>
      <?php
        // 2. データを無事に取得できていれば(空でなければ)商品を表示
        if(empty($drink_data) === FALSE) {
      ?>
      
      <form method="post" action="./result.php">
        <label>お金を入れてください　<input type="number" name="<?php print INDEX_MONEY?>" value="0">円</label>
        <div id="flex">
      <?php
          foreach($drink_data as $key => $value) {
            if ($value['status'] == 1) {
      ?>
          <div class="drink">
            <span>
              <img class="img_size" src="<?php print IMAGE_DIRECTORY . $value['img']; ?>">
            </span>
            <span><?php print $value['drink_name']?></span>
            <span>¥<?php print $value['price']?></span>
          
      <?php if($value['stock'] > 0) { ?>
            <input type="radio" name="<?php print INDEX_GET_ID?>" value="<?php print $value['drink_id']?>">
      <?php } else { ?>
            <span class="red">売り切れ</span>
      <?php } ?>
          </div>
      <?php
            }
          }
      ?>
        </div>
        <hr>
        <input type="submit" value="買う">
      </form>

      <?php
        // エラーがあるときはエラー内容を表示
        } else {
          print '商品データがみつかりません。';
        }
      ?>
    </section>

  </body>
</html>