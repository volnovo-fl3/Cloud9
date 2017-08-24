<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>オブジェクト指向01</title>
    </head>

    <body>
        <?php
        // Dogクラス定義
        class Dog {
            public $name;
            public $height;
            public $weight;
            
            function show() {
                print "{$this->name}の身長は{$this->height}cm、体重は{$this->weight}kgです。<br>";
            }
        }
        
        // 太郎インスタンス
        $taro = new Dog();
        $taro->name = '太郎';
        $taro->height = 100;
        $taro->weight = 50;
        $taro->show();
        
        // 二郎インスタンス
        $jiro = new Dog();
        $jiro->name = '二郎';
        $jiro->height = 90;
        $jiro->weight = 45;
        $jiro->show();
        
        ?>
    </body>
</html>