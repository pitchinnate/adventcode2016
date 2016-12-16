<html>
<body>
<pre>

<?php
$input = 1352;
//$input = 10;

$sizeX = 50;
$sizeY = 50;

$targetX = 31;
$targetY = 39;
//$targetX = 7;
//$targetY = 4;
//
//$grid = createGrid($sizeX,$sizeY,$input);
//
//foreach($grid as $y) {
//    foreach($y as $x) {
//        echo $x;
//    }
//    echo "\n";
//}
//echo "\n";
//die();

$currentX = 1;
$currentY = 1;
$moves = [];
//findMoves($currentX,$currentY,$moves,$targetX,$targetY,$input);

global $allMoves;
$allMoves = [];

findAllMoves($currentX,$currentY,$moves,$input,0);
print_r($allMoves);

function findAllMoves($currentX,$currentY,$moves,$input,$level)
{
    global $allMoves;
    $newMove = "{$currentX},{$currentY}";
    if(array_search($newMove, $allMoves) === false) {
        $allMoves[] = $newMove;
    }
    $moves[] = $newMove;
    if ($level < 50) {
        $directions = [
            "bottom" => openSpot($currentX, ($currentY + 1), $input),
            "top" => openSpot($currentX, ($currentY - 1), $input),
            "left" => openSpot(($currentX - 1), $currentY, $input),
            "right" => openSpot(($currentX + 1), $currentY, $input),
        ];
        foreach ($directions as $key => $direction) {
            if ($direction != false && array_search(implode(',', $direction), $moves) === false) {
                findAllMoves($direction['x'], $direction['y'], $moves, $input,($level + 1));
            }
        }
    }
}

function findMoves($currentX,$currentY,$moves,$targetX,$targetY,$input)
{
    $moves[] = "{$currentX},{$currentY}";
    $directions = [
        "bottom" => openSpot($currentX, ($currentY + 1), $input),
        "top" => openSpot($currentX, ($currentY - 1), $input),
        "left" => openSpot(($currentX - 1), $currentY, $input),
        "right" => openSpot(($currentX + 1), $currentY, $input),
    ];
    $previousMoves = $moves;
    foreach($directions as $key => $direction) {
        if ($direction != false && array_search(implode(',', $direction), $moves) === false) {
            $newMove = implode(',',$direction);
            if($direction['x'] == $targetX && $direction['y'] == $targetY) {
                $previousMoves[] = $newMove;
                echo "GOOD BRANCH \n";
                print_r($previousMoves);
                echo "\n\n";
                break;
            } else {
                if(count($moves) < 89) {
                    findMoves($direction['x'], $direction['y'], $moves, $targetX, $targetY, $input);
                }
            }
        }
    }
}

function openSpot($x,$y,$input)
{
    if($x >= 0 && $y >= 0 && is_wall($x,$y,$input) === false) {
        return ['x' => $x, 'y' => $y];
    }
    return false;
}

function createGrid($sizeX,$sizeY,$input)
{
    $grid = [];
    for ($y = 0; $y < $sizeY; $y++) {
        for ($x = 0; $x < $sizeX; $x++) {
            if (is_wall($x, $y, $input)) {
                $grid[$y][$x] = "#";
            } else {
                $grid[$y][$x] = ".";
            }
        }
    }
    return $grid;
}

function is_wall($x,$y,$input)
{
    $value = ($x * $x) + (3 * $x) + (2*$x*$y) + $y + ($y * $y);
    $value += $input;
    $base2 = base_convert($value,10,2);
    $letters = str_split($base2);
    $numberOfOnes = 0;
    foreach($letters as $letter) {
        if($letter == '1') {
            $numberOfOnes++;
        }
    }
    if($numberOfOnes % 2 == 0) {
        return false;
    }
    return true;
}
?>
</pre>
</body>
</html>
