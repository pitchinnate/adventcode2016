<?php
set_time_limit(0);

$input = "reyedfim";

$counter = 0;
$password = [];

while(count($password) < 8) {
    $str = "{$input}{$counter}";
    $md5 = md5($str);
    if(substr($md5,0,5) === '00000') {
        $index = substr($md5,5,1);
        if(is_numeric($index) && $index < 8) {
            if(!isset($password[$index])) {
                $password[$index] = substr($md5,6,1);
                var_dump($password);
                sleep(1);
                ob_flush();
                flush();
            }
        }
    }
    $counter++;
}
ksort($password);
var_dump($password);
echo "password: " . implode('',$password);