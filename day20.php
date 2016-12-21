<?php

include('day20_input.php');

$maxIp = 4294967295;

$lines = explode("\n",$input);
$blacklist = [];
foreach($lines as $line) {
    $pieces = explode('-',$line);
    $blacklist[$pieces[0]] = [
        'min' => $pieces[0],
        'max' => $pieces[1],
    ];
}
ksort($blacklist);
$blacklist = array_values($blacklist);
$removed = 1;

while($removed > 0) {
    $removed = 0;
    $blacklistCount = count($blacklist);
    $newBlackList = [];
    for ($x = 0; $x < $blacklistCount; $x++) {
        if($x == ($blacklistCount - 1)) {
            $newBlackList[] = $blacklist[$x];
            break;
        }
        $min = (int)$blacklist[$x]['min'];
        $max = (int)$blacklist[$x]['max'];
        $y = $x + 1;
        if ($blacklist[$y]['min'] >= $min && $blacklist[$y]['min'] <= $max) {
            if ($max < $blacklist[$y]['max']) {
                $max = $blacklist[$y]['max'];
            }
            $removed++;
            $x++;
        } else {
            if ($blacklist[$y]['min'] == ($max + 1)) {
                $max = $blacklist[$y]['max'];
                $removed++;
                $x++;
            }
        }
        $newBlackList[] = [
            'min' => $min,
            'max' => $max,
        ];
    }
    $blacklist = $newBlackList;
}

$validIps = 0;
$blacklistCount = count($blacklist) - 1;
for($x=0;$x<$blacklistCount;$x++) {
    $goodRange = $blacklist[($x+1)]['min'] - $blacklist[$x]['max'] - 1;
    $validIps += $goodRange;
}
echo "valid ips: $validIps";