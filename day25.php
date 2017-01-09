<?php
$input = "cpy a d
cpy 4 c
cpy 633 b
inc d
dec b
jnz b -2
dec c
jnz c -5
cpy d a
jnz 0 0
cpy a b
cpy 0 a
cpy 2 c
jnz b 2
jnz 1 6
dec b
dec c
jnz c -4
inc a
jnz 1 -7
cpy 2 b
jnz c 2
jnz 1 4
dec b
dec c
jnz 1 -4
jnz 0 0
out b
jnz a -19
jnz 1 -21";



$instructions = array_values(array_map('trim', array_filter(explode("\n",$input))));
$length = count($instructions);

$regA = 0;
$test = true;
while (true) {
    $regA++;
    echo "\n---------------------------------\n";
    echo "testing with reg a: $regA \n";
    $registers = ['a'=>$regA,'b'=>0,'c'=>0,'d'=>0];

    $x = 0;
    $highestX = 0;
    $count = 0;

    ob_start();
    while($x < $length) {
        $instruction = trim($instructions[$x]);
        $words = explode(' ',$instruction);
        list($registers,$x,$instructions) = runInstruction($words,$x,$registers,$instructions);
        $count++;
        if($x > $highestX) {
            $highestX = $x;
        }
        if($count % 100000 == 0) {
            break;
        }
    }
    $output = ob_get_contents();
    ob_end_clean();
    echo $output;
    if(strpos($output,'00') === false && strpos($output,'11') === false) {
        die();
    }
}

function runInstruction($words,$x,$registers,$instructions)
{
    global $running;
    switch($words[0]) {
        case 'cpy':
            if(!is_numeric($words[2])) {
                if (is_numeric($words[1])) {
                    $registers[$words[2]] = (int)$words[1];
                } else {
                    $registers[$words[2]] = $registers[$words[1]];
                }
            }
            $x++;
            break;
        case 'out':
            if(is_numeric($words[1])) {
                    echo (int)$words[1];
            } else {
                echo $registers[$words[1]];
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
                $value = (int)$registers[$words[1]];
            }
            if(is_numeric($words[2])) {
                $value2 = (int)$words[2];
            } else {
                $value2 = (int)$registers[$words[2]];
            }
            $isMultiply = false;
            if($value2 == -2) {
                $words2 = explode(' ',$instructions[($x - 1)]);
                $words3 = explode(' ',$instructions[($x - 2)]);
                if(($words2[0] == 'inc' && $words3[0] == 'dec') || ($words2[0] == 'dec' && $words3[0] == 'inc')) {
                    if($words2[0] == 'inc') {
                        $increaseRegister = $words2[1];
                        $decreaseRegister = $words3[1];
                    } else {
                        $increaseRegister = $words3[1];
                        $decreaseRegister = $words2[1];
                    }
                    //print_r($registers);
                    $registers[$increaseRegister] += $registers[$decreaseRegister];
                    $registers[$decreaseRegister] = 0;
                    $isMultiply = true;
//                    echo "ran multiply on $instructions[$x] \n";
//                    $running = false;
                    $x++;
                }
            }
            if(!$isMultiply) {
                if($value !== 0) {
                    $x += $value2;
                } else {
                    $x++;
                }
            }
            break;
        case 'tgl':
            $register_value = $registers[$words[1]];
            if(isset($instructions[($register_value + $x)])) {
                $toggledInstruction = $instructions[($register_value + $x)];
                $words2 = explode(' ',$toggledInstruction);
                if(count($words2) == 2) {
                    if($words2[0] == 'inc') {
                        $words2[0] = 'dec';
                    } else {
                        $words2[0] = 'inc';
                    }
                } else {
                    if($words2[0] == 'jnz') {
                        $words2[0] = 'cpy';
                    } else {
                        $words2[0] = 'jnz';
                    }
                }
                $instructions[($register_value + $x)] = implode(' ',$words2);
            }
            $x++;
            break;
    }
    return [$registers,$x,$instructions];
}

echo "took $count moves \n";
print_r($registers);