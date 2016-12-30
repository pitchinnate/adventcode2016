<?php
$numberOfElves = 30120;
$elves = range(1,$numberOfElves);

$last_time = microtime(true);
array_splice($elves,2,1);
$now = microtime(true);
$diff = $now - $last_time;
echo "time: $diff \n";

$last_time = microtime(true);
array_splice($elves,30000,1);
$now = microtime(true);
$diff = $now - $last_time;
$last_time = $now;
echo "time: $diff \n";