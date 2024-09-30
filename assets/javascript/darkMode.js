import "./buttonToggle.js";


const themeToggleButton = document.getElementById('theme-toggle');
const darkModeImages = document.querySelectorAll('img[data-dark-src]');

verifyDarkMode();

themeToggleButton.addEventListener("click", () => {
    toggleDarkMode();
    toggleButtonDisplayMode();
});

function toggleDarkMode() {
    const isDarkMode = localStorage.getItem("darkmode") === "true" || false;
    if (isDarkMode) {
        setLightMode();
    } else {
        setDarkMode();
    }
    localStorage.setItem("darkmode", !isDarkMode ? "true" : "false");
}

function verifyDarkMode() {
    const isDarkMode = localStorage.getItem("darkmode") === "true" || false;
    if (isDarkMode) {
        setDarkMode();
    } else {
        setLightMode();
    }
}

function setDarkMode() {
    document.documentElement.setAttribute('data-theme', 'dark');
    darkModeImages.forEach(img => {
        const darkSrc = img.getAttribute('data-dark-src');
        if (darkSrc) {
            img.src = darkSrc;
            console.log("Dark mode image URL:", darkSrc);
        }
    });
}

function setLightMode() {
    document.documentElement.removeAttribute('data-theme');
    darkModeImages.forEach(img => {
        const lightSrc = img.getAttribute('data-light-src');
        if (lightSrc) {
            img.src = lightSrc;
            console.log("Light mode image URL:", lightSrc);
        }
    });
}

function toggleButtonDisplayMode() {
    themeToggleButton.classList.toggle('flex-mode');
}
