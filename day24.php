<?php

const GOOD_PATH = 6;

include('day24_input.php');
//$input = "###########
//#0.1.....2#
//#.#######.#
//#4.......3#
//###########";

$locations = [];
$grid = [];

$rows = array_filter(explode("\n",$input));
foreach($rows as $key => $row) {
    $characters = str_split($row);
    $grid[$key] = $characters;
    foreach($characters as $xCord => $character) {
        if(is_numeric($character)) {
            $locations[$character] = ['x' => $xCord, 'y' => $key];
        }
    }
}

ksort($locations);

global $minDuplicatePath, $minDuplicatePathCords;
$minDuplicatePath = 100000000;
$minDuplicatePathCords = [];

findMoves($grid,$locations,[0],0,0);

print_r($minDuplicatePathCords);
echo "visited everything in $minDuplicatePath steps \n";

function findMoves($grid,$locations,$visited,$current,$steps)
{
    global $goodAttempts, $minPath, $minPathCords, $minDuplicatePath, $minDuplicatePathCords;

    if(count($visited) == count($locations)) {
        echo "visited everything in $steps steps \n";
        //print_r($visited);
        if($steps < $minDuplicatePath) {
            $minDuplicatePath = $steps;
            $minDuplicatePathCords = $visited;
        }
    } else {
        $needToVisit = array_diff_key($locations, $visited);
        if (count($needToVisit) > 0) {

            $sortedLocations = [];
            foreach($needToVisit as $locationKey => $cords) {
                $sortedLocations[] = [
                    'cords' => $cords,
                    'location' => $locationKey,
                    'difference' => abs($locations[$current]['x'] - $cords['x']) + abs($locations[$current]['y'] - $cords['y']),
                ];
            }
            uasort($sortedLocations, 'compare_results');
            if(count($sortedLocations) > 2) {
                $sortedLocations = array_slice($sortedLocations,0,2);
            }

            $shortestPath = 10000;
            $shortestLocations = [];
            foreach ($sortedLocations as $location) {
                $locationKey = $location['location'];
                $cords = $location['cords'];
                $distance = $location['difference'];

                $goodAttempts = 0;
                $minPath = 5000;
                $minPathCords = [];

                $maxSteps = $distance * 2;
                echo "trying to find path from {$locations[$current]['x']},{$locations[$current]['y']} to {$cords['x']},{$cords['y']} distance is $distance searching within $maxSteps steps \n";
                calcStepsToLocation($locations[$current], $cords, $grid, 0, $maxSteps, []);

                if($minPath == 5000) {
                    echo "no path to location found in under $maxSteps steps \n";
                    $maxSteps = $distance * 3;
                    echo "trying to find path from {$locations[$current]['x']},{$locations[$current]['y']} to {$cords['x']},{$cords['y']} distance is $distance searching within $maxSteps steps \n";
                    calcStepsToLocation($locations[$current], $cords, $grid, 0, $maxSteps, []);
                }
                echo "found path of $minPath steps \n";
                //die();
                if ($minPath == $shortestPath) {
                    $shortestLocations[] = $locationKey;
                }
                if ($minPath < $shortestPath) {
                    $shortestPath = $minPath;
                    $shortestLocations = [$locationKey];
                }
            }
            if (count($shortestLocations) == 1) {
                $steps += $shortestPath;
                $visited[$shortestLocations[0]] = $shortestLocations[0];
                echo "we now visit {$shortestLocations[0]} and it took $steps steps \n";
                findMoves($grid, $locations, $visited, $shortestLocations[0], $steps);
            } else {
                $steps += $shortestPath;
                foreach($shortestLocations as $locationKey) {
                    $duplicateVisited = $visited;
                    $duplicateVisited[$locationKey] = $locationKey;
                    echo "we now visit $locationKey and it took $steps steps \n";
                    findMoves($grid, $locations, $visited, $locationKey, $steps);
                }
            }
        }
    }
}

function calcStepsToLocation($currentCords,$destinationCords,$grid,$steps,$maxSteps,$visited)
{
    global $goodAttempts, $minPath, $minPathCords;

    $visited[] = $currentCords;

    if(compareLocations($currentCords,$destinationCords)) {
        echo "got to destination in $steps steps \n";
//        displayListOfCords($visited);
//        die();
        $goodAttempts++;
        if($steps < $minPath) {
            $minPathCords = $visited;
            $minPath = $steps;
        }
    } else {
        if($steps < $maxSteps && $goodAttempts < GOOD_PATH) {
            $possibleMoves = findAdjacentSpaces($visited,$currentCords,$grid,$destinationCords);
            if(count($possibleMoves) > 0) {
                foreach ($possibleMoves as $move) {
                    calcStepsToLocation($move, $destinationCords, $grid, ($steps + 1), $maxSteps, $visited);
                }
            }
        } else {
//            echo "to many steps \n";
//            displayListOfCords($visited);
        }
    }
}

function displayListOfCords($cords)
{
    foreach($cords as $cord) {
        echo "({$cord['x']},{$cord['y']}),";
    }
    echo "\n";
}

function findAdjacentSpaces($visited,$currentCords,$grid,$destinationCords)
{
    $possibleMoves = [];
    //up
    if($grid[($currentCords['y'] - 1)][$currentCords['x']] != '#') $possibleMoves['up'] = ['x' => $currentCords['x'], 'y' => ($currentCords['y'] - 1)];
    //down
    if($grid[($currentCords['y'] + 1)][$currentCords['x']] != '#') $possibleMoves['down'] = ['x' => $currentCords['x'], 'y' => ($currentCords['y'] + 1)];
    //left
    if($grid[$currentCords['y']][($currentCords['x'] - 1)] != '#') $possibleMoves['left'] = ['x' => ($currentCords['x'] - 1), 'y' => $currentCords['y']];
    //right
    if($grid[$currentCords['y']][($currentCords['x'] + 1)] != '#') $possibleMoves['right'] = ['x' => ($currentCords['x'] + 1), 'y' => $currentCords['y']];

    $cleanedLocations = [];
    foreach($possibleMoves as $key => $possibleMove) {
        $found = false;
        foreach($visited as $visitedLocation) {
            if(compareLocations($possibleMove,$visitedLocation)) {
                $found = true;
                break;
            }
        }
        if(!$found) {
            $cleanedLocations[] = [
                'direction' => $key,
                'coords' => $possibleMove,
                'difference' => abs($possibleMove['x'] - $destinationCords['x']) + abs($possibleMove['y'] - $destinationCords['y']),
            ];
        }
    }

    //try to order by direction that gets us closer
    if(count($cleanedLocations) > 1) {
        uasort($cleanedLocations, 'compare_results');
    }

    $updatedLocations = [];
    foreach($cleanedLocations as $location) {
        $updatedLocations[$location['direction']] = $location['coords'];
    }

    return $updatedLocations;
}

function compare_results($a,$b) {
    return $a['difference'] > $b['difference'];
}

function compareLocations($location,$location2)
{
    return ($location['x'] == $location2['x'] && $location['y'] == $location2['y']);
}

function displayGrid($grid)
{
    foreach($grid as $row) {
        foreach($row as $letter) {
            echo $letter;
        }
        echo "\n";
    }
}