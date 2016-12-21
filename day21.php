<?php
$password = "abcde";
$password = "abcdefgh";

$input = "swap position 4 with position 0
swap letter d with letter b
reverse positions 0 through 4
rotate left 1 step
move position 1 to position 4
move position 3 to position 0
rotate based on position of letter b
rotate based on position of letter d";
include('day21_input.php');

$instructions = explode("\n",$input);
foreach($instructions as $instruction) {
    echo "$password \n";
    echo "$instruction \n";
    $words = explode(" ",$instruction);
    $words = array_map('trim', $words);
    $password = $words[0]($words,str_split($password));
}
echo "$password \n";

function swap($words,$letters)
{
    if($words[1] == 'position') {
        $index1 = (int)$words[2];
        $index2 = (int)$words[5];
        $temp = $letters[$index1];
        $letters[$index1] = $letters[$index2];
        $letters[$index2] = $temp;
    } else {
        $indexs1 = array_keys($letters,$words[2]);
        $indexs2 = array_keys($letters,$words[5]);

        $newLetters = [];
        foreach($letters as $key => $letter) {
            if(in_array($key,$indexs1)) {
                $newLetters[] = $words[5];
            } elseif(in_array($key,$indexs2)) {
                $newLetters[] = $words[2];
            } else {
                $newLetters[] = $letter;
            }
        }
        $letters = $newLetters;
    }
    return implode('',$letters);
}

function reverse($words,$letters)
{
    $index1 = (int)$words[2];
    $index2 = (int)$words[4];
    $newLetters = [];
    for($x=$index1;$x<=$index2;$x++) {
        $newLetters[$x] = $letters[$x];
    }
    $newLetters = array_reverse($newLetters);
    $newLetters2 = [];
    foreach($newLetters as $key => $value) {
        $newLetters2[($key + $index1)] = $value;
    }
    $finalLetters = [];
    foreach($letters as $key => $letter) {
        if(array_key_exists($key,$newLetters2)) {
            $finalLetters[] = $newLetters2[$key];
        } else {
            $finalLetters[] = $letter;
        }
    }
    $letters = $finalLetters;
    return implode('',$letters);
}

function move($words,$letters)
{
    $index1 = (int)$words[2];
    $index2 = (int)$words[5];
    $value = $letters[$index1];
    array_splice($letters,$index1,1);
    array_splice($letters,$index2,0,$value);

    return implode('',$letters);
}

function rotate($words,$letters)
{
    if($words[1] == 'based') {
        $letter = $words[6];
        $index = array_search($letter,$letters);
        $count = 1 + $index;
        if($index >= 4) {
            $count++;
        }
        for($x=1;$x<=$count;$x++) {
            $last = array_pop($letters);
            array_unshift($letters,$last);
        }
    } else {
        $direction = $words[1];
        $steps = (int)$words[2];
        for($x=1;$x<=$steps;$x++) {
            if($direction == 'left') {
                $first = array_shift($letters);
                array_push($letters,$first);
            } else {
                $last = array_pop($letters);
                array_unshift($letters,$last);
            }
        }
    }
    return implode('',$letters);
}

