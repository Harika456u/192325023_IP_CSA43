"use strict";
function searchMechanic() {
    let location = document.getElementById("location").value;
    document.getElementById("result").innerHTML =
        "Searching mechanics near: " + location;
}
function postFeedback() {
    let feedback = document.getElementById("feedback").value;
    document.getElementById("result").innerHTML =
        "Feedback submitted: " + feedback;
}
