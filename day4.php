<?php

include("day4_rooms.php");
//$allRoomCodes = "aaaaa-bbb-z-y-x-123[abxyz]";
//$goodRoomCodes = "qzmt-zixmtkozy-ivhz-343[abxyz]";
$roomCodes = explode("\n",$goodRoomCodes);

$total_sector = 0;
foreach($roomCodes as $roomCode) {
    //echo "$roomCode <br>";
    $pieces = explode('-',$roomCode);
    $charactersFound = [];
    $sector = 0;
    $verify_code = "";
    $allLetters = [];
    foreach($pieces as $piece) {
        $letters = str_split($piece);
        if(!is_numeric($letters[0])) {
            foreach($letters as $letter) {
                $allLetters[] = $letter;
                if (!isset($charactersFound[$letter])) {
                    $charactersFound[$letter] = 1;
                } else {
                    $charactersFound[$letter]++;
                }
            }
            $allLetters[] = ' ';
        } else {
            $pieces2 = explode('[',$piece);
            $sector = $pieces2[0];
            $verify_code = substr(trim($pieces2[1]),0,-1);
        }
    }
//    arsort($charactersFound);
//    $groupedByQuantity = [];
//    foreach($charactersFound as $character => $count) {
//        if(!isset($groupedByQuantity[$count])) {
//            $groupedByQuantity[$count] = [];
//        }
//        $groupedByQuantity[$count][] = $character;
//    }
//    $keepers = [];
//    foreach($groupedByQuantity as $letters) {
//        sort($letters);
//        foreach($letters as $letter) {
//            $keepers[] = $letter;
//            if(count($keepers) == 5) {
//                break;
//            }
//        }
//        if(count($keepers) == 5) {
//            break;
//        }
//    }
//    $code = implode('',$keepers);
//    echo "sector: $sector <br>";
//    echo "verify_code: $verify_code <br>";
//    echo "code: $code <br>";

    //if($verify_code == $code) {
        // a = 97
        // z = 122
        //$total_sector += $sector;
        //echo "total_sector: $total_sector <br><Br>";
        foreach($allLetters as $letter) {
            if($letter != ' ') {
                $characterCode = ((ord($letter) - 96 + $sector) % 26) + 96;
                echo chr($characterCode);
            } else {
                echo " ";
            }
        }
//    } else {
//        echo "bad room code";
//    }
    echo "<br>is sector: $sector <br><br>";
}

//echo "total_sector: $total_sector";