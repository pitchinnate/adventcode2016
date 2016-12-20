<html>
<body>
<pre>

<?php

$input = 'edjrjqaa';
findPath($input,1,1,4,4,0);

function findPath($path,$xPos,$yPos,$sizeX,$sizeY,$count,$direction=null)
{
    //echo "path: $path xpos: $xPos ypos: $yPos count: $count direction: $direction \n";
    if(!is_null($direction)) {
        $path .= $direction;
    }
    $findMore = true;
    if($xPos == 4 && $yPos == 4) {
        echo "I'm in the right spot, moves: $count path: $path \n\n";
        $findMore = false;
    }
    if($count > 12) {
        echo "killed to many moves: $count path $path \n\n";
        $findMore = false;
    }
    if($findMore) {
        $directions = openDoors($xPos,$yPos,$path,$sizeX,$sizeY);
        foreach ($directions as $key => $direction) {
            if($direction !== false) {
                findPath($path,$direction['x'],$direction['y'],$sizeX,$sizeY,($count + 1),$key);
            }
        }
    }
}

function openDoors($xPos,$yPos,$path,$sizeX,$sizeY)
{
    $hashed = md5($path);
    $letters = str_split($hashed);
    $directions = ['U','D','L','R'];
    $returnedDirections = [];
    for($x=0;$x<4;$x++) {
        $returnedDirections[$directions[$x]] = checkDoor($letters[$x],$xPos,$yPos,$directions[$x],$sizeX,$sizeY);
    }
    return $returnedDirections;
}

function checkDoor($letter,$xPos,$yPos,$direction,$sizeX,$sizeY)
{
    if($direction == 'U') $yPos = $yPos - 1;
    if($direction == 'D') $yPos = $yPos + 1;
    if($direction == 'L') $xPos = $xPos - 1;
    if($direction == 'R') $xPos = $xPos + 1;

    if($xPos <= 0 || $xPos > $sizeX || $yPos <= 0 || $yPos > $sizeY) {
        return false;
    }
    if(is_numeric($letter) || $letter == 'a') {
        return false;
    }
    return ['x' => $xPos, 'y' => $yPos];
}
