"use strict";
function searchPlace() {
    let place = document.getElementById("place").value;
    document.getElementById("result").innerHTML =
        "Searching destination: " + place;
}
function bookRoom() {
    let name = document.getElementById("name").value;
    document.getElementById("result").innerHTML =
        "Accommodation booked successfully for " + name;
}
