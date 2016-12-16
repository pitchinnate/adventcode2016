<html>
<body>
<pre>
<?php
global $input;
$input = "ihaygndm";
//$input = "abc";

global $hashes;
$hashes = [];
$keys = [];
$index = 0;

while(count($keys) < 64) {
    $newHash = getHash($index);
    $repeatLetter = findRepeat($newHash,3);
    if($repeatLetter !== false) {
        $lookForward = lookForward($repeatLetter,1000,5,$index);
        if($lookForward !== false) {
            $keys[$index] = "index: $index has hash of $newHash letter: $repeatLetter and $lookForward";
        }
    }
    $index++;
}
echo "count: " . count($keys) . "\n";
print_r($keys);

function getHash($index)
{
    global $hashes;
    global $input;

    if(!isset($hashes[$index])) {
        $hashes[$index] = md5($input . $index);
        for($x=1;$x<=2016;$x++) {
            $hashes[$index] = md5($hashes[$index]);
        }
    }
    return $hashes[$index];
}

function lookForward($letter,$forwardSteps,$quantity,$index)
{
    $min = $index + 1;
    $max = $index + $forwardSteps;
    for($x=$min;$x<=$max;$x++) {
        $newHash = getHash($x);
        $repeatLetter = findRepeat($newHash,$quantity,$letter);
        if($repeatLetter !== false) {
            return "$x has hash of $newHash \n";
        }
    }
    return false;
}

function findRepeat($string,$quantity,$specificLetter=null)
{
    $letters = str_split($string);
    $length = count($letters) - $quantity;
    for($x=0;$x<=$length;$x++) {
        $letter = $letters[$x];
        if(is_null($specificLetter) || $specificLetter == $letter) {
            $lookFor = str_repeat($letter, $quantity);
            $found = substr($string,$x,$quantity);
            if($lookFor === $found) {
                return $letter;
            }
        }
    }
    return false;
}

?>
</pre>
</body>
</html>