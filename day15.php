<html>
<body>
<pre>

<?php
$discs = [
    new Disc('1',13,10),
    new Disc('2',17,15),
    new Disc('3',19,17),
    new Disc('4',7,1),
    new Disc('5',5,0),
    new Disc('6',3,1),
    new Disc('7',11,0),
];

//$discs = [
//    new Disc('1',5,4),
//    new Disc('2',2,1),
//];

$disc_count = count($discs);
$good_spot = false;
$time = 0;
while($good_spot === false) {
    $goodDiscs = 0;
    for($x=0;$x<$disc_count;$x++) {
        $move_time = $time + $x + 1;
        if($discs[$x]->findPosition($move_time) == 0) {
            $goodDiscs++;
        }
    }
    //echo "disc count: $disc_count good_discs: $goodDiscs \n";
    if($goodDiscs == $disc_count) {
        $good_spot = true;
        echo "found it at time: $time \n";
    } else {
        $time++;
    }
}

class Disc
{
    public $name;
    public $positions;
    public $startPosition;

    public function __construct($name,$positions,$startPosition)
    {
        $this->name = $name;
        $this->positions = $positions;
        $this->startPosition = $startPosition;
    }

    public function findPosition($time)
    {
        return ($this->startPosition + $time) % $this->positions;
    }
}