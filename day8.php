<html>
    <body>
        <pre>
<?php

include("day8_input.php");
//$input = "rect 3x2
//rotate column x=1 by 1
//rotate row y=0 by 4
//rotate column x=1 by 1";

$sizeX = 50;
$sizeY = 6;

$instructions = explode("\n",$input);
$grid = [];
for($y=0;$y<$sizeY;$y++) {
    $grid[$y] = [];
    for($x=0;$x<$sizeX;$x++) {
        $grid[$y][$x] = '-';
    }
}

foreach($instructions as $instruction) {
    echo "<hr> $instruction \n\n";
    $words = explode(' ',$instruction);
    $command = array_shift($words);
    if($command == 'rotate') {
        $grid = rotateGrid($words,$grid,$sizeX,$sizeY);
    }
    if($command == 'rect') {
        $grid = rectGrid($words,$grid);
    }
}

function displayGrid($grid)
{
    $pixelsOn = 0;
    foreach($grid as $key => $y) {
        foreach ($y as $x) {
            echo $x . ' ';
            if($x == '0') {
                $pixelsOn++;
            }
        }
        echo "\n";
    }
    echo "\nPixels On: $pixelsOn \n\n";
}



function rectGrid($words,$grid)
{
    $pieces = explode('x',$words[0]);
    for($y=0;$y<$pieces[1];$y++) {
        for($x=0;$x<$pieces[0];$x++) {
            $grid[$y][$x] = "0";
        }
    }
    return $grid;
}

function rotateGrid($words,$grid,$sizeX,$sizeY)
{
    $pieces = explode('=',$words[1]);
    $index = $pieces[1];

    if($words[0] == 'row') {
        for($count=0;$count<$words[3];$count++) {
            $grid = rotateRow($index,$grid,$sizeX,$sizeY);
            displayGrid($grid);
        }
    } else {
        for($count=0;$count<$words[3];$count++) {
            $grid = rotateColumn($index,$grid,$sizeX,$sizeY);
            displayGrid($grid);
        }
    }

    return $grid;
}

function rotateColumn($index,$grid,$sizeX,$sizeY)
{
    $values = [];
    for($y=0;$y<$sizeY;$y++) {
        $values[] = $grid[$y][$index];
    }
    $last_value = array_pop($values);
    array_unshift($values,$last_value);
    for($y=0;$y<$sizeY;$y++) {
        $grid[$y][$index] = array_shift($values);
    }
    return $grid;
}

function rotateRow($index,$grid,$sizeX,$sizeY)
{
    $values = [];
    for($x=0;$x<$sizeX;$x++) {
        $values[] = $grid[$index][$x];
    }
    $last_value = array_pop($values);
    array_unshift($values,$last_value);
    for($x=0;$x<$sizeX;$x++) {
        $grid[$index][$x] = array_shift($values);
    }
    return $grid;
}
?>

</pre>