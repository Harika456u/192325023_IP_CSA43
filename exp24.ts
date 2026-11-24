
function searchPlace(): void {
    let place = (document.getElementById("place") as HTMLInputElement).value;

    document.getElementById("result")!.innerHTML =
        "Searching destination: " + place;
}

function bookRoom(): void {
    let name = (document.getElementById("name") as HTMLInputElement).value;

    document.getElementById("result")!.innerHTML =
        "Accommodation booked successfully for " + name;
}
