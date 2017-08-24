<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>条件分岐 課題1</title>
    </head>

    <body>
        <h2>何が出るかな！</h2>
        <ol>
            <li>『夢の直行便』 / 大分空港 → 千歳へ</li>
            <li>『地獄の深夜バス「ぶんご号」』 / 名古屋へ</li>
            <li>『一回休み』 / 別府温泉へ</li>
            <li>『ふりだしに戻る』 / 寝台特急「富士」 東京へ</li>
            <li>『まだまだ九州』 / 小倉へ</li>
            <li>『たっぷり九州』 / 鹿児島へ</li>
        </ol>
        <br>
        <br>
        
        <?php
        $dice = mt_rand(1, 6);
        print "出た数字：$dice";
        print "\n";

        if ($dice % 2 == 1){
            print '奇数';
        } else {
            print '偶数';
        }
        print "\n";
        ?>
        
        <p>行き先は...</p>
        <?php if ($dice == 1){ ?>
            <h3>『夢の直行便』<h3>
            <h3> 大分空港 → 千歳へ！</h3>
        <?php } else if ($dice == 2){ ?>
            <h3>『地獄の深夜バス「ぶんご号」』<h3>
            <h3> 名古屋へ！</h3>
        <?php } else if ($dice == 3){ ?>
            <h3>『一回休み』<h3>
            <h3> 別府温泉へ！</h3>
        <?php } else if ($dice == 4){ ?>
            <h3>『ふりだしに戻る』<h3>
            <h3> 寝台特急「富士」　東京へ！</h3>
        <?php } else if ($dice == 5){ ?>
            <h3>『まだまだ九州』<h3>
            <h3> 小倉へ！</h3>
        <?php } else if ($dice == 6){ ?>
            <h3>『たっぷり九州』<h3>
            <h3> 鹿児島へ！</h3>
        <?php } ?>
    </body>
</html>