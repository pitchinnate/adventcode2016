package main;

import (
	"fmt"
	"strings"
	"strconv"
	"math"
	"sort"
	"github.com/cznic/mathutil"
)

func main() {
	mapString := getMap()
	grid := convertMap(mapString)
	locations := findLocations(grid)
	locations = findDistances(locations,grid)
	//fmt.Println(locations)
	startLocation, startIndex := findStartingLocation(locations)
	remainingLocations := RemoveLocation(locations,startIndex)
	FindOptions(startLocation,remainingLocations)
	//fmt.Println(options)
}

func FindOptions(start Location, locations []Location) []Option {
	options := make([]Option,0)

	locationSort := LocationSorter(locations)
	mathutil.PermutationFirst(locationSort)
	printLocations(locationSort)
	for {
		if !mathutil.PermutationNext(locationSort) {
			break
		}
		printLocations(locationSort)
	}

	//newOption := Option{}
	//newOption.Locations = append(newOption.Locations,start.Name)
	//
	//
	//
	//options = append(options,newOption)


	return options
}

func printLocations(locations []Location) {
	for _,location := range locations {
		fmt.Printf("%s,",location.Name)
	}
	fmt.Print("\n")
}

func findRoughDistances(start Location, locations []Location) []Location {
	for index, location := range locations {
		locations[index].Distance = int(math.Abs(float64(start.X - location.X))) + int(math.Abs(float64(start.Y - location.Y)))
	}
	return locations
}

func findRoughDistance(start Location, end Location) int {
	return int(math.Abs(float64(start.X - end.X))) + int(math.Abs(float64(start.Y - end.Y)))
}

func findDistances(locations [] Location, grid [][]string) [] Location {
	locationCount := len(locations)
	for i := 0; i < (locationCount - 1); i++ {
		for j := (i + 1); j < locationCount; j++ {
			startLocation := locations[i]
			endLocation := locations[j]
			roughDistance := findRoughDistance(startLocation,endLocation)
			pathsFound := 0
			//fmt.Printf("Going from %s to %s rough distance is %d \n",startLocation.Name,endLocation.Name,roughDistance)
			findPath(startLocation,&endLocation,Path{Steps: []Location{startLocation}}, grid, &pathsFound, (roughDistance * 4))
			//fmt.Printf("Shortest path found was %d steps \n",endLocation.Distance)
			locations[i].Distances = append(locations[i].Distances, Distance{Name: endLocation.Name, Distance: endLocation.Distance})
			locations[j].Distances = append(locations[j].Distances, Distance{Name: startLocation.Name, Distance: startLocation.Distance})
		}
	}
	return locations
}

func findPath(start Location, end *Location, path Path, grid [][]string, pathsFound *int, maxSteps int) {
	if(start.X == end.X && start.Y == end.Y) {
		*pathsFound++
		//fmt.Printf("found a path to %s in %d steps \n",end.Name,(len(path.Steps) - 1))
		if(end.Distance == 0 || end.Distance > len(path.Steps)) {
			end.Distance = len(path.Steps) - 1
		}
	} else {
		if(len(path.Steps) < maxSteps && *pathsFound < 10) {
			viableMoves := findPossibleMoves(start, grid, path, *end)
			if (len(viableMoves) > 0) {
				for _, location := range viableMoves {
					newPath := path
					newPath.Steps = append(newPath.Steps, location)
					findPath(location, end, newPath, grid, pathsFound, maxSteps)
				}
			}
		}
	}
}

func findPossibleMoves(currentLocation Location, grid [][]string, path Path, end Location) []Location {
	moves := make([]Location,0)
	//check up
	if grid[(currentLocation.Y - 1)][currentLocation.X] != "#" {
		moves = append(moves,Location{X: currentLocation.X, Y: (currentLocation.Y - 1)})
	}
	//check down
	if grid[(currentLocation.Y + 1)][currentLocation.X] != "#" {
		moves = append(moves,Location{X: currentLocation.X,Y: (currentLocation.Y + 1)})
	}
	//check left
	if grid[currentLocation.Y][(currentLocation.X - 1)] != "#" {
		moves = append(moves,Location{X: (currentLocation.X - 1),Y: currentLocation.Y})
	}
	//check right
	if grid[currentLocation.Y][(currentLocation.X + 1)] != "#" {
		moves = append(moves,Location{X: (currentLocation.X + 1),Y: currentLocation.Y})
	}

	if(len(moves) > 0) {
		moves = findRemainingLocations(moves,path.Steps)
		moves = findRoughDistances(end,moves)
		sort.Sort(LocationSorter(moves))
	}

	return moves
}

func findRemainingLocations(locations []Location, visitedLocations []Location) []Location {
	goodLocations := make([]Location, 0)
	for _,location := range locations {
		found := false
		for _, visitedLocation := range visitedLocations {
			if(location.X == visitedLocation.X && location.Y == visitedLocation.Y) {
				found = true
				break
			}
		}
		if found == false {
			goodLocations = append(goodLocations,location)
		}
	}
	return goodLocations
}

func RemoveLocation(s []Location, index int) []Location {
	return append(s[:index], s[index+1:]...)
}

func findStartingLocation(locations []Location) (Location, int) {
	var foundLocation Location
	var foundIndex int
	for i, v := range locations {
		if v.Name == "0" {
			foundLocation = v
			foundIndex = i
			break
		}
	}
	return foundLocation, foundIndex
}


func IsInteger(s string) bool {
	_, err := strconv.Atoi(s)
	return err == nil
}

func findLocations(grid [][]string) []Location {
	locations := make([]Location,0)
	for yCord, row := range grid {
		for xCord, letter := range row {
			if IsInteger(letter) {
				locations = append(locations, Location{X: xCord, Y: yCord, Name: letter})
			}
		}
	}
	return locations
}

func convertMap(mapString string) [][]string {
	rows := strings.Split(mapString,"\n")
	grid := make([][]string,len(rows))
	for i,v := range rows {
		grid[i] = strings.Split(v,"")
	}
	return grid
}

type Option struct {
	Locations []string
	TotalDistance int
}

type Distance struct {
	Name string
	Distance int
}

type Path struct {
	Steps []Location
}

type Location struct {
	X int
	Y int
	Name string
	Distance int
	Distances []Distance
}

type LocationSorter []Location

func (locations LocationSorter) Len() int {
	return len(locations)
}

func (locations LocationSorter) Less(i, j int) bool {
	return locations[i].Distance < locations[j].Distance
}

func (locations LocationSorter) Swap(i, j int) {
	locations[i], locations[j] = locations[j], locations[i]
}

func getMap() string {
	grid := `###########
#0.1.....2#
#.#######.#
#4.......3#
###########`
	//	grid := `#####################################################################################################################################################################################
	//#.....#.........#.#...#.....#.............#.......#.....#.....#...........#...#.........#.#.#.....#.......#...............#..........3#.#.#.....#.......#...#.....#...#.#.#.....#...#
	//#.###.#.#.###.#.#.#.#.#.#.#.#.#.#####.#####.#.###.#.#.#######.###.#.#######.#.#.#.#.#.#.#.#.#.#####.#.#.###.#######.#.###.###.#.#.#.#.#.#.#.#.#.#.#.#.#####.#.###.#.#.#.#.###.#.###.#
	//#.......#.#...#...#.#...#...#.#...#...#.#...#.....#...#.#.....#.....#.....#.......#...#...#.................#.#.............#...#.....#.........#...#...#.#...#...#.....#.......#...#
	//#.#.#.###.#.#.###.#.#.#.#.###.#.###.###.#.#.#.#######.#.#####.#.#.#####.#.#.#.#####.#.###.#.#####.#####.#.###.###.###.#####.#.#.#.#.#.#.#.#.#.#.###.###.#.#.#.#.#####.#.#.#.#.#.#####
	//#..1#.......#...........#...#.........#.#.....#...#.#...#.........#...#...#...#.....#.#...#.#.#.....#...#.#.#...#.......#.........#.......#...#.#...#.....#.#.....#...#...#..2#.....#
	//#.#####.###.#.#.#.###.###.###.#####.#.#.#.#.###.#.#.#.#.#####.###.#.#.#####.#.#.#.#.#.#.###.#.#.#.#.#.#.#.#.#.#.#.#.#####.###.#.#.#####.###.###.#.#.#.###.#.#####.#.#.#.###.#.#.#.#.#
	//#...#.............#.#...#.#...#...#.#.#...#...#.............#.#.....#.........#.........#.#...#.#.#.#...#.......#.#.......#...#...#.#.......#...#.#.....#.........#.#.#.#.........#.#
	//#.#.#.###.###.#.#.#.#.#.#.#.#.#.#.#.###.###.###.#.#####.#.#.#.###.#.#.#.#####.#.#.###.#.#.#.#.#.###.#.#.#.#####.#####.###.#.#.#.#.#.#.#######.#.#.#.#.#.#.#######.#.#.#.#.###.#.#.#.#
	//#.......#...#.....#.....#.......#...#.....#.#.#.........#.......#.#.....#...#.#...#...#.#.....#...#.#...........#.........#...#.#.#...#.#.....#.....#.....#...........#...#.......#.#
	//#####.###.#.###########.###.#.###.###.###.#.#.#.###.#.###.###.#.#.#.#####.#.#.#.#########.#####.#.#.###.#.#.#.#.#.###.#.#.#.###.#.#####.#.#.#.#.#.#.#.#####.###.#####.###.#.#.#.#.#.#
	//#...#...#.......#.....#.....#.....#.....#.......#.#.#.....#...........#.....#.#.#.#.......#.....#.......#...........#.#...#...#.#.......#...#.....#...#.#...#.#...#...#.....#.....#.#
	//#.#.###.#.###.#.#####.#.#.#.#.#.#.###.#.###.###.#.#.#.###.#.#.#.###.#.#.###.#.#.#.#.#.#.#.###.#.#.#######.###.#######.#.###.###.#.###.#.#.#.###.###.#.#.#.#.#.#.#.#.###.#.#.###.#.#.#
	//#.....#...#.........#...#...#.#.#.........#.#.#...#.#...#.#...#.#.........#.....#.#...#.#...#...#.......#.....#...#...#.#.....#.......#.#...#...#.........#.#...#.#.........#.#...#.#
	//#.###.###########.#.###.#.#.#.#####.#.#.#.###.#.#.#.#####.#.###.#.#.#######.#####.#.#.#.#.###.#.#.###.#.#.#####.###.#.#.#.#.#.#########.###.#.#.#.#.#.###.#.#.#.#.#.#.#.#.#.#.#####.#
	//#.#.................#.............#...#...#.#.#.#...#...#...#.....#.......#.#.#...#...........#.........#.......#.........#...#...#...#.........#.#...#...#.........#.........#...#.#
	//#.#.#.#####.#######.###.###.###.#.#######.#.#.###.###.#.#.#.#####.#####.###.#.#.#.#.###.#.###.#.#.#####.#.#.#.#.#.#.###.#.#.#.#.#.#.#.#.###.###.#######.###.#.#.#.#.#.#.###.#.#.#.#.#
	//#...#.#.#...................#0............#...........#.#.....#.#.....#.#.........#.....#.......#.......#.....#.......#.#...#.......#.#.#...#.............#...#.....#.#.......#...#6#
	//#.#.#.#.#.###.#.#.#.#.#####.###.#.#.#####.#####.###.#.###.###.#.#.#.#.#.#.#####.#.#.#.#.#.#####.#.###.#.#####.#.#####.#.#.#.#.#####.#.#.#.#.#.#.#.#########.#.###.###.#######.#.#.###
	//#.#...#.#.......#.#.#.#.....#...#...#.#...#...#.#...#.........#...#...#...#.....#.....#.....#...#.....#.......#.....#...#...#.#.....#.#...#.#.#.#.#.......#...#.......#...#...#...#.#
	//#.###.#.###.#.#.#.#.#.###.#.#.#.###.#.###.#.#.#.#.###.#.#.#.#.#.#.#####.#.#####.#.#####.#.#.#.###.#.#############.###.###.###.###########.#.###.#.#.#.###.#.###.###.#.#.#.#.#.#.###.#
	//#.....#.#...#...#...#...#.#.#.........#.....#...#...#.#.....#...#.#...........#.#.......#...#.#.......#.#...#.........#...#...#.#.#.....#...#.#.#.#.......#...........#...#.#.......#
	//#.#.#####.#.###########.#.#.#.#############.#.#.#.#.#######.#######.###.#.###.###.###.#######.#.###.#.#.#.#.#######.###.###.###.#.#.#.#.#.#.#.#.#.#.###.#.#######.###.###.#.#.#.#####
	//#...#.#.......#.................#.#.........#.....#.#.#.....#...#.....#.......#...#...#.......#.#...#.#.#...#...........#.#.#.....#.#.........#...#.#...........#...#.....#...#.#...#
	//###.#.#.#.###.#.#.#.#.#.#.###.#.#.#.#.#.#.###.#.#.#.#.#.#.#.###.#.###.#.###.###.#.#####.#####.###.#.#.#.#######.#.#.#.#.#.#.#.###.#.#.###.#.#.###.#.#.#####.#.#.#.###.#.#.###.#.#.#.#
	//#...#...#.....#.#.#...#...#...#...#.............#.....#...#.#.#...#.............#.#.............#...#.#.#...#.#.#...#.#...#.#.#.......#.#.......#...#.#.....#...#...#.#...#.#...#...#
	//#.#.#.#.#.#####.#.#.#.#.#.#.#######.###.#######.#.###.#.###.#.#.#.###.#.#.###.#.#.#.#.#.#.#.###.###.#.###.###.###.###.###.###.###.#####.#######.###.#.###.#.#.###.#.#.#.###.###.#.#.#
	//#...#.#.#.......#.#.#...#...........#.........#.#.#...#.#.#.#.#.#.............#...#...#...#.....#.......#...#.#...#...#...#...#.........#...#...#.....#.#.....#.#.#...#...#.#...#...#
	//###.#.#.#.###.###.###.#.#####.#.#.#.#.#.#####.###.#.###.#.#.#.#.###.#.###.###.###.#.#.#.###.###.###.###.###.###.#.#.###.#.#.#.#.###.#.#.#.#.#.#.#.#.#.#.#.#.#.#.#.#.#.###.#.###.###.#
	//#...#...#.#...#...#.#.#.......#...#...#.#.......#.......#.#.....#.........#...........#.....#...#...#.......#...........#...#...#.#.#...#.......#...#.....#.....#.#....5#.....#.....#
	//#.#.#.#####.#.#.#.#.###.#.#.#.###.#.#.###.#####.#.#.#.#.###.#.#.#.#.#.#.#.#.#.#.#.###############.#.###.#.#.#.###.###.#.#.#.#.###.#.#.###.#####.#.#.#####.###.###.#.#.#.###.#.#.#.#.#
	//#.........#.#...#.....#...#.#.#...#.....#...#.....#.....#...#.#.#...#.#.....#...#.............#...#.#.....#.#.....#...........#...#.............#...#...#.#...#...#.#.......#...#...#
	//#.#.#.#.#.#.###.#####.###.#.#.#.#.#.###.#.###.#.###.#.#.#.#.#.###.#.#.#.#####.#.#####.#####.###.#.#.#.#############.#####.#.###.#.###.#.#.#.#.#####.#.#.#.#.#.#.#.#.#.#.#.#.#.#######
	//#4#.#.....#.#.....#...#...#...#...#...#.#.#...#...#...#.#.....#...#...#.........#...#.#.....#...#.#...#.#.....#.#.#...#...#.#...#.#.......#.#.......#...#.......#.#.#.#.#.........#.#
	//#####.#.###.###.###.#####.###.#.#.###.#.#.#.#.#.#.#.#.#####.#.#.#.#.###.#.#.#.#.#.#.#.#.#.###.#.#.###.#.#.#.#.#.#.###.#.#.###.#.#.###.#.#.#.###.###.#.#.#.#.#####.#.###.#.#####.###.#
	//#.......#...#...#...#.#.#.........#...#.#7#.#...#...#.......#.#.#.#.....#.#.....#.....#.....#...#.#.#.#...........#...#.....#.............#...............#.....#.........#...#.....#
	//#####################################################################################################################################################################################`
	return grid
}
