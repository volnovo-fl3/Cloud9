<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print SITE_NAME ?> | マイページ</title>
    <link rel="shortcut icon" href="font_icon/favicon.ico">
    <link rel="stylesheet" href="css/html5reset-1.6.1.css">
    <link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/show_me.css">
    <link type="text/css" rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/cupertino/jquery-ui.min.css" />
    <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  </head>

  <body class="wf-notosansjapanese Background_Color_default">
    
    <!-- 上段 -->
    <header>
      <div class="contents_line">
        <div class="container flexbox">
          <div class="flex_2 position_set">
            <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/mypage.php"><img src="font_icon/logo02.png" class="logo02_image_size"></a>
          </div>
          <div class="user_name_image flex_1">
            
            <div class="flexbox position_set">
              <div class="user_img">
                <img src="<?php print IMAGE_DIRECTORY . $header_user_img; ?>" class="image_size_to_panel_radius"></img>
              </div>
              
            <?php
            if((mb_strlen($header_user_name) > 0) && (mb_strlen($header_user_img) > 0)) {
            ?>
              <div class="block_center_height">
                <div>
                  <p><?php print $header_user_name?> さん</p>
                  <p class="logout"><a href="https://codeincubate-tanakanoboru.c9users.io/show_me/logout.php">ログアウト</a></p>
                </div>
              </div>
            <?php
            }
            ?>
            
            </div>
          </div>
        </div>
      </div>
      <div class="contents_line Background_Color_white">
        <div class="container">
          <ul class="flexbox_header">
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/mypage.php"><p>マイページ</p></a>
            </li>
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/product_list.php"><p>制作リスト</p></a>
            </li>
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/item_list.php?page_type=all_list"><p>課題を探す</p></a>
            </li>
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/item_info.php"><p>出品する</p></a>
            </li>
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/item_list.php?page_type=seller_list"><p>出品一覧</p></a>
            </li>
            <li class="headerlist">
              <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/cart_list.php"><p>カートを確認</p></a>
            </li>
            
          </ul>
        </div>
      </div>
    </header>
    
    
    <!-- 中段 -->
    <main class="Background_Color_white">
      <div class="container flexbox">
      
        <!-- 左側 -->
        <div class="flex_1">
          <div class="user_profiles_panel_back">
            <div class="user_profiles_panel">
              <div class="image_panel_200 block_center_width">
                <img src="<?php print IMAGE_DIRECTORY . $user_img; ?>" class="image_size_to_panel_radius"></img>
              </div>
              <p class="inline_center_width"><?php print $user[0]['user_name'] ?> さん</p>
              <?php
                if (mb_strlen($user[0]['user_affiliation']) > 0){
              ?>
              <p class="inline_center_width"><?php print $user[0]['user_affiliation'] ?></p>
              <?php
                }
              ?>
              
              <hr>
              <h3>対応カテゴリ</h3>
              <p class="name_list"><?php print entity_str(str_is_regist(implode(' / ', $categories_name_list))) ?></p>
              
              <h3>使用ソフト</h3>
              <p class="name_list"><?php print entity_str(str_is_regist(implode(' / ', $skills_name_list))) ?></p>

              <form action='user_profile.php'>
                <div class="add_row">
                  <input type="hidden" name="process_kind" value="to_user_profile">
                  <input type="hidden" name="target_user_id" value="<?php print $user_id?>">
                  <input type="submit" class="display_block block_center_width" value="登録情報確認">
                </div>
              </form>
            </div>
          </div>
          
        </div>
        
        <!-- 右側 -->
        <div class="flex_3">
          <?php
            if((isset($carts_unpaid) === TRUE) && (count($carts_unpaid) > 0)){
          ?>
          <div class='add_row message_box'>
            <a href="https://codeincubate-tanakanoboru.c9users.io/show_me/cart_list.php">
              <p class="Text_Color_red">カートに <?php print entity_str($carts_unpaid[0]['cart_sum_amount']); ?> 点の商品があります。</p>
            </a>
          </div>
          <?php
            }
          ?>
          
          <div class="panel_board">
            <h3>あなたの作品</h3>
            
            <?php
              if(count($user_products) > 0){
            ?>
            <p class="link"><a href="product_list.php">→ 制作リストへ</a></p>
            <div class="panel_list">
            <?php
                foreach($user_products as $key => $product){
            ?>
              <div class="item_panel" style="background-color:<?php print $product['category_color']?>">
                <a href="product_details.php?target_product_id=<?php print $product['product_id']?>">
                  <img src="<?php print IMAGE_DIRECTORY . image_link($product['product_img']); ?>"></img>
                  <p><?php print $product['item_name']?> / <?php print product_status_int_to_str($product['product_status'])?></p>
                </a>
              </div>
            <?php
                }
            ?>
            </div>
            <?php
              } else {
            ?>
            <p>作品が登録されていません。</p>
            <?php
              }
            ?>
          </div>
          
          <div class="add_row panel_board">
            <h3>あなたにおすすめの商品</h3>
            
            <?php
              if(count($recommended_items) > 0){
            ?>
            <p class="link">
              <a href="<?php print $url_recommended_items_list?>">→ もっと見る</a>
            </p>
            <div class="panel_list">
            <?php
                foreach($recommended_items as $key => $item){
            ?>
              <div class="item_panel" style="background-color:<?php print $item['category_color']?>">
                <a href="item_details.php?target_item_id=<?php print $item['item_id']?>">
                  <img src="<?php print IMAGE_DIRECTORY . image_link($item['item_img']); ?>"></img>
                  <p><?php print $item['item_name']?></p>
                </a>
              </div>
            <?php
                }
            ?>
            </div>
            <?php
              } else {
            ?>
            <p>おすすめの商品を表示するために、対応カテゴリ・使用ソフトを登録してください。</p>
            <?php
              }
            ?>
          </div>
          
          <div class="add_row panel_board">
            <h3>あなたにおすすめの書籍</h3>
            <div class="add_row">
              <input
                id="books_search_word"
                type="text"
                value="<?php
                  $array = [];
                  if((count($categories_name_list) > 0)&&(count($skills_name_list) > 0)){
                    $array = array_merge($categories_name_list, $skills_name_list);
                  } else {
                    $array = $array_for_autocomplete;
                  }
                  print $array[array_rand($array)];
                ?>"/>
              <input id="search" type="button" value="検索"/>
              <p id="search_message">キーワードを入れ、検索してください。</p>
            </div>
            <div id="books_list" class="panel_list">
            </div>
          </div>
          
          
          <!-- ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ ▼ -->
          <!-- ▼ ▼ ▼ ▼ ▼ ▼  ここから javascript  ▼ ▼ ▼ ▼ ▼ ▼ -->
          <script>
            //-------------------------------------------------
            // オートコンプリート
            //-------------------------------------------------
            //JSON.parseを使って配列を受け取る
            var autocomplete = JSON.parse('<?php echo json_encode($array_for_autocomplete); ?>');
            
            $(document).ready(function() {
             $("#books_search_word").autocomplete({
              source:autocomplete,
              minLength: 2,
             })
            });
            
            
            //-------------------------------------------------
            // GoogleBooks から検索して表示
            //-------------------------------------------------
            function search_books(){
              var search_word = '';
              search_word = document.getElementById("books_search_word").value;
              
              if(search_word.replace(/\s+/g, "").length > 0){
                
                // APIの呼び出し・取得データの表示
                var url = "https://www.googleapis.com/books/v1/volumes";
                
                jQuery.getJSON(
                  url,
                  {
                    q:search_word,
                  },
                  function(json) {
                    if(json.items){
                      
                      document.getElementById("search_message").innerHTML = "";
                      $("#search_message").append('<a href=https://www.google.co.jp/search?tbm=bks&q=' + encodeURIComponent(search_word) + '>Googleブックスで『' + search_word + '』を検索</a>');
                      document.getElementById("search_message").className = "link";
                
                      // 初期化！！
                      document.getElementById("books_list").innerHTML = "";
                      
                      var books = 0;
                      for(var i in json.items){
                        
                        if(books > 3){
                          break;
                        }
                        
                        // 日本出版で販売中のもののみ
                        if(json.items[i].saleInfo !== undefined){
                          
                          if((json.items[i].volumeInfo.language !== undefined) && (json.items[i].saleInfo.saleability !== undefined)){
                            if(json.items[i].volumeInfo.language === "ja"){
                              
                              $("#books_list").append('<div id="book_' + i + '" class="item_panel"></div>');
                              
                              // リンク
                              if(json.items[i].volumeInfo.infoLink !== undefined){
                                $("#book_" + i).append('<a id="book_link_' + i + '" href=' + json.items[i].volumeInfo.infoLink + '></a>');
                              } else {
                                $("#book_" + i).append('<a id="book_link_' + i + '" href=https://books.google.co.jp/></a>');
                              }
                              
                              // 画像
                              if(
                                  (json.items[i].volumeInfo.imageLinks !== undefined)
                                  && (json.items[i].volumeInfo.imageLinks.thumbnail !== undefined)
                                )
                              {
                                $("#book_link_" + i).append('<img src=' + json.items[i].volumeInfo.imageLinks.thumbnail + '></img>');
                              } else {
                                $("#book_link_" + i).append('<img src=https://books.google.co.jp/googlebooks/images/no_cover_thumb.gif></img>');
                              }
                              
                              // 書籍名 + 著者
                              var book_name = '';
                              if(json.items[i].volumeInfo.title !== undefined){
                                book_name = json.items[i].volumeInfo.title;
                              } else {
                                book_name = '(タイトル情報なし)';
                              }
                              if(json.items[i].volumeInfo.authors !== undefined){
                                book_name = book_name + '<br> / ' + json.items[i].volumeInfo.authors.join(" , ");
                              } else {
                                book_name = book_name + '<br> / (著者情報なし)';
                              }
                              $("#book_link_" + i).append('<p>' + book_name + '</p>');
                              
                              books = books + 1;
                              
                            }
                          }
                          

                        }
                      }
                    } else{
                      document.getElementById("search_message").innerHTML = "";
                      $("#search_message").text('検索結果は 0 件です。');
                    }
                  }
                );
      
              } else {
                document.getElementById("search_message").innerHTML = "";
                $("#search_message").text('データを取得できませんでした。');
              }
            }
            
            // 画面を表示したタイミングで動かす
            $(function() {
              search_books();
            });
            
            // 検索ボタンをクリックした場合の処理
            $('#search').click(function() {
              search_books();
            });
            
          </script>
          <!-- ▲ ▲ ▲ ▲ ▲ ▲  ここまで javascript  ▲ ▲ ▲ ▲ ▲ ▲ -->
          <!-- ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ ▲ -->
          
          
        </div>
        
      </div>
    </main>    
    

    <!-- 下段 -->
    <footer>
      <div class="container">
         <ul class="flexbox_footer">
          <li class="footerlist">
            <a href="#" target="_blank"><p>サイトマップ</p></a>
          </li>
          <li class="footerlist">
            <a href="#" target="_blank"><p>プライバシーポリシー</p></a>
          </li>
          <li class="footerlist">
            <a href="#" target="_blank"><p>お問い合わせ</p></a>
          </li>
          <li class="footerlist">
            <a href="#" target="_blank"><p>ご利用ガイド</p></a>
          </li>
        </ul>
        <p><small>Copyright &copy; Show me! All Rights Reserved.</small></p>
      </div>
    </footer>
    
  </body>
</html>