var numberOfElves = 3012000;
var elves = [...Array((numberOfElves+1)).keys()];

var last_time = process.hrtime()[1];
elves.splice(2,1);
var now = process.hrtime()[1];
var diff = now - last_time;
console.log(" time: " + diff);

last_time = process.hrtime()[1];
elves.splice(3000000,1);
now = process.hrtime()[1];
diff = now - last_time;
last_time = now;
console.log(" time: " + diff);
