<?php
// 変数初期化
$gender = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['gender']) === TRUE ) {
        $gender = htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8');
    }
}

function entity($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>

    <body>
    </body>
</html>