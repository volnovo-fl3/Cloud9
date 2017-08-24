<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>自動販売機 (商品管理)</title>
    <style>
      table {
        width: 800px;
        border-collapse: collapse;
      }
      table, tr, th, td {
        border: solid 1px;
        padding: 10px;
        text-align: center;
      }
      caption {
        text-align: left;
      }
      span {
        color: red;
      }
      .blue {
        color: blue;
      }
    </style>
  </head>

  <body>
    <a href="https://codeincubate-tanakanoboru.c9users.io/php/21/index.php"><p>購入画面へ</p></a>
    <h1>自動販売機 商品管理</h1>
    <hr>
    
    <section>
      <h2>商品新規登録</h2>
      
      <!-- 入力エラーがあればここに表示 -->
      <?php
        if(empty($insert_err) === FALSE) {
          foreach ($insert_err as $key => $INSerror) {
      ?>
      <ul>
        <li>
          <span><?php print entity_str($INSerror)?></span>
        </li>
      </ul>
      <?php
          }
        }
      ?>
      
      <!-- 新規登録用入力フォーム -->
      <form method="post" enctype="multipart/form-data">
        <div><label>名前：<input type="text" name="<?php print TOOL_INS_NAME?>"></label></div>
        <div><label>価格：<input type="number" name="<?php print TOOL_INS_PRICE?>"></label></div>
        <div><label>個数：<input type="number" name="<?php print TOOL_INS_STOCK?>"></label></div>
        <div><input type="file" name="<?php print TOOL_INS_IMG?>"></div>
        <div>
          <select name="<?php print TOOL_INS_STATUS?>">
            <option value="0">非公開</option>
            <option value="1" selected>公開</option>
          </select>
        </div>
        <div><input type="submit" value="商品を追加する"></div>
        <input type="hidden" name="process_kind" value="insert_item">
      </form>
    </section>
    <hr>
    
    <section>
      <h2>商品情報変更</h2>
      <?php
        // 1. DBエラーのとき → 延々とエラーを釈明
        if(empty($err_msg) === FALSE) {
          foreach ($err_msg as $key => $DBerror){
      ?>
        <p><?php print entity_str($DBerror) ?></p>
      <?php
          }
        }
      ?>
      
      <?php
        // 2. データを無事に取得できていれば(空でなければ)商品を表示
        if(empty($drink_data) === FALSE) {
      ?>
      
      <?php
          // 3. 新規登録・情報変更メッセージがあれば表示
          if(empty($tool_message) === FALSE) {
            foreach ($tool_message as $key => $t_msg) {
      ?>
      <p class='blue'><?php print $t_msg ?></p>
      <?php
            }
          }
      ?>
      
      <table>
        <caption>商品一覧</caption>
        <tr>
          <th>商品画像</th>
          <th width="700">商品名</th>
          <th>価格</th>
          <th>在庫数</th>
          <th>ステータス</th>
        </tr>
        
      <?php foreach ($drink_data as $value)  { 
        // 背景色を設定(非公開ならグレーに)
        if ($value['status'] == 0) { //HTMLエスケープでstring型になるため、==で整合性チェック
      ?>
        <tr style="background-color:#a9a9a9;">
      <?php
        } else {
      ?>
        <tr>
      <?php
        }
      ?>
          <td><img src="<?php print IMAGE_DIRECTORY . $value['img']; ?>" height="200"></td>
          
          <td><?php print $value['drink_name']; ?></td>
          
          <td><?php print '¥' . $value['price']; ?></td>
          
          <td>
            <form method="post" enctype="multipart/form-data">
              <div>
                <input
                  type="number"
                  name="<?php print TOOL_UPD_STOCK?>"
                  value="<?php print $value['stock']; ?>"
                >
                <label>個</label>
                <input type="submit" value="変更">
              </div>
              <input type="hidden" name="<?php print TOOL_GET_ID?>" value="<?php print $value['drink_id']; ?>">
              <input type="hidden" name="<?php print TOOL_GET_NAME?>" value="<?php print $value['drink_name']; ?>">
              <input type="hidden" name="process_kind" value="update_stock">
            </form>
            <?php if(empty($update_err) === FALSE) {
              if ($value['drink_id'] === $_POST[TOOL_GET_ID]) {
                foreach ($update_err as $upderr) {
            ?>
            <p><span><?php print entity_str($upderr)?><span></p>
            <?php
                }
              }
            }?>
          </td>
          
          <td>
            <form method="post" enctype="multipart/form-data">
              <div>
                <input
                  type="submit"
                  value=" <?php 
                    if ($value['status'] == 0) {
                      print '非公開 → 公開';
                    } else {
                      print '公開 → 非公開';
                    }
                  ?>"
                >
              </div>
              <input
                type="hidden"
                name="<?php print TOOL_UPD_STATUS?>"
                value=" <?php 
                  if ($value['status'] == 0) {
                    print '1';
                  } else {
                    print '0';
                  }
                ?>">
              <input type="hidden" name="<?php print TOOL_GET_ID?>" value="<?php print $value['drink_id']; ?>">
              <input type="hidden" name="<?php print TOOL_GET_NAME?>" value="<?php print $value['drink_name']; ?>">
              <input type="hidden" name="process_kind" value="update_status">
            </form>
          </td>
          
        </tr>
      <?php } ?>
      </table>

      <!-- エラーがあるときはエラー内容を表示 -->
      <?php
          // 2. DBに接続できたがデータを取得できていないとき
        } else {
          print '商品データがみつかりません。';
        }
      ?>
    </section>

  </body>
</html>