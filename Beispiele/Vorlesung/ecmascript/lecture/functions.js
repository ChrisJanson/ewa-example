// #18

// Declaration
function add(num1, num2) {
    return num1 + num2;
}


// Expression as anonymous function
var add = function (num1, num2) {
    return num1 + num2;
};


// OK
var result = add(5, 5);

function add(num1, num2) {
    return num1 + num2;
}


// error!
var result = add(5, 5);

var add = function (num1, num2) {
    return num1 + num2;
};




// ================================================
// ================================================
// ================================================
// Functions as values (Functionpointer)
function sayHi() {
//    console.log("Hi!");
    return "Hi!";
}

sayHi(); // outputs "Hi!" 

var sayHi2 = sayHi;

sayHi2();       // outputs "Hi!"



// ================================================
// ================================================
// ================================================
// Variable Function Arguments
function sum() {
    "use strict";
    var result = 0,
        i = 0,
        len = arguments.length;
    
    while (i < len) {
        result += arguments[i];
        i++;
    }
    return result;
}

console.log(sum(1, 2)); // 3
console.log(sum(3, 4, 5, 6)); // 18
console.log(sum(50)); // 50
console.log(sum()); // 0
