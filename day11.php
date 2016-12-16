<html>
<body>
<pre>

<?php
$floors = [
    1 => [
        'G' => ['Po','Th','Pr','Ru','Co','El','Di'],
        'M' => ['Th','Ru','Co','El','Di'],
    ],
    2 => [
        'G' => [],
        'M' => ['Po','Pr'],
    ],
    3 => ['G' => [],'M' => []],
    4 => ['G' => [],'M' => []],
];
$elevator = [];
$currentFloor = 1;
$moves = [];
$currentState = $floors;

moveItems($floors,1,$elevator,0);

global $completes;
$completes = 0;

function moveItems($floors,$currentFloor,$elevator,$moves)
{
    global $completes;
    if(count($elevator) > 0) {
        $floors = unloadElevator($elevator, $floors, $currentFloor);
    }
    $currentElements = getFloorElements($floors[4]);
    if(count($currentElements) == 14) {
        echo "COMPLETE!!! Took $moves moves \n";
        displayFloors($floors);
        $completes++;
        if($completes > 100) {
            echo "Reached 100 complete \n";
            die();
        }
    }
    if($moves < 100) {
        if ($currentFloor < 4) {
            findMovesUp($floors, $currentFloor, $moves);
        } else {
            findMovesDown($floors, $currentFloor, $moves);
        }
    }
}

function unloadElevator($elevator,$floors,$currentFloor)
{
    foreach($elevator as $type => $elements) {
        foreach($elements as $element) {
            $floors[$currentFloor][$type][] = $element;
        }
    }
    return $floors;
}

function findMovesDown($floors,$currentFloor,$moves)
{
    $currentElements = getFloorElements($floors[$currentFloor]);
    foreach ($currentElements as $element) {
        $newFloor = $floors[$currentFloor];
        list($element1, $type1) = explode('-', $element);
        $key1 = array_search($element1, $newFloor[$type1]);
        $elevator = ['G' => [], 'M' => []];
        $elevator[$type1][] = $newFloor[$type1][$key1];
        unset($newFloor[$type1][$key1]);
        if (!isset($newFloor['M'])) {
            $newFloor['M'] = [];
        }
        if (!isset($newFloor['G'])) {
            $newFloor['G'] = [];
        }
        if (validFloor($newFloor)) {
            $copyFloors = $floors;
            $copyFloors[$currentFloor] = $newFloor;
            moveItems($copyFloors, ($currentFloor - 1), $elevator, ($moves + 1));
        }
    }
}

function findMovesUp($floors,$currentFloor,$moves)
{
    $movingDown = false;
    if($currentFloor > 1) {
        $previousFloorElements = getFloorElements($floors[($currentFloor-1)]);
        if(count($previousFloorElements) > 0) {
            findMovesDown($floors,$currentFloor,$moves);
            $movingDown = true;
        }
    }
    if($movingDown === false) {
        $currentElements = getFloorElements($floors[$currentFloor]);
        $combos = findCombos($currentElements);
        foreach ($combos as $combo) {
            $newFloor = $floors[$currentFloor];
            list($element1, $type1) = explode('-', $combo[0]);
            list($element2, $type2) = explode('-', $combo[1]);
            $key1 = array_search($element1, $newFloor[$type1]);
            $key2 = array_search($element2, $newFloor[$type2]);
            $elevator = ['G' => [], 'M' => []];
            $elevator[$type1][] = $newFloor[$type1][$key1];
            $elevator[$type2][] = $newFloor[$type2][$key2];
            unset($newFloor[$type1][$key1]);
            unset($newFloor[$type2][$key2]);
            if (!isset($newFloor['M'])) {
                $newFloor['M'] = [];
            }
            if (!isset($newFloor['G'])) {
                $newFloor['G'] = [];
            }
            if (validFloor($newFloor)) {
                $copyFloors = $floors;
                $copyFloors[$currentFloor] = $newFloor;
                moveItems($copyFloors, ($currentFloor + 1), $elevator, ($moves + 1));
            }
        }
    }
}

function getFloorElements($floor)
{
    $currentElements = [];
    foreach($floor as $type => $elements) {
        foreach ($elements as $element) {
            $currentElements[] = "{$element}-{$type}";
        }
    }
    return $currentElements;
}

function displayFloors($floors)
{
    foreach($floors as $floor) {
        displayFloor($floor);
    }
    echo "\n";
}

function displayFloor($floor)
{
    foreach($floor as $type => $elements) {
        foreach($elements as $element) {
            echo "{$element}{$type} ";
        }
    }
    echo "\n";
}

function comboValid($item1,$item2)
{
    list($element1,$type1) = explode('-',$item1);
    list($element2,$type2) = explode('-',$item2);
    if($type1 == $type2 || $element1 == $element2) {
        return true;
    }
    return false;
}

function validFloor($floor)
{
    $emptyGenerators = [];
    $emptyModules = [];
    foreach($floor['G'] as $element) {
        if(array_search($element,$floor['M']) === false) {
            $emptyGenerators[] = $element;
        }
    }
    foreach($floor['M'] as $element) {
        if(array_search($element,$floor['G']) === false) {
            $emptyModules[] = $element;
        }
    }
    if(count($emptyGenerators) == 0 || count($emptyModules) == 0) {
        return true;
    }
    return false;
}

function findCombos($array)
{
    $length = count($array);
    $possibilities = [];
    for($x=0;$x<($length-1);$x++) {
        for($y=($x+1);$y<$length;$y++) {
            if(comboValid($array[$x],$array[$y])) {
                $possibilities[] = [$array[$x], $array[$y]];
            }
        }
    }
    return $possibilities;
}