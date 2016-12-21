<?php
$last_time = time();
$numberOfElves = 5;
$numberOfElves = 3012210;
$elves = range(1,$numberOfElves);
$index = 0;
$countSteps = 0;
$count = $numberOfElves;

while($count > 1) {
    if($index > $count) {
        $index = 0;
    }
    $opposite_index = (floor($count / 2) + $index) % $count;
    unset($elves[$opposite_index]);
    $elves = array_values($elves);
    $count--;
    $index++;

    $countSteps++;
    if($countSteps % 100 == 0) {
        $now = time();
        $diff = $now - $last_time;
        $last_time = $now;
        echo "$countSteps time: $diff \n";
    }
}
print_r($elves);