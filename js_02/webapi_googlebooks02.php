<!DOCTYPE html>

<!-- GoogleBooksAPI でキーワード検索して書籍データを取得する -->
<!-- サジェスト機能(jQuery UI)追加 -->

<?php
header('Content-Type: text/html; charset=UTF-8');

  $test_array = [];
  $jsonTest = [];
  
  $test_array = array(
    'VectorWorks'
    ,'AutoCAD Design Suite'
    ,'Shade'
    ,'3dsMAX'
    ,'Jw_cad'
    ,'auto cad'
    ,'Maya'
    ,'Softimage'
    ,'Cinema 4D'
    ,'LightWave'
    ,'modo'
    ,'InDesign'
    ,'QuarkXPress'
    ,'Coda2'
    ,'Dreamweaver'
    ,'Illustrator'
    ,'Photoshop'
    ,'AfterEffects'
    ,'Premiere'
    ,'FinalCut'
    ,'Edius'
    ,'Logic'
    ,'FL Studio'
    ,'SONAR'
    ,'Ableton Live'
    ,'Digital Perfomer'
    ,'ACID'
  );
  
  //var_dump($test_array);
  
?>

<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>GoogleBooks連携(jQuery UI) テスト</title>
    <link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/cupertino/jquery-ui.min.css" />
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  </head>

  <body>
    <form>
      <label>書籍検索</label>
      <br>
      <input id="search_word" type="text" value="" />
      <input id="search" type="button" value="GoogleBooks 検索" />
    </form>
    <hr>
    <p id="search_message">キーワードを入れ、検索してください。</p>
    <div id="output">
    </div>
    
    <?php
      $jsonTest=json_encode($test_array);
    ?>
    <script>
      
      //JSON.parseを使って配列を受け取る
      var test=JSON.parse('<?php echo $jsonTest; ?>');
      /*
      // (受け取れたか確認用)
      for (var i = 0; i < test.length; i++) {
        $("#output").append('<p>' + test[i] + '</p>');
      }
      */
      
      $(document).ready(function() {
       $("#search_word").autocomplete({
        source:test,
       })
      });
      

      function search_books(){
        var search_word = '';
        search_word = document.getElementById("search_word").value;
        
        if(search_word.replace(/\s+/g, "").length > 0){
          $("#search_message").text('『' + search_word + '』で検索');
          //$("#output").append("<p>https://www.googleapis.com/books/v1/volumes?q=" + search_message + "</p>");
          
          // APIの呼び出し・取得データの表示
          var url = "https://www.googleapis.com/books/v1/volumes";
          
          jQuery.getJSON(
            url,
            {
              q:search_word,
            },
            function(json) {
              if(json.items){
                // 出力する前に初期化
                document.getElementById("output").innerHTML = "";
                
                $("#output").append("<p>" + json.totalItems + " 件の書籍が見つかりました。</p>");
                
                $("#output").append('<ol id="bookslist"></ol>');
                
                // 10件取得
                for(var i =0; i < 10; i++){
                  // 本のタイトル"
                  $("#bookslist").append("<li>" + json.items[i].volumeInfo.title + "</li>");
                  if(
                      (json.items[i].volumeInfo.imageLinks !== undefined)
                      && (json.items[i].volumeInfo.imageLinks.thumbnail !== undefined)
                    )
                  {
                    $("#bookslist").append("<img src=" + json.items[i].volumeInfo.imageLinks.thumbnail + "></img>");
                  } else {
                    $("#bookslist").append('<img src=https://books.google.co.jp/googlebooks/images/no_cover_thumb.gif></img>');
                  }
                  $("#bookslist").append('<ul id="book_' + i + '"></ul>');
                  
                  // 著者
                  if(json.items[i].volumeInfo.authors !== undefined){
                    $("#book_" + i).append("<li>著者：" + json.items[i].volumeInfo.authors.join(" , ") + "</li>");
                  } else {
                    $("#book_" + i).append("<li>著者：(情報なし)</li>");
                  }
                  
                  // リンク
                  if(json.items[i].volumeInfo.infoLink !== undefined){
                    $("#book_" + i).append("<li>リンク：<a href=" + json.items[i].volumeInfo.infoLink + ">『" + json.items[i].volumeInfo.title + "』をGoogleBooksで見る</a></li>");
                  } else {
                    $("#book_" + i).append("<li>リンク：(情報なし)</li>");
                  }
                }
              } else{
                document.getElementById("output").innerHTML = "検索結果は 0 件です。";
              }
            }
          );

        } else {
          $("#search_message").text('検索ワードを入力してください。');
        }
      }
      
      
      $(function() {
        search_books();
      });
      
      // 検索ボタンをクリックした場合の処理
      $('#search').click(function() {
        search_books();
      });
      

    </script>
    
  </body>
</html>