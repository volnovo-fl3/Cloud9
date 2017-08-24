<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>var_dump</title>
    </head>

    <body>
        <pre>
        <?php
        /* 色々な値の入った変数をvar_dumpしてみよう */
        
        // 数字の１
        $var1 = 1;
        print '■数字のvar_dump';
        print "\n";
        var_dump($var1);
        print "\n";
        
        // 文字列の１
        $var2 = '1';
        print '■文字列のvar_dump';
        print "\n";
        var_dump($var2);
        print "\n";
        
        // null
        $var3 = null;
        print '■nullのvar_dump';
        print "\n";
        var_dump($var3);
        print "\n";
        
        // boolean
        $var4 = true;
        print '■booleanのvar_dump';
        print "\n";
        var_dump($var4);
        print "\n";
        
        $var5 = -1;
        print '■int?のvar_dump';
        print "\n";
        var_dump($var5);
        print "\n";
        
        $var6 = date('Y');
        print '■int?のvar_dump'; //stringでしたー
        print "\n";
        var_dump($var6);
        print "\n";
        
        $var7 = mb_strlen('文字数は9文字です');
        print '■int?のvar_dump';
        print "\n";
        var_dump($var7);
        print "\n";
        
        ?>
        </pre>
    </body>
</html>