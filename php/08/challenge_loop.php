<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>繰り返し 課題3</title>
    </head>

    <body>
    <?php
    for ($i = 1; $i <= 100; $i++ ) {
        print $i.': ';
        if ($i % 3 == 0 && $i % 5 == 0){
            print 'FizzBuzz';
        }
        else if ($i % 3 == 0){
            print 'Fizz';
        }
        else if ($i % 5 == 0){
            print 'Buzz';
        }
        else{
            print $i;
        }
        print "<br>";
    }
    ?>
    </body>
</html>