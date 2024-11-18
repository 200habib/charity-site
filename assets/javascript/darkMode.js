import "./buttonToggle.js";

const themeToggleButtons = document.querySelectorAll('.theme-toggle');
const darkModeImages = document.querySelectorAll('img[data-dark-src]');

const darkModetextContent = document.querySelector('.menu__appearance-textContent');

verifyDarkMode();


themeToggleButtons.forEach(button => {
    button.addEventListener("click", () => {
        toggleDarkMode();
        toggleButtonDisplayMode(button);
    });
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
    darkModetextContent.textContent = "activer le mode clair"
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
    darkModetextContent.textContent = "activer le mode sombre"
    document.documentElement.removeAttribute('data-theme');
    
    darkModeImages.forEach(img => {
        const lightSrc = img.getAttribute('data-light-src');
        if (lightSrc) {
            img.src = lightSrc;
            console.log("Light mode image URL:", lightSrc);
        }
    });
}

function toggleButtonDisplayMode(button) {
    button.classList.toggle('flex-mode');
}
