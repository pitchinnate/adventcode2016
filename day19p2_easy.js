const numberOfElves = 3012210;

Math.logBase = function(n, base) {
    return Math.log(n) / Math.log(base);
};

var power = Math.floor(Math.logBase(numberOfElves,3));
var lastPowerOfThree = Math.pow(3,power);
var remaining = numberOfElves - lastPowerOfThree;
if(remaining == 0) {
    console.log("exact power of three:",lastPowerOfThree);
} else if (remaining <= lastPowerOfThree) {
    console.log("less than previous so remaining is: ",remaining);
} else {
    var extra = (remaining - lastPowerOfThree);
    var result = lastPowerOfThree + (extra * 2);
    console.log("more than previous so just remaining + extra * 2:",result);
}