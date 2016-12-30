<?php
$input = "cpy a b
dec b
cpy a d
cpy 0 a
cpy b c
inc a
dec c
jnz c -2
dec d
jnz d -5
dec b
cpy b c
cpy c d
dec d
inc c
jnz d -2
tgl c
cpy -16 c
jnz 1 c
cpy 83 c
jnz 78 d
inc a
inc d
jnz d -2
inc c
jnz c -5";
$registers = ['a'=>12,'b'=>0,'c'=>0,'d'=>0];
$x = 0;

//after 200000
//$input = "cpy a b
//dec b
//cpy a d
//cpy 0 a
//cpy b c
//inc a
//dec c
//jnz c -2
//dec d
//jnz d -5
//dec b
//cpy b c
//cpy c d
//dec d
//inc c
//jnz d -2
//tgl c
//cpy -16 c
//jnz 1 c
//cpy 83 c
//jnz 78 d
//inc a
//inc d
//jnz d -2
//inc c
//jnz c -5";
//$registers = ['a'=>46076,'b'=>8,'c'=>5,'d'=>6121];
//$x = 6;

//$input = "cpy 2 a
//tgl a
//tgl a
//tgl a
//cpy 1 a
//dec a
//dec a";
//$registers = ['a'=>0];
$highestX = 0;

$instructions = array_values(array_map('trim', array_filter(explode("\n",$input))));
$length = count($instructions);
$count = 0;
global $running;
$running = true;
while($x < $length) {
    //echo "running instruction $x \n";
    $instruction = trim($instructions[$x]);
    $words = explode(' ',$instruction);
    list($registers,$x,$instructions) = runInstruction($words,$x,$registers,$instructions);
    $count++;
    if($x > $highestX) {
        $highestX = $x;
    }
    if($count % 10000000 == 0) {
        echo "ran $count steps, do you want to stop? (y/n) [n] \n";
        exec("choice /C yn /D n /T 1",$out,$ret);
        if($ret == 1) {
            $running = false;
        }
    }
    if(!$running) {
        print_r($registers);
        foreach($instructions as $key => $instruction) {
            echo "$key => ";
            echo "$instruction \n";
        }
        echo "\nfarthest x got was $highestX currently at $x \n";
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