
function searchMechanic(): void {
    let location = (document.getElementById("location") as HTMLInputElement).value;

    document.getElementById("result")!.innerHTML =
        "Searching mechanics near: " + location;
}

function postFeedback(): void {
    let feedback = (document.getElementById("feedback") as HTMLInputElement).value;

    document.getElementById("result")!.innerHTML =
        "Feedback submitted: " + feedback;
}
