<html>
<body>
<pre>

<?php

include("day10_input.php");
//$input = "value 5 goes to bot 2
//bot 2 gives low to bot 1 and high to bot 0
//value 3 goes to bot 1
//bot 1 gives low to output 1 and high to bot 0
//bot 0 gives low to output 2 and high to output 0
//value 2 goes to bot 2";

$instructions = explode("\n",$input);
$bots = [];
$bins = [];

//setup all bots and bins
foreach($instructions as $instruction) {
    $words = explode(' ',$instruction);
    $word_count = count($words);
    for($x=0;$x<$word_count;$x++) {
        if($words[$x] == 'bot') {
            $x++;
            $botIndex = (int)$words[$x];
            if(!array_key_exists($botIndex,$bots)) {
                $bots[$botIndex] = new Bot($botIndex);
            }
        }
        if($words[$x] == 'output') {
            $x++;
            $binIndex = (int)$words[$x];
            if(!array_key_exists($binIndex,$bins)) {
                $bins[$binIndex] = new Bin();
            }
        }
    }
}

//setup bot instructions
foreach($instructions as $instruction) {
    $words = explode(' ',$instruction);
    if($words[0] == 'bot') {
        $botIndex = (int)$words[1];

        //set low
        $giveIndex = (int)$words[6];
        if($words[5] == 'output') {
            $bots[$botIndex]->setLow($bins[$giveIndex]);
        } else {
            $bots[$botIndex]->setLow($bots[$giveIndex]);
        }

        //set high
        $giveIndex = (int)$words[11];
        if($words[10] == 'output') {
            $bots[$botIndex]->setHigh($bins[$giveIndex]);
        } else {
            $bots[$botIndex]->setHigh($bots[$giveIndex]);
        }
    }
}

foreach($instructions as $instruction) {
    $words = explode(' ',$instruction);
    if($words[0] == 'value') {
        $chip = (int)$words[1];
        $botIndex = (int)$words[5];
        $bots[$botIndex]->give($chip);
    }
}

var_dump($bins[0]);
var_dump($bins[1]);
var_dump($bins[2]);

class Bin
{
    public $chips;

    public function give($chip)
    {
        $this->chips[] = $chip;
    }
}

class Bot
{
    public $name = "";
    public $chips = [];
    public $lowOutputObject;
    public $highOutputObject;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function give($chip)
    {
        $this->chips[] = $chip;
        if(count($this->chips) == 2) {
            $this->output();
        }
    }

    public function setLow($outputObject)
    {
        $this->lowOutputObject = $outputObject;
    }

    public function setHigh($outputObject)
    {
        $this->highOutputObject = $outputObject;
    }

    public function output()
    {
        sort($this->chips);

        $firstChip = array_shift($this->chips);
        $secondChip = array_shift($this->chips);

//        if($firstChip == 17 && $secondChip == 61) {
//            var_dump($this);
//            die();
//        }

        $this->lowOutputObject->give($firstChip);
        $this->highOutputObject->give($secondChip);
    }
}

?>

</pre>
</body>
</html>
