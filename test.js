var numberOfElves = 3012210;
var elves = [...Array((numberOfElves+1)).keys()];

var last_time = new Date();
elves.splice(2,1);
var now = new Date();
var diff = now - last_time;
last_time = now;
console.log(" time: " + diff);

elves.splice(3000000,1);
now = new Date();
diff = now - last_time;
last_time = now;
console.log(" time: " + diff);
