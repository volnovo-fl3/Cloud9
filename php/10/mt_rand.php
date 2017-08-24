<?php

// mt_rand()の引数を指定しない場合、0 から mt_getrandmax() の値を生成します
var_dump(mt_rand());
print "<br>";

// 指定の範囲内の値を取得したい場合は最小値と最大値を指定します
// この場合、10から50までの値を生成します
var_dump(mt_rand(10, 50));

?>