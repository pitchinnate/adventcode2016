<?php

include "day3_triangles.php";

$allTriangles = explode("\n",$triangles);
$goodTriangles = 0;
$allSides = [0=>[],1=>[],2=>[]];

foreach($allTriangles as $triangle) {
    $sides = array_values(array_filter(explode(" ",$triangle)));
    $allSides[0][] = $sides[0];
    $allSides[1][] = $sides[1];
    $allSides[2][] = $sides[2];
}
$allSidesTotal = array_merge($allSides[0],$allSides[1],$allSides[2]);
while(count($allSidesTotal) > 0) {
    $sides = [array_shift($allSidesTotal),array_shift($allSidesTotal),array_shift($allSidesTotal)];
    if(isTriangle($sides)) {
        $goodTriangles++;
    }
}

echo "good triangles: $goodTriangles";

function isTriangle($sides)
{
    if(($sides[0] + $sides[1]) <= $sides[2]) {
        return false;
    }
    if(($sides[0] + $sides[2]) <= $sides[1]) {
        return false;
    }
    if(($sides[1] + $sides[2]) <= $sides[0]) {
        return false;
    }
    return true;
}