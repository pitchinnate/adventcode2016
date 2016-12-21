<?php

include('day20_input.php');
//$input = "5-8
//0-2
//4-7";

$maxIp = 4294967295;
//$maxIp = 10;

$minIp = 0;

$lines = explode("\n",$input);
$blacklist = [];
foreach($lines as $line) {
    $pieces = explode('-',$line);
    $blacklist[$pieces[0]] = $pieces[1];
}
ksort($blacklist);

foreach($blacklist as $min => $max) {
    if($minIp >= $min && $minIp <= $max) {
        $minIp = $max + 1;
    }
}
echo $minIp;