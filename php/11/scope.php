<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>スコープ</title>
    </head>

    <body>
        <?php
        $str = 'スコープテスト';
        
        /*
        function test_scope() {
            print $str; // 関数内の変数を参照
        }
        */
        
        /*
        function test_scope() {
            global $str;
            print $str; // 関数内の変数を参照
        }
        */
        
        function test_scope() {
            print $_SERVER['REQUEST_METHOD']; // スーパーグローバル変数を参照
        }
        
        test_scope();
        ?>
    </body>
</html>