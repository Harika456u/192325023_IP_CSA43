"use strict";
function registerComplaint() {
    let name = document.getElementById("name").value;
    let complaint = document.getElementById("complaint").value;
    document.getElementById("result").innerHTML =
        name + ", your complaint is registered: " + complaint;
}
function updateLoad() {
    let load = document.getElementById("load").value;
    document.getElementById("result").innerHTML =
        "Garbage load updated: " + load;
}
