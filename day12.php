<html>
<body>
<pre>

<?php

include("day12_input.php");
//$input = "cpy 41 a
//inc a
//inc a
//dec a
//jnz a 2
//dec a";

$instructions = explode("\n",$input);

$registers = ['a'=>0,'b'=>0,'c'=>1,'d'=>0];
$length = count($instructions);
$x = 0;
$count = 0;
while($x < $length) {
    $instruction = trim($instructions[$x]);
    //echo "run instruction ($x): $instruction \n";
    $words = explode(' ',$instruction);
    switch($words[0]) {
        case 'cpy':
            if(is_numeric($words[1])) {
                $registers[$words[2]] = (int)$words[1];
            } else {
                $registers[$words[2]] = $registers[$words[1]];
            }
            $x++;
            break;
        case 'inc':
            $registers[$words[1]]++;
            $x++;
            break;
        case 'dec':
            $registers[$words[1]]--;
            $x++;
            break;
        case 'jnz':
            if(is_numeric($words[1])) {
                $value = (int)$words[1];
            } else {
                $value = $registers[$words[1]];
            }
            if($value !== 0) {
                $x += (int)$words[2];
            } else {
                $x++;
            }
            break;
    }
    //print_r($registers);
    $count++;
//    if($count > 200) {
//        die();
//    }
}
echo "took $count moves \n";
print_r($registers);
?>

</pre>
</body>
</html>