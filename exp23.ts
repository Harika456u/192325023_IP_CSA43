
function registerDonor(): void {
    let name = (document.getElementById("name") as HTMLInputElement).value;
    let group = (document.getElementById("group") as HTMLInputElement).value;
    let phone = (document.getElementById("phone") as HTMLInputElement).value;

    document.getElementById("result")!.innerHTML =
        "Donor Registered: " + name + " | " + group + " | " + phone;
}

function searchDonor(): void {
    let group = (document.getElementById("search") as HTMLInputElement).value;

    document.getElementById("result")!.innerHTML =
        "Searching donors with blood group: " + group;
}
