<?php
$numberOfElves = 3012210;
$elves = range(1,$numberOfElves);

$last_time = microtime(true);
array_splice($elves,2,1);
$now = microtime(true);
$diff = $now - $last_time;
$last_time = $now;
echo "time: $diff \n";

array_splice($elves,3000000,1);
$now = microtime(true);
$diff = $now - $last_time;
$last_time = $now;
echo "time: $diff \n";