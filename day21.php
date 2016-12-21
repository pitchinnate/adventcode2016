<?php
$password = "fbgdceah";
include('day21_input.php');

$instructions = explode("\n",$input);
$instructions = array_reverse($instructions);

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
    $index1 = (int)$words[5];
    $index2 = (int)$words[2];
    $value = $letters[$index1];
    array_splice($letters,$index1,1);
    array_splice($letters,$index2,0,$value);

    return implode('',$letters);
}

function rotate($words,$letters)
{
    if($words[1] == 'based') {
        $letter = $words[6];
//        print_r($letters);
        //run once no matter what
        $first = array_shift($letters);
        array_push($letters,$first);
//        print_r($letters);

        $correct = false;
        $count = 0;
        while($correct == false) {
            $index = array_search($letter,$letters);
//            echo "index: $index count: $count \n";
            if($index == $count) {
                $correct = true;
            } else {
                $first = array_shift($letters);
                array_push($letters,$first);
//                print_r($letters);
                $count++;
                if($count == 4) {
                    //needs to be run an extra time
                    $first = array_shift($letters);
                    array_push($letters,$first);
                }
            }
        }
//        print_r($letters);
//        die();
    } else {
        $direction = $words[1];
        $steps = (int)$words[2];
        for($x=1;$x<=$steps;$x++) {
            if($direction == 'right') {
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

