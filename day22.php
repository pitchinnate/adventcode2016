<?php
const TARGET_X = 33;
const TARGET_Y = 0;
const TARGET_DATA_SIZE = 73;
const MAX_X = 33;
const MAX_Y = 29;

include('day22_input.php');

$nodes = explode("\n",$input);
$allNodes = [];
foreach($nodes as $node) {
    $words = array_values(array_map('trim', array_filter(explode(" ",$node))));
    $node = new Node(cleanName($words[0]),cleanNumber($words[1]),cleanNumber($words[2]));
    $allNodes[$node->yCord][$node->xCord] = $node;
}

//$allNodes = $allNodes[TARGET_Y][TARGET_X]->findMoves($allNodes);

displayNodes($allNodes,'used');

class Node
{
    public $name;
    public $size;
    public $used;
    public $avail;
    public $xCord;
    public $yCord;
    public $targetData = false;

    public function __construct($name,int $size,int $used)
    {
        $this->name = $name;
        $this->size = $size;
        $this->used = $used;
        $this->avail = $size - $used;
        $this->calcCords();
    }

    public function calcCords()
    {
        $pieces = explode('-',$this->name);
        $this->xCord = substr($pieces[1],1);
        $this->yCord = substr($pieces[2],1);
        if($this->xCord == TARGET_X && $this->yCord == TARGET_Y) {
            $this->targetData = true;
        }
    }

    public function fitsOnNode(Node $node)
    {
        if($this->used <= $node->avail) {
            return false;
        }
        return true;
    }

    public function addUsed($used)
    {
        $this->used += $used;
        $this->avail = $this->size - $this->used;
    }

    public function setUsed($used)
    {
        $this->used = $used;
        $this->avail = $this->size - $used;
    }

    public function moveData(Node $node)
    {
        if($this->fitsOnNode($node)) {
            $node->addUsed($this->used);
            $this->setUsed(0);
            if ($this->targetData) {
                $node->targetData = true;
                $this->targetData = false;
            }
        }
        return $node;
    }

    /**
     * @param Node[] $nodes
     */
    public function findConnected(array $nodes)
    {
        $connectedNodes = [];
        if($this->xCord > 0)  $connectedNodes[] = $nodes[$this->yCord][($this->xCord - 1)];
        if($this->xCord < MAX_X)  $connectedNodes[] = $nodes[$this->yCord][($this->xCord + 1)];

        if($this->yCord > 0)  $connectedNodes[] = $nodes[($this->yCord - 1)][$this->xCord];
        if($this->yCord < MAX_Y)  $connectedNodes[] = $nodes[($this->yCord + 1)][$this->xCord];

        return $connectedNodes;
    }

    /**
     * @param Node[] $nodes
     */
    public function findMoves(array $nodes)
    {
        $connectedNodes = $this->findConnected($nodes);
        /** @var Node[] $canMoveNow */
        /** @var Node[] $needsSpace */
        $canMoveNow = [];
        $needsSpace = [];
        foreach($connectedNodes as $node) {
            if($this->fitsOnNode($node) && (!$this->targetData || $node->used == 0)) {
                $canMoveNow[] = $node;
            } else {
                $needsSpace[] = $node;
            }
        }
        if(count($canMoveNow) > 0) {
            $modifiedNode = $this->moveData($canMoveNow[0]);
            $nodes[$this->yCord][$this->xCord] = $this;
            $nodes[$modifiedNode->yCord][$modifiedNode->xCord] = $modifiedNode;
        }
        return $nodes;
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

/**
 * @param Node[] $nodes
 */
function displayNodes(array $nodes,$display='used')
{
    foreach($nodes as $yCord => $row) {
        foreach($row as $node) {
            //echo sprintf('%1$03d',$node->$display) . ' ';
            echo sprintf('%1$03d',$node->used) . '|'. sprintf('%1$03d',$node->avail) . '|'. sprintf('%1$03d',$node->size) . ' ';
        }
        echo "\n";
    }
}