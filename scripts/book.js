function updateMaxPersons(rooms) {
    let selectedIndex = document.getElementById("room-name").selectedIndex
    let maxPersons = rooms[selectedIndex].maxPersons;
    console.log(maxPersons);
    document.getElementById("num-persons").max = maxPersons;
}