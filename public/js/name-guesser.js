const CHECK_ENDPOINT = "/api/check-constellation-name";
const INCREASE_STAT_ENDPOINT = "/api/increase-stat";

const form = document.querySelector("form");
const inputField = form.querySelector("input");
const submitButton = form.querySelector("button");

const timeSpan = document.querySelector("#time");
const guessCountSpan = document.querySelector("#guess-count");

let guessedCount = 0;
let guessed = {};
let timerStart = null;
let timerId = null;

function requestStatIncrease(isGameEnd) {
    let property = isGameEnd ? "success" : "total";
    let xhr = new XMLHttpRequest();
    xhr.open("POST", INCREASE_STAT_ENDPOINT, true);
    xhr.send(JSON.stringify({
        stat: "name-guess",
        property: property
    }));
}

function formatTime(ms) {
    const minutes = Math.floor(ms / 60000);
    const seconds = Math.floor((ms % 60000) / 1000);
    const milliseconds = Math.floor((ms % 1000) / 10);
    return `${minutes}:${seconds.toString().padStart(2, '0')}.${milliseconds.toString().padStart(2, '0')}`;
}

function updateTimer() {
    const elapsed = Date.now() - timerStart;
    timeSpan.textContent = formatTime(elapsed);
    if (guessedCount < 88) {
        timerId = requestAnimationFrame(updateTimer);
    }
}

function startTimer() {
    timerStart = Date.now();
    updateTimer();
}

function stopTimer() {
    if (timerId) {
        cancelAnimationFrame(timerId);
        timerId = null;
    }
}

function gameStart() {
    startTimer();
    requestStatIncrease(false);
}

function gameEnd() {
    stopTimer();
    requestStatIncrease(true);
}

function getConstellationLi(index) {
    return document.querySelector(`#all-constellations #const_${index}`);
}

function correctLi(li, value, index) {
    li.textContent = `${index}. ${value}`;
    li.classList.add("correct");
}

let markCooldown = false;
function markInput(correct) {
    if (markCooldown) return;
    markCooldown = true;

    const className = correct ? "correct" : "incorrect";
    inputField.classList.add(className);
    submitButton.classList.add(className);

    setTimeout(() => {
        inputField.classList.remove(className);
        submitButton.classList.remove(className);
        markCooldown = false;
    }, 500);
}

function handleSuccess(data) {
    if (guessed[data.name]) return;

    if (guessedCount === 0) gameStart();

    guessed[data.name] = true;
    guessedCount++;

    const li = getConstellationLi(data.index);
    correctLi(li, data.name, data.index);
    inputField.value = "";
    guessCountSpan.textContent = `${guessedCount}/88`;

    if (guessedCount === 88) gameEnd();

    markInput(true);
}

form.addEventListener("submit", (event) => {
    event.preventDefault();
    const name = inputField.value.trim();

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `${CHECK_ENDPOINT}?input_name=${encodeURIComponent(name)}`, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = () => {
        if (xhr.readyState !== XMLHttpRequest.DONE) return;

        try {
            const data = JSON.parse(xhr.responseText);
            if (data !== null) {
                handleSuccess(data);
            } else {
                markInput(false);
            }
        } catch (e) {
            console.log("Response text:", xhr.responseText);
            console.log("Error:", e);
        }
    };

    xhr.send();
});
