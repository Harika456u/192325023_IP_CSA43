"use strict";
function registerDonor() {
    let name = document.getElementById("name").value;
    let group = document.getElementById("group").value;
    let phone = document.getElementById("phone").value;
    document.getElementById("result").innerHTML =
        "Donor Registered: " + name + " | " + group + " | " + phone;
}
function searchDonor() {
    let group = document.getElementById("search").value;
    document.getElementById("result").innerHTML =
        "Searching donors with blood group: " + group;
}
