<html>
<body>
<pre>

<?php
const SAFE = '.';
const TRAP = '^';

$grid = [
    1 => '.^^^.^.^^^.^.......^^.^^^^.^^^^..^^^^^.^.^^^..^^.^.^^..^.^..^^...^.^^.^^^...^^.^.^^^..^^^^.....^....',
];
$max_levels = 400000;
$currentLevel = 1;

while($currentLevel < $max_levels) {
    $lastRow = $grid[$currentLevel];
    $currentLevel++;
    $grid[$currentLevel] = createNewRow($lastRow);
}
$allTraps = implode('',$grid);
echo substr_count($allTraps,'.');

function createNewRow($lastRow)
{
    $letters = str_split($lastRow);
    array_unshift($letters,'.');
    array_push($letters,'.');
    $letterCount = count($letters) - 1;
    $newRow = "";
    for($x=1;$x<$letterCount;$x++) {
        $newRow .= checkTrap($letters[($x - 1)],$letters[$x],$letters[($x + 1)]);
    }
    return $newRow;
}

function checkTrap($firstLetter,$secondLetter,$thirdLetter)
{
    if($firstLetter == TRAP && $secondLetter == TRAP && $thirdLetter == SAFE) {
        return TRAP;
    }
    if($firstLetter == SAFE && $secondLetter == TRAP && $thirdLetter == TRAP) {
        return TRAP;
    }
    if($firstLetter == TRAP && $secondLetter == SAFE && $thirdLetter == SAFE) {
        return TRAP;
    }
    if($firstLetter == SAFE && $secondLetter == SAFE && $thirdLetter == TRAP) {
        return TRAP;
    }
    return SAFE;
}
