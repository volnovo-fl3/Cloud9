<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>オブジェクト指向02</title>
    </head>

    <body>
        <?php
        // Dogクラス定義
        class Dog {
            public $name;
            public $height;
            public $weight;
            
            // コンストラクタ
            function __construct($name, $height, $weight) {
                $this->name   = $name;
                $this->height = $height;
                $this->weight = $weight;
            }
            
            function show() {
                print "{$this->name}の身長は{$this->height}cm、体重は{$this->weight}kgです。<br>";
            }
        }
        
        // 太郎インスタンス
        $taro = new Dog('太郎', 100, 50);
        $taro->show();
        
        // 二郎インスタンス
        $jiro = new Dog('二郎', 90, 45);
        $jiro->show();
        
        ?>
    </body>
</html>