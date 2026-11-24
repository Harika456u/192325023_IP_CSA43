
function registerComplaint(): void {
    let name = (document.getElementById("name") as HTMLInputElement).value;
    let complaint = (document.getElementById("complaint") as HTMLInputElement).value;

    document.getElementById("result")!.innerHTML =
        name + ", your complaint is registered: " + complaint;
}

function updateLoad(): void {
    let load = (document.getElementById("load") as HTMLInputElement).value;

    document.getElementById("result")!.innerHTML =
        "Garbage load updated: " + load;
}
