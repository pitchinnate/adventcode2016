<?php

include("day7_input.php");
//$input = "aba[bab]xyz
//xyx[xyx]xyx
//aaa[kek]eke
//zazbz[bzb]cdb";

$codes = explode("\n",$input);
$good_codes = 0;
foreach($codes as $code) {
    echo "<br><hr><br>Code: $code <br>";

    list($hypers,$nonHypers) = breakDownIp($code);

    $hyperBlocks = hasKey($hypers);
    $nonHyperBlocks = hasKey($nonHypers);

    var_dump($hyperBlocks);
    var_dump($nonHyperBlocks);

    if(checkOverlap($hyperBlocks,$nonHyperBlocks)) {
        echo "IS GOOD <br>";
        $good_codes++;
    } else {
        echo "BAD <br>";
    }
}

echo "total good codes: $good_codes";

function checkOverlap($hyperBlocks,$nonHyperBlocks)
{
    foreach($hyperBlocks as $hyperBlock) {
        $letters = str_split($hyperBlock);
        foreach($nonHyperBlocks as $nonHyperBlock) {
            $letters2 = str_split($nonHyperBlock);
            if($letters[0] == $letters2[1] && $letters[1] == $letters2[0]) {
                echo "overlapped on: $hyperBlock and $nonHyperBlock <br>";
                return true;
            }
        }
    }
    return false;
}

function breakDownIp($code)
{
    $hypers = [];
    $nonHypers = [];

    $pieces = explode('[',$code);
    $nonHypers[] = array_shift($pieces);

    foreach($pieces as $piece) {
        $pieces2 = explode(']',$piece);
        $hypers[] = $pieces2[0];
        $nonHypers[] = $pieces2[1];
    }

    return [$hypers,$nonHypers];
}

function hasKey($strings)
{
    $foundBlocks = [];
    foreach($strings as $string) {
        $letters = str_split($string);
        if (count($letters) < 3) {
            echo "<span style='color: red'>string: $string is not good not enough letters</span><br>";
        }
        $indexesToCheck = count($letters) - 2;
        for ($x = 0; $x < $indexesToCheck; $x++) {
            if ($letters[$x] === $letters[($x + 2)] && $letters[$x] !== $letters[($x + 1)]) {
                echo "<span style='color: green'>string: $string is Good</span><br>";
                $foundBlocks[] = $letters[$x] . $letters[($x+1)] . $letters[($x+2)];
            }
        }
    }
    return $foundBlocks;
}