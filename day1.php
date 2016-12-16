<?php


$turns = "L1, L5, R1, R3, L4, L5, R5, R1, L2, L2, L3, R4, L2, R3, R1, L2, R5, R3, L4, R4, L3, R3, R3, L2, R1, L3, R2, L1, R4, L2, R4, L4, R5, L3, R1, R1, L1, L3, L2, R1, R3, R2, L1, R4, L4, R2, L189, L4, R5, R3, L1, R47, R4, R1, R3, L3, L3, L2, R70, L1, R4, R185, R5, L4, L5, R4, L1, L4, R5, L3, R2, R3, L5, L3, R5, L1, R5, L4, R1, R2, L2, L5, L2, R4, L3, R5, R1, L5, L4, L3, R4, L3, L4, L1, L5, L5, R5, L5, L2, L1, L2, L4, L1, L2, R3, R1, R1, L2, L5, R2, L3, L5, L4, L2, L1, L2, R3, L1, L4, R3, R3, L2, R5, L1, L3, L3, L3, L5, R5, R1, R2, L3, L2, R4, R1, R1, R3, R4, R3, L3, R3, L5, R2, L2, R4, R5, L4, L3, L1, L5, L1, R1, R2, L1, R3, R4, R5, R2, R3, L2, L1, L5";
$directions = explode(',',$turns);

$clean_directions = [];
foreach($directions as $turn) {
    $clean_directions[] = [
        'direction' => substr(trim($turn),0,1),
        'moves' => substr(trim($turn),1),
    ];
}

$facing = [ 'north', 'east', 'south', 'west' ];
$currentFacing = 0;
$cords = [
    'x' => 0,
    'y' => 0,
];
$all_cords = [];

foreach($clean_directions as $direction) {
    $currentFacing = changeFacing($currentFacing,$direction['direction']);
    list($cords,$all_cords) = updateCords($currentFacing,$direction['moves'],$cords,$all_cords);
}
echo "cords now x: {$cords['x']} y: {$cords['y']}<br>";

function changeFacing($currentFacing,$turnDirection)
{
    if($turnDirection == 'R') {
        $currentFacing++;
    } else {
        $currentFacing--;
    }
    if($currentFacing == -1)  $currentFacing = 3;
    if($currentFacing == 4) $currentFacing = 0;

    return $currentFacing;
}

function updateCords($direction,$moves,$cords,$all_cords) {
    $add_cords = [];
    switch($direction) {
        case 0:
            for($x=0;$x<$moves;$x++) {
                $add_cords[] = [
                    'x' => $cords['x'],
                    'y' => ($cords['y'] + 1),
                ];
                $cords['y'] += 1;
            }
            break;
        case 1:
            for($x=0;$x<$moves;$x++) {
                $add_cords[] = [
                    'x' => ($cords['x'] + 1),
                    'y' => $cords['y'],
                ];
                $cords['x'] += 1;
            }
            break;
        case 2:
            for($x=0;$x<$moves;$x++) {
                $add_cords[] = [
                    'x' => $cords['x'],
                    'y' => ($cords['y'] - 1),
                ];
                $cords['y'] -= 1;
            }
            break;
        case 3:
            for($x=0;$x<$moves;$x++) {
                $add_cords[] = [
                    'x' => ($cords['x'] - 1),
                    'y' => $cords['y'],
                ];
                $cords['x'] -= 1;
            }
            break;
    }

    foreach($add_cords as $currentCords) {
        $key = implode(',',$currentCords);
        if(in_array($key,$all_cords)) {
            echo "already been here<br>";
            echo "cords now x: {$currentCords['x']} y: {$currentCords['y']}<br>";
            die();
        }
        $all_cords[] = $key;
    }

    return [$cords,$all_cords];
}