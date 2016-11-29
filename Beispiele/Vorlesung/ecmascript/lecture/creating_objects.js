// #9
// ================================================
// ================================================
// ================================================
// Create Object with Literal Notation

var book1 = {
    name: "The Principles of Object-Oriented JavaScript",
    year: 2014
};

var book2 = {
    "name": "The Principles of Object-Oriented JavaScript",
    "year": 2014
};


var book3 = new Object();
book3.name = "The Principles of Object-Oriented JavaScript";
book3.year = 2014;


// Test that all creations are equal, ie., they are objects
console.log(book3 instanceof Object); // true

console.log(typeof book1); //object
console.log(typeof book2); //object
console.log(typeof book3); //object

console.log(book1 == book2); // false
console.log(book1 === book2); // false



// ================================================
// ================================================
// ================================================
// Primitive Types are no objects
var name = "Hans";
name.last = "Haas";

console.log(name.last); // undefined




// ==================================================================
// ==================================================================
// ==================================================================
// Create via Constructor
function Person(firstname, lastname, birthyear) {
    this.firstname = firstname;
    this.lastname = lastname;
    this.age = new Date().getFullYear() - birthyear;
    
    this.sayName = function() {
        console.log("My name is " + this.firstname + " " + this.lastname);
    }
}

var john = new Person("John", "Doe", 1986);