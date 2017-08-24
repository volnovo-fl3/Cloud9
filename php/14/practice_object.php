<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>オブジェクト指向 課題</title>
    </head>

    <body>
        <?php
        class Cat {
            public $name;
            public $height;
            public $weight;
            function show() {
                print "{$this->name}の身長は{$this->height}cm、体重は{$this->weight}kgです。<br>";
            }
        }
        
        // インスタンス
        $AntonioJr = new Cat();
        $AntonioJr->name = 'アントニオJr.';
        $AntonioJr->height = 30;
        $AntonioJr->weight = 4;
        $AntonioJr->show();
        
        ?>
    </body>
</html>