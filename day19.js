//const numberOfElves = 7;
const numberOfElves = 3012210;
var elves = [...Array((numberOfElves+1)).keys()];
elves.splice(0,1);

var last_time = new Date();
var index = 0;
var countSteps = 0;
var count = numberOfElves;

while(count > 1) {

    if(index > (count - 1)) {
        index = 0;
    }
    var opposite_index = (Math.floor(count / 2) + index) % count;
    //console.log(elves);
    //console.log("index " + index + " opposite: " + opposite_index + ' remove elf ' + elves[opposite_index]);
    elves.splice(opposite_index,1);
    count--;

    if(index > opposite_index) {
        //don't move
    } else {
        index++;
    }

    countSteps++;
    if(countSteps % 10000 == 0) {
        var now = new Date();
        var diff = now - last_time;
        last_time = now;
        console.log(countSteps + " time: " + diff);
    }
}
console.log(elves);
