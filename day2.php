<?php

$instructions = "LUULRUULULLUDUDULDLUDDDLRURUDLRRDRDULRDDULLLRULLLURDDLRDLUUDDRURDDRDDDDRDULULLLLURDDLLRLUUDDDRLRRRDURLDDLRRLDUDRRRDLDLRRDLDLUURRLRULLULRUDRDLRUURLDRDLRLDULLLUDRDDRLURLUUDRLLLDRUUULLUULRUDDUDRDUURRRUDRLDDUURDUURUDRDDLULDDUDUDRRDDULUDULRDRULRLRLURURDULRUULLRDDDDRRUUDDDUUDRLLRUDRLRDLRRLULRLULRUDDULRLLLURLDDRLDDLRRLDRDDDRRLRUDRULUUDUURLDLRRULUDRDULDLLRRURRDDLRRRLULUDUUDDUDDLRDLRDRLRLDUDUDDUDLURRUURDRLRURLURRRLRLRRUDDUDDLUDRLUURUUDUUDDULRRLUUUDRLRLLUR
LDLLRRLDULDDRDDLULRRRDDUDUDRRLLRUUULRUDLLRRDDRRLDDURUUDLUDRRLDURDDRUDLUDUUDLDLLLDLLLDRLLDLRUULULLUUDULDUUULDDLRUDLLUDLUUULDRLUDRULUUDLDURDLDUULLRDUDRDLURULDLUUUDURLDDRLLDRLRDDDUDRUULLDLUDRRDDLDLUURUDDLDRURRLULUDDURLDRDRDUDDRRULRLDURULULRURDUURRUDRDDRDRLDRDUUDLRULRDDDULRURUDRUUULUUDDLRRDDDUDRLRUDRDLRRUDLUDRULDDUDLRLDDLDRLRDLULRDRULRLLRLUDUURULLLDDUULUUDDDUDRRULDDDULRUDRRLRLLLUDLULDUUULDDULDUUDLUULRDLDUDRUDLLDLDLLULDDDDLUDDUDRUDLRRRDDDDDLLRRDRUUDDDRRULRUDUUDRULLDLLLDDRDDUURLUUURUDRUDURLRUUUULUUURDRRRULDUULDLDDDRDDDDLLDRUDRDURLDDURDURULDDRLLRRLDUDRDURRLDRDLLULUUUD
LDDLRLRDDRLRUDDRDDUDRULUUULULDULRUULLRRDUULRDUUDDDRRULDDUDRLLLDULURDLDDRLLRURULULDLDULRDLDLRULUDLLDRUDLDURRDULDDRLRURDLLUDRDDDUDLUDULURULRDRLRULDLLRLDRRUDRDRUDRLDLRLUUURURRRLDDULLULLLRLRLULDLLRLDDRLDULURULRUURRUUURRUDRLRRURURDDDRULDULDLDLRRRLLDDRRURRULULULDRDULDRRULDUDRRLDULDRDURRDULLRRRLLLLRRLLRRRDRURDUULLURURURDDRRDRLLLULRRRDRLDRLDRDLLRUUDURRDRRDLLUDLDRLRLDLUDRDULRULRRLLRDLULDRLUDUUULLDRULDDLLRDUUUDRUUUUULUURDDLLDUURURRURLLURRDDUDUDRUUDDRDDRRLRLULRLRRRDRLLRRLLLDUULLUUDDLULLLDURRLLDRLDRDRLRRLRRULRRRRLRRRRRURUDULUULRDLLDRLRRDUURDRRUDRURRRDDRLDDLRLUDRDRDRRLDDDRDDRRRDUDULRURRDRDLLDRUD
UUUDLDDLRDLLLLRUUURDDLLURRUUURLUULLURUUDUDLDULULLRRRRLLLRDLLUDRUURDRURUDRURRLRLDRURLUDRLULRRURDDDURLLDULDLRRRDUUDDDRDLRUURRDRDRLRDLULRLDDRULRULDRDUDRUURLDLUDDULLLRURRLURLULDRRLUUURURLDLDDULLLRUUURDDDUURULULLUUUDUDRLLRRULUULDDDLLUDLURLLLRRULLURDRLUUDDLLDLLLUDULLRDRRRURDRUDUDUULUDURDLRUDLLRDDRURUDURLRULURDDURULLRDDRLRRDRLLULRDDDULRDLRULDDLRRDULDLUURRURUULRRDUURUDRRRRRLDULDLRURRULULDLRDDDRLLDURRULDUDUDRRRLUULRLUDURRRLRLDURRRRUULDRLUDDDUDURLURUDLLUDRDDDRLLURLRLDDURUUDDDUDUR
RURRRRURUDDRLURUDULRDUDDDUURULDRRRRURDLDRRLLDLUDLRRLRRUULLURULLRDLLRDDDDULLRLLDDLLRUDDULDUDLDURLRUULDDURURDURDLDRRULRURRRRRLRRLLUDURRURULRLRDLRLRRRLLURURDLLLDLDDULDLUDDLLLRUDDRDRLRUDRRLDDLRDLRLRLRLRRDUUURRUDRRLDLRRUULULLUDRRRUDLURDRUULDRDRRLUULULDDLURRLDULLURLDRLDULDRLLDLUUULLULRRDDRURRURLDLDRRLLLLLUDUURUULURLRDDDLRRRRLLLURUDLDDRDDRRUDURUULDRRULLLRRLRULLLRLDDLLRRLRURLRDRUDULLDDLDDDDDLDURURDLULRDDLRDLLRURLLRDLRUDDRDRRDURDURLUDRLDUDDDRRURRLUULURULLRLRDLRRLRURULLDDURLLRRRUDDRDLULURRRUUUULUULRRLLDLRUUURLLURLUURRLRL";
//$instructions = "ULL
//RRDDD
//LURDL
//UUUUD";

$individualInstructions = explode("\n",$instructions);
$buttons = [
    0 => [0,0,5,0,0],
    1 => [0,2,6,'A',0],
    2 => [1,3,7,'B','D'],
    3 => [0,4,8,'C',0],
    4 => [0,0,9,0,0],
];
$cords = [
    'x' => 0,
    'y' => 2,
];

foreach($individualInstructions as $instruction) {
    $directions = str_split($instruction);
    foreach($directions as $direction) {
        switch($direction) {
            case "U":
                if(getButton($cords['x'],($cords['y']-1),$buttons) !== 0) {
                    $cords['y']--;
                }
                break;
            case "D":
                if(getButton($cords['x'],($cords['y']+1),$buttons) !== 0) {
                    $cords['y']++;
                }
                break;
            case "L":
                if(getButton(($cords['x']-1),$cords['y'],$buttons) !== 0) {
                    $cords['x']--;
                }
                break;
            case "R":
                if(getButton(($cords['x']+1),$cords['y'],$buttons) !== 0) {
                    $cords['x']++;
                }
                break;
        }
        //echo "move {$direction} cords x: {$cords['x']} y: {$cords['y']} on button {$buttons[$cords['x']][$cords['y']]} <br>";
    }
    echo "on button {$buttons[$cords['x']][$cords['y']]} <br><br> new button <br><br>";
}

function getButton($x,$y,$buttons)
{
    //echo "get button for: $x, $y<br>";
    if(!isset($buttons[$x][$y])) {
        return 0;
    }
    return $buttons[$x][$y];
}