<html>
<body>
        <pre>
<?php

include("day9_input.php");
//$input = "(25x3)(3x3)ABC(2x3)XY(5x2)PQRSTX(18x9)(3x2)TWO(5x7)SEVEN";
//$input = "(27x12)(20x12)(13x14)(7x10)(1x12)A";
//$input = "X(8x2)(3x3)ABCY";

$letters = str_split($input);
$decompressed = 0;
$length = strlen($input);

$capturingPointer = false;
$capturePointerString = "";

$capturingCompressed = false;
$capturingCompressedString = "";
$capturingCompressedLength = 0;
$capturingCompressedDuplication = 0;

for($i=0;$i<$length;$i++) {
    if($capturingPointer == true) {
        if($letters[$i] == ')') {
            $capturingPointer = false;

            $capturingCompressed = true;
            $capturingCompressedString = "";
            $pieces = explode('x',$capturePointerString);
            $capturingCompressedLength = $pieces[0];
            $capturingCompressedDuplication = $pieces[1];

            $capturePointerString = "";
        } else {
            $capturePointerString .= $letters[$i];
        }
    } else if($letters[$i] == '(' && $capturingPointer == false && $capturingCompressed == false) {
        $capturingPointer = true;
    } else if($capturingCompressed == true) {
        $capturingCompressedString .= $letters[$i];
        if(strlen($capturingCompressedString) == $capturingCompressedLength) {
            $characterCount = checkPointer($capturingCompressedString);
            $decompressed += ($characterCount * $capturingCompressedDuplication);
            $capturingCompressed = false;
        }
    } else {
        $decompressed++;
    }
}

function checkPointer($string)
{
    $decompressed = 0;

    $capturingPointer = false;
    $capturePointerString = "";

    $capturingCompressed = false;
    $capturingCompressedString = "";
    $capturingCompressedLength = 0;
    $capturingCompressedDuplication = 0;

    $letters = str_split($string);
    $length = strlen($string);

    for($i=0;$i<$length;$i++) {
        if ($capturingPointer == true) {
            if ($letters[$i] == ')') {
                $capturingPointer = false;

                $capturingCompressed = true;
                $capturingCompressedString = "";
                $pieces = explode('x', $capturePointerString);
                $capturingCompressedLength = $pieces[0];
                $capturingCompressedDuplication = $pieces[1];

                $capturePointerString = "";
            } else {
                $capturePointerString .= $letters[$i];
            }
        } else if ($letters[$i] == '(' && $capturingPointer == false && $capturingCompressed == false) {
            $capturingPointer = true;
        } else if ($capturingCompressed == true) {
            $capturingCompressedString .= $letters[$i];
            if (strlen($capturingCompressedString) == $capturingCompressedLength) {
                $characterCount = checkPointer($capturingCompressedString);
                $decompressed += ($characterCount * $capturingCompressedDuplication);
                $capturingCompressed = false;
            }
        } else {
            $decompressed++;
        }
    }
    return $decompressed;
}

echo "\n\nDecompressed Length:\n\n$decompressed";

?>
</pre>
</body>
</html>