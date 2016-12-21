var last_time = new Date();
//var numberOfElves = 5;
var numberOfElves = 3012210;
var elves = [...Array((numberOfElves+1)).keys()];
elves.splice(0,1);
var index = 0;
var countSteps = 0;
var count = numberOfElves;

while(count > 1) {
    if(index > count) {
        index = 0;
    }
    var opposite_index = (Math.floor(count / 2) + index) % count;
    //console.log("index " + index + " opposite: " + opposite_index);
    elves.splice(opposite_index,1);
    count--;
    index++;
    //console.log(elves);

    countSteps++;
    if(countSteps % 1000 == 0) {
        var now = new Date();
        var diff = now - last_time;
        last_time = now;
        console.log(countSteps + " time: " + diff);
    }
}
console.log(elves);
// print_r(var elves);