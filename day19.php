<?php
$last_time = time();
$numberOfElves = 3012210;
$elves = range(1,$numberOfElves);

while(count($elves) > 1) {
    $elves = reduceElves($elves);
}
print_r($elves);

function reduceElves($elves)
{
    $count = count($elves);
    $newElves = [];
    if($count % 2 == 0) {
        $min = 0;
    } else {
        $min = 2;
    }
    for($x=$min;$x<$count;$x+=2) {
        $newElves[] = $elves[$x];
    }
    return $newElves;
}