<?php
set_time_limit(0);
?>

<html>
<body>
<pre>

<?php
$input = "01110110101001000";
//$input = "10000";

$length = 272;
$length = 35651584;
//$length = 20;

$newInput = meetLengthRequirment($input,$length);
$checksum = calcChecksum($newInput);
var_dump($checksum);

function calcChecksum($newInput)
{
    $length = strlen($newInput);
    if($length % 2 == 1) {
        return $newInput;
    }
    $letters = str_split($newInput);
    $new = "";
    for($x=0;$x<$length;$x++) {
        $first = (int)$letters[$x];
        $second = (int)$letters[($x+1)];
        if($first === $second) {
            $new .= "1";
        } else {
            $new .= "0";
        }
        $x++;
    }
    return calcChecksum($new);
}

function meetLengthRequirment($input,$length)
{
    if(strlen($input) >= $length) {
        return substr($input,0,$length);
    }
    $letters = str_split($input);
    $newInput = $input . '0' . reverseOnesZeros($input);
    return meetLengthRequirment($newInput,$length);
}

function reverseOnesZeros($string)
{
    $letters = array_reverse(str_split($string));
    $new = "";
    foreach($letters as $letter) {
        $new .= (($letter + 1) % 2);
    }
    return $new;
}