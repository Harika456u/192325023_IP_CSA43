"use strict";
function searchJob() {
    let job = document.getElementById("job").value;
    document.getElementById("result").innerHTML =
        "You searched for: " + job;
}
function applyJob() {
    alert("Job application submitted successfully!");
}
