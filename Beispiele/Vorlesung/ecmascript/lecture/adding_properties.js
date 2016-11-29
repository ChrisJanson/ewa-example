var person = new Object();

person.firstname = "John";
person.surname = "Doe";

person["surname"] = "Doe";

person.firstname;

var s = "firstname";
person[s];

person["firstname"];



// ================================================
// ================================================
// ================================================
// Iterating over Properites in Objects
for (props in person) {
    console.log("Own Property: " + person.hasOwnProperty(props));
    console.log("Name: " + props);
    console.log("Value: " + person[props]);
}

console.log("firstname" in person); // true
console.log("givenname" in person); // false




// ================================================
// ================================================
// ================================================
// #34 Checking for existence of properties
console.log("firstname" in person); // true
console.log(person.hasOwnProperty("firstname")); // true

console.log("toString" in person); // true
console.log(person.hasOwnProperty("toString")); // false




// ================================================
// ================================================
// ================================================
// #36 Enumerations on property keys
var _keys = Object.keys(person);

for (props in _keys) {
    console.log("Own Property: " + person.hasOwnProperty(props));
    console.log("Name: " + props);
    console.log("Value: " + person[props]);
}