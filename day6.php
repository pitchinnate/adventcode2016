<?php

include('day6_input.php');
//$allCodes = "eedadn
//drvtee
//eandsr
//raavrd
//atevrs
//tsrnev
//sdttsa
//rasrtv
//nssdts
//ntnada
//svetve
//tesnvt
//vntsnd
//vrdear
//dvrsen
//enarar";

$lines = explode("\n",$allCodes);
$letterPositions = [[],[],[],[],[],[]];

foreach($lines as $line) {
    $letters = str_split($line);
    foreach($letters as $key => $letter) {
        if(!isset($letterPositions[$key][$letter])) {
            $letterPositions[$key][$letter] = 1;
        } else {
            $letterPositions[$key][$letter]++;
        }
    }
}
$code = "";
$ordered = [];
foreach($letterPositions as $key => $letters) {
    asort($letters);
    $flipped = array_flip($letters);
    $code .= array_shift($flipped);
}
echo "code: $code";