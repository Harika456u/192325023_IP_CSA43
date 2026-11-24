
function searchJob(): void {
    let job = (document.getElementById("job") as HTMLInputElement).value;

    document.getElementById("result")!.innerHTML =
        "You searched for: " + job;
}

function applyJob(): void {
    alert("Job application submitted successfully!");
}
