let form = document.querySelector("form");
let pairAddButton = document.querySelector("#pair-add");
let pairPrefab = form.querySelector("#pair-prefab");
let parentUl = form.querySelector("ul");

function countCurrentPairs() {
    let lis = parentUl.querySelectorAll("li");
    return lis.length - 1;
}

function addPair() {
    let newPair = pairPrefab.cloneNode(true);
    newPair.removeAttribute("id");
    newPair.classList.toggle("hidden", false);
    parentUl.appendChild(newPair);

    let pairCountLabel = newPair.querySelector("h2");

    let count = countCurrentPairs();
    pairCountLabel.textContent = "Pair " + count;

    let cleanInput = newPair.querySelector("input[name=new-clean]");
    let linesInput = newPair.querySelector("input[name=new-lines]");

    cleanInput.name = `new-clean#${count}`;
    linesInput.name = `new-lines#${count}`;
}

pairAddButton.addEventListener("click", addPair);