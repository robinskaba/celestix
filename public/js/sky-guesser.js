const FETCH_PAIR_ENDPOINT = "/api/fetch-constellation-img-pair";
const VALIDATE_RESULT_ENDPOINT = "/api/validate-sky-guess";
const INCREASE_STAT_ENDPOINT = "/api/increase-stat";

let cleanImage = document.querySelector("#clean-image");
let linesImage = document.querySelector("#lines-image");
let loader = document.querySelector(".loader");

let solutionWrap = document.querySelector("#solution-wrap");
let continueButton = solutionWrap.querySelector("button");
let solutionSpan = solutionWrap.querySelector("span");

let form = document.querySelector("#guess-form");
let skipButton = form.querySelector("#skip-button");
let submitButton = form.querySelector("#submit-button");
let guessField = form.querySelector("input[name=guess]");

let constellationName;
let clean;
let lines;

function requestStatIncrease(property) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", INCREASE_STAT_ENDPOINT, true);
    xhr.send(JSON.stringify({
        stat: "sky-guess",
        property: property
    }));
}

function toggleSolution(solutionShown) {
    cleanImage.style.opacity = solutionShown ? 0 : 1;
    form.classList.toggle("hidden", solutionShown);
    solutionWrap.classList.toggle("hidden", !solutionShown);

    if (!solutionShown) {
        solutionSpan.classList.toggle("correct", false);
        solutionSpan.classList.toggle("incorrect", false);
    }
}

function showLoading(isLoading) {
    loader.classList.toggle("hidden", !isLoading);
    cleanImage.classList.toggle("hidden", isLoading);
    linesImage.classList.toggle("hidden", isLoading);
}

function setFieldsToCurrent() {
    guessField.value = "";
    solutionSpan.textContent = constellationName;
    cleanImage.src = "/resources/image?id=" + clean;
    linesImage.src = "/resources/image?id=" + lines;

    guessField.focus();
}

function fetchPair() {
    showLoading(true);

    const xhr = new XMLHttpRequest();
    xhr.open("GET", FETCH_PAIR_ENDPOINT, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);

            constellationName = response.name;
            clean = response.clean;
            lines = response.lines;

            // console.log("HACK: "+constellationName);

            setFieldsToCurrent();
            showLoading(false);
        }
    };
    xhr.send();
}

function newPair() {
    toggleSolution(false);
    fetchPair();
}

function onResultValidated(matching) {
    solutionSpan.classList.toggle("correct", matching);
    solutionSpan.classList.toggle("incorrect", !matching);

    requestStatIncrease("total");
    if(matching) {
        requestStatIncrease("success");
    }
}

function validateResult() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `${VALIDATE_RESULT_ENDPOINT}?name=${guessField.value}`, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            onResultValidated(response.matching);
        }
    };
    xhr.send();
}

newPair();

skipButton.addEventListener("click", (ev) => {
    ev.preventDefault();
    toggleSolution(true);
});

submitButton.addEventListener("click", (ev) => {
    ev.preventDefault();
    validateResult();
    toggleSolution(true);
});

continueButton.addEventListener("click", (ev) => {
    ev.preventDefault();
    newPair();
});

document.addEventListener("keydown", function(event) {
    const isTextInput = event.target.tagName.toLowerCase() === "input" && event.target.type === "text";
    const formHidden = window.getComputedStyle(form).display === "none";

    if (event.key === "Enter") {
        event.preventDefault();

        if (formHidden) {
            continueButton?.click();
        } else if (isTextInput) {
            submitButton?.click();
        }
    }

    if (event.key === "Backspace" && event.shiftKey) {
        event.preventDefault();
        skipButton?.click();
    }
});

