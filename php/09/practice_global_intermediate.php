<?php
// 変数初期化
$myhand = '';
$enemyhand = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['myhand']) === TRUE ) {
        // 自分の手はラジオボタンを使用
        $myhand = htmlspecialchars($_POST['myhand'], ENT_QUOTES, 'UTF-8');
        
        // ラジオボタンからのPOSTがある場合のみ、敵の手を設定
        $rand = mt_rand(0, 2);
        if ($rand == 0) {
            $enemyhand = 'グー';
        } else if ($rand == 1) {
            $enemyhand = 'チョキ';
        } else {
            $enemyhand = 'パー';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>スーパーグローバル変数 課題3</title>
    </head>

    <body>
        <h2>じゃんけん勝負</h2>
        <form method="post">
            <input type="radio" name="myhand" value="グー" <?php if ($myhand === 'グー') { print 'checked'; } ?>>グー
            <input type="radio" name="myhand" value="チョキ" <?php if ($myhand === 'チョキ') { print 'checked'; } ?>>チョキ
            <input type="radio" name="myhand" value="パー" <?php if ($myhand === 'パー') { print 'checked'; } ?>>パー
            <input type="submit" value="勝負！">
        </form>
        
        <?php if (isset($_POST['myhand']) === TRUE) { ?>
            <br>

            <?php
            print "自分: $myhand<br>";
            print "相手: $enemyhand<br>";
            print "相手: ";
            
            if ($myhand === 'グー') {
                if ($enemyhand === 'グー') {
                    print 'Draw';
                }
                else if ($enemyhand === 'チョキ') {
                    print 'Win!!';
                }
                else { //パー
                    print 'Lose...';
                }
            }
            
            else if ($myhand === 'チョキ') {
                if ($enemyhand === 'グー') {
                    print 'Lose...';
                }
                else if ($enemyhand === 'チョキ') {
                    print 'Draw';
                }
                else { //パー
                    print 'Win!!';
                }
            }

            else { // パー
                if ($enemyhand === 'グー') {
                    print 'Win!!';
                }
                else if ($enemyhand === 'チョキ') {
                    print 'Lose...';
                }
                else { //パー
                    print 'Draw';
                }
            }

            ?>
        <?php } ?>
    </body>
</html>