// Select all theme toggle buttons with the class 'theme-toggle'
const themeToggleButtons = document.querySelectorAll('.theme-toggle');
const element1 = document.querySelector('.navbar__shopping-icon');
const element2 = document.querySelector('#id-or-class2');
const element3 = document.querySelector('#id-or-class3');

// Function to set initial flex direction based on dark mode
function know() {
    const isDarkMode = localStorage.getItem("darkmode") === "true" || false;
    
    themeToggleButtons.forEach(button => {
        if (isDarkMode) {
            button.style.flexDirection = "row-reverse";
        } else {
            button.style.flexDirection = "row";
        }
    });
}

// Initialize the flex direction
know();

// Event listener for each toggle button
themeToggleButtons.forEach(button => {
    button.addEventListener('click', function() {
        if (button.style.flexDirection === "row-reverse") {
            button.style.flexDirection = "row";
        } else {
            button.style.flexDirection = "row-reverse";
        }

        // Optionally initialize other settings
        // initializeSettings();
    });
});
