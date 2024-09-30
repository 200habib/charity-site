let themeToggle = document.getElementById('theme-toggle')
const element1 = document.querySelector('.navbar__shopping-icon');
const element2 = document.querySelector('#id-or-class2');
const element3 = document.querySelector('#id-or-class3');

function know() {
    const imageSrc = element1
    
    const isDarkMode = localStorage.getItem("darkmode") === "true" || false;
    if (isDarkMode) {
        themeToggle.style.flexDirection = "row-reverse"
    } else {
        themeToggle.style.flexDirection = "row"
    }
    
}

know();


themeToggle.addEventListener('click', function() {
    if ( themeToggle.style.flexDirection == "row-reverse") {
        themeToggle.style.flexDirection = "row"
    } else {
        themeToggle.style.flexDirection = "row-reverse"
    }





  console.log('click');
});
