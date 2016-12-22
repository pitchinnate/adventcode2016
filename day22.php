<?php

include('day22_input.php');

$nodes = explode("\n",$input);
$allNodes = [];
foreach($nodes as $node) {
    $words = array_values(array_map('trim', array_filter(explode(" ",$node))));
    $allNodes[] = new Node(cleanName($words[0]),cleanNumber($words[1]),cleanNumber($words[2]));
}
$combos = findCombos(range(0,(count($allNodes)-1)));
$viablePair = 0;
foreach($combos as $combo) {
    $node1 = $allNodes[$combo[0]];
    $node2 = $allNodes[$combo[1]];
    if($node2->fitsNode($node1)) {
        $viablePair++;
    }
}
echo "viable pairs: $viablePair";

class Node
{
    public $name;
    public $size;
    public $used;
    public $avail;

    public function __construct($name,int $size,int $used)
    {
        $this->name = $name;
        $this->size = $size;
        $this->used = $used;
        $this->avail = $size - $used;
    }

    public function fitsNode(Node $node)
    {
        if($node->used == 0) {
            return false;
        }
        if($node->used > $this->avail) {
            return false;
        }
        return true;
    }
}

function cleanName($string)
{
    $pieces = explode('/',$string);
    return end($pieces);
}

function cleanNumber($string)
{
    return str_replace('T','',$string);
}

function findCombos($array)
{
    $length = count($array);
    $possibilities = [];
    for($x=0;$x<($length-1);$x++) {
        for($y=($x+1);$y<$length;$y++) {
            $possibilities[] = [$array[$x], $array[$y]];
            $possibilities[] = [$array[$y], $array[$x]];
        }
    }
    return $possibilities;
}