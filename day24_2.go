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
	sort.Sort(LocationSorter(locations))
	locations = findDistances(locations,grid)
	startLocation := locations[0]
	remainingLocations := locations[1:]
	options := FindOptions(startLocation,remainingLocations)
	findShortestPath(options,locations)
}

func findShortestPath(options []Option,locations []Location) {
	shortestPath := 10000
	shortestOption := Option{}

	for i,option := range options {
		for x := 0; x < (len(option.Locations) - 1); x++ {
			startIndex, _ := strconv.Atoi(option.Locations[x]);
			endName := option.Locations[(x + 1)]
			start := locations[startIndex]
			for _, distance := range start.Distances {
				if distance.Name == endName {
					//fmt.Printf("Distance from %s to %s is %d\n",start.Name,endName,distance.Distance)
					if(distance.Distance > 0) {
						options[i].TotalDistance += distance.Distance
					} else {
						options[i].TotalDistance += 1000
					}
					break
				}
			}
		}
		if options[i].TotalDistance < shortestPath {
			shortestPath = options[i].TotalDistance
			shortestOption = options[i]
		}
	}
	fmt.Println(shortestOption)
	fmt.Printf("Short path was %d steps",shortestPath)
}

func FindOptions(start Location, locations []Location) []Option {
	options := make([]Option,0)
	data := sort.StringSlice{"1","2","3","4","5","6","7"}
	//data := sort.StringSlice{"1","2","3","4"}

	mathutil.PermutationFirst(data)
	locationNames := data
	locationNames = append([]string{start.Name},locationNames...)
	locationNames = append(locationNames,start.Name)
	options = append(options,Option{Locations: locationNames})

	for {
		if !mathutil.PermutationNext(data) {
			break
		}
		locationNames := data
		locationNames = append([]string{start.Name},locationNames...)
		locationNames = append(locationNames,start.Name)
		options = append(options,Option{Locations: locationNames})
	}

	return options
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
			fmt.Printf("Going from %s to %s rough distance is %d \n",startLocation.Name,endLocation.Name,roughDistance)
			mapString := getMap()
			newGrid := convertMap(mapString)
			distance := findBestPath(startLocation,endLocation,newGrid)
			fmt.Printf("Shortest path found was %d steps \n",distance)
			locations[i].Distances = append(locations[i].Distances, Distance{Name: endLocation.Name, Distance: distance})
			locations[j].Distances = append(locations[j].Distances, Distance{Name: startLocation.Name, Distance: distance})
		}
	}
	return locations
}

type Step struct {
	Location Location
	StepCount int
}

func findBestPath(start Location, end Location, grid [][]string) int {
	startStep := Step{start,0}
	queue := []Step{startStep}
	grid[start.Y][start.X] = "#"

	path := []Location{start}
	shortestPath := 10000

	queueLength := 1
	for queueLength > 0 {
		processStep := queue[0]
		queue = append(queue[1:])

		if(processStep.Location.X == end.X && processStep.Location.Y == end.Y) {
			return processStep.StepCount
		}

		viableMoves := findNeighbors(processStep.Location, grid)
		if (len(viableMoves) > 0) {
			for _, location := range viableMoves {
				path = append(path,location)
				queue = append(queue,Step{location,(processStep.StepCount + 1)})
				grid[location.Y][location.X] = "#"
			}
		}
		queueLength = len(queue)
	}

	return shortestPath
}

func findNeighbors(currentLocation Location, grid [][]string) []Location {
	moves := make([]Location,0)
	//check left
	if grid[currentLocation.Y][(currentLocation.X - 1)] != "#" {
		moves = append(moves,Location{X: (currentLocation.X - 1),Y: currentLocation.Y})
	}
	//check right
	if grid[currentLocation.Y][(currentLocation.X + 1)] != "#" {
		moves = append(moves,Location{X: (currentLocation.X + 1),Y: currentLocation.Y})
	}
	//check up
	if grid[(currentLocation.Y - 1)][currentLocation.X] != "#" {
		moves = append(moves,Location{X: currentLocation.X, Y: (currentLocation.Y - 1)})
	}
	//check down
	if grid[(currentLocation.Y + 1)][currentLocation.X] != "#" {
		moves = append(moves,Location{X: currentLocation.X,Y: (currentLocation.Y + 1)})
	}

	return moves
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
	return locations[i].Name < locations[j].Name
}

func (locations LocationSorter) Swap(i, j int) {
	locations[i], locations[j] = locations[j], locations[i]
}

func getMap() string {
//	grid := `###########
//#0.1.....2#
//#.#######.#
//#4.......3#
//###########`
	grid := `#####################################################################################################################################################################################
#.....#.........#.#...#.....#.............#.......#.....#.....#...........#...#.........#.#.#.....#.......#...............#..........3#.#.#.....#.......#...#.....#...#.#.#.....#...#
#.###.#.#.###.#.#.#.#.#.#.#.#.#.#####.#####.#.###.#.#.#######.###.#.#######.#.#.#.#.#.#.#.#.#.#####.#.#.###.#######.#.###.###.#.#.#.#.#.#.#.#.#.#.#.#.#####.#.###.#.#.#.#.###.#.###.#
#.......#.#...#...#.#...#...#.#...#...#.#...#.....#...#.#.....#.....#.....#.......#...#...#.................#.#.............#...#.....#.........#...#...#.#...#...#.....#.......#...#
#.#.#.###.#.#.###.#.#.#.#.###.#.###.###.#.#.#.#######.#.#####.#.#.#####.#.#.#.#####.#.###.#.#####.#####.#.###.###.###.#####.#.#.#.#.#.#.#.#.#.#.###.###.#.#.#.#.#####.#.#.#.#.#.#####
#..1#.......#...........#...#.........#.#.....#...#.#...#.........#...#...#...#.....#.#...#.#.#.....#...#.#.#...#.......#.........#.......#...#.#...#.....#.#.....#...#...#..2#.....#
#.#####.###.#.#.#.###.###.###.#####.#.#.#.#.###.#.#.#.#.#####.###.#.#.#####.#.#.#.#.#.#.###.#.#.#.#.#.#.#.#.#.#.#.#.#####.###.#.#.#####.###.###.#.#.#.###.#.#####.#.#.#.###.#.#.#.#.#
#...#.............#.#...#.#...#...#.#.#...#...#.............#.#.....#.........#.........#.#...#.#.#.#...#.......#.#.......#...#...#.#.......#...#.#.....#.........#.#.#.#.........#.#
#.#.#.###.###.#.#.#.#.#.#.#.#.#.#.#.###.###.###.#.#####.#.#.#.###.#.#.#.#####.#.#.###.#.#.#.#.#.###.#.#.#.#####.#####.###.#.#.#.#.#.#.#######.#.#.#.#.#.#.#######.#.#.#.#.###.#.#.#.#
#.......#...#.....#.....#.......#...#.....#.#.#.........#.......#.#.....#...#.#...#...#.#.....#...#.#...........#.........#...#.#.#...#.#.....#.....#.....#...........#...#.......#.#
#####.###.#.###########.###.#.###.###.###.#.#.#.###.#.###.###.#.#.#.#####.#.#.#.#########.#####.#.#.###.#.#.#.#.#.###.#.#.#.###.#.#####.#.#.#.#.#.#.#.#####.###.#####.###.#.#.#.#.#.#
#...#...#.......#.....#.....#.....#.....#.......#.#.#.....#...........#.....#.#.#.#.......#.....#.......#...........#.#...#...#.#.......#...#.....#...#.#...#.#...#...#.....#.....#.#
#.#.###.#.###.#.#####.#.#.#.#.#.#.###.#.###.###.#.#.#.###.#.#.#.###.#.#.###.#.#.#.#.#.#.#.###.#.#.#######.###.#######.#.###.###.#.###.#.#.#.###.###.#.#.#.#.#.#.#.#.###.#.#.###.#.#.#
#.....#...#.........#...#...#.#.#.........#.#.#...#.#...#.#...#.#.........#.....#.#...#.#...#...#.......#.....#...#...#.#.....#.......#.#...#...#.........#.#...#.#.........#.#...#.#
#.###.###########.#.###.#.#.#.#####.#.#.#.###.#.#.#.#####.#.###.#.#.#######.#####.#.#.#.#.###.#.#.###.#.#.#####.###.#.#.#.#.#.#########.###.#.#.#.#.#.###.#.#.#.#.#.#.#.#.#.#.#####.#
#.#.................#.............#...#...#.#.#.#...#...#...#.....#.......#.#.#...#...........#.........#.......#.........#...#...#...#.........#.#...#...#.........#.........#...#.#
#.#.#.#####.#######.###.###.###.#.#######.#.#.###.###.#.#.#.#####.#####.###.#.#.#.#.###.#.###.#.#.#####.#.#.#.#.#.#.###.#.#.#.#.#.#.#.#.###.###.#######.###.#.#.#.#.#.#.###.#.#.#.#.#
#...#.#.#...................#0............#...........#.#.....#.#.....#.#.........#.....#.......#.......#.....#.......#.#...#.......#.#.#...#.............#...#.....#.#.......#...#6#
#.#.#.#.#.###.#.#.#.#.#####.###.#.#.#####.#####.###.#.###.###.#.#.#.#.#.#.#####.#.#.#.#.#.#####.#.###.#.#####.#.#####.#.#.#.#.#####.#.#.#.#.#.#.#.#########.#.###.###.#######.#.#.###
#.#...#.#.......#.#.#.#.....#...#...#.#...#...#.#...#.........#...#...#...#.....#.....#.....#...#.....#.......#.....#...#...#.#.....#.#...#.#.#.#.#.......#...#.......#...#...#...#.#
#.###.#.###.#.#.#.#.#.###.#.#.#.###.#.###.#.#.#.#.###.#.#.#.#.#.#.#####.#.#####.#.#####.#.#.#.###.#.#############.###.###.###.###########.#.###.#.#.#.###.#.###.###.#.#.#.#.#.#.###.#
#.....#.#...#...#...#...#.#.#.........#.....#...#...#.#.....#...#.#...........#.#.......#...#.#.......#.#...#.........#...#...#.#.#.....#...#.#.#.#.......#...........#...#.#.......#
#.#.#####.#.###########.#.#.#.#############.#.#.#.#.#######.#######.###.#.###.###.###.#######.#.###.#.#.#.#.#######.###.###.###.#.#.#.#.#.#.#.#.#.#.###.#.#######.###.###.#.#.#.#####
#...#.#.......#.................#.#.........#.....#.#.#.....#...#.....#.......#...#...#.......#.#...#.#.#...#...........#.#.#.....#.#.........#...#.#...........#...#.....#...#.#...#
###.#.#.#.###.#.#.#.#.#.#.###.#.#.#.#.#.#.###.#.#.#.#.#.#.#.###.#.###.#.###.###.#.#####.#####.###.#.#.#.#######.#.#.#.#.#.#.#.###.#.#.###.#.#.###.#.#.#####.#.#.#.###.#.#.###.#.#.#.#
#...#...#.....#.#.#...#...#...#...#.............#.....#...#.#.#...#.............#.#.............#...#.#.#...#.#.#...#.#...#.#.#.......#.#.......#...#.#.....#...#...#.#...#.#...#...#
#.#.#.#.#.#####.#.#.#.#.#.#.#######.###.#######.#.###.#.###.#.#.#.###.#.#.###.#.#.#.#.#.#.#.###.###.#.###.###.###.###.###.###.###.#####.#######.###.#.###.#.#.###.#.#.#.###.###.#.#.#
#...#.#.#.......#.#.#...#...........#.........#.#.#...#.#.#.#.#.#.............#...#...#...#.....#.......#...#.#...#...#...#...#.........#...#...#.....#.#.....#.#.#...#...#.#...#...#
###.#.#.#.###.###.###.#.#####.#.#.#.#.#.#####.###.#.###.#.#.#.#.###.#.###.###.###.#.#.#.###.###.###.###.###.###.#.#.###.#.#.#.#.###.#.#.#.#.#.#.#.#.#.#.#.#.#.#.#.#.#.###.#.###.###.#
#...#...#.#...#...#.#.#.......#...#...#.#.......#.......#.#.....#.........#...........#.....#...#...#.......#...........#...#...#.#.#...#.......#...#.....#.....#.#....5#.....#.....#
#.#.#.#####.#.#.#.#.###.#.#.#.###.#.#.###.#####.#.#.#.#.###.#.#.#.#.#.#.#.#.#.#.#.###############.#.###.#.#.#.###.###.#.#.#.#.###.#.#.###.#####.#.#.#####.###.###.#.#.#.###.#.#.#.#.#
#.........#.#...#.....#...#.#.#...#.....#...#.....#.....#...#.#.#...#.#.....#...#.............#...#.#.....#.#.....#...........#...#.............#...#...#.#...#...#.#.......#...#...#
#.#.#.#.#.#.###.#####.###.#.#.#.#.#.###.#.###.#.###.#.#.#.#.#.###.#.#.#.#####.#.#####.#####.###.#.#.#.#############.#####.#.###.#.###.#.#.#.#.#####.#.#.#.#.#.#.#.#.#.#.#.#.#.#######
#4#.#.....#.#.....#...#...#...#...#...#.#.#...#...#...#.#.....#...#...#.........#...#.#.....#...#.#...#.#.....#.#.#...#...#.#...#.#.......#.#.......#...#.......#.#.#.#.#.........#.#
#####.#.###.###.###.#####.###.#.#.###.#.#.#.#.#.#.#.#.#####.#.#.#.#.###.#.#.#.#.#.#.#.#.#.###.#.#.###.#.#.#.#.#.#.###.#.#.###.#.#.###.#.#.#.###.###.#.#.#.#.#####.#.###.#.#####.###.#
#.......#...#...#...#.#.#.........#...#.#7#.#...#...#.......#.#.#.#.....#.#.....#.....#.....#...#.#.#.#...........#...#.....#.............#...............#.....#.........#...#.....#
#####################################################################################################################################################################################`
	return grid
}
