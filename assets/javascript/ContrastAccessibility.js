// export function initializeSettings() {
//     updateDisplaySettings();
// }

// const contrastToggleButton = document.querySelector('.menu__appearance-settings_toggle');
// const contrastToggleText = document.querySelector('.menu__appearance-settings__title_active');

// let isHighContrastEnabled = localStorage.getItem("contraste") === "true";
// let isDarkModeEnabled = localStorage.getItem("darkmode") === "true";

// console.log(`High contrast enabled: ${isHighContrastEnabled}`);
// console.log(`Dark mode enabled: ${isDarkModeEnabled}`);

// function resetDisplaySettings() {

//     contrastToggleButton.style.display = isDarkModeEnabled ? "block" : "none"

//     console.log("Updating display settings...  " + contrastToggleButton.style.display + "  iniziale");

//     let isHighContrastEnabled = localStorage.getItem("contraste") === "true";

//     console.log(isHighContrastEnabled);
    

    

//         if (isHighContrastEnabled == false) {
//         document.documentElement.style.setProperty('--text-color', '#ffffff');
//         console.log('Contrasto elevato disattivato: colore testo bianco click');  
//         // localStorage.setItem("contraste", "true");
        
//     } else {
//         document.documentElement.style.setProperty('--text-color', '#9b9b9b');
//         console.log('Contrasto elevato attivato: colore testo grigio click');
//         // localStorage.setItem("contraste", "false");
//     }




//     console.log(isHighContrastEnabled);
    

//     let isdarkmodeEnabled = localStorage.getItem("darkmode") === "true";

//     if (isHighContrastEnabled == true && isdarkmodeEnabled == true) {
//         document.documentElement.style.setProperty('--text-color', '#9b9b9b');
        
//         console.log('Contrasto elevato disattivato: colore testo ');
//         // localStorage.setItem("contraste", "false");
//     } else {
//         document.documentElement.style.removeProperty('--text-color');
//         console.log('Contrasto elevato attivato: colore testo grigio');
//         // localStorage.setItem("contraste", "true");
//     }
    
// }

// resetDisplaySettings()

// function updateDisplaySettings() {
    
//     let isDarkModeEnabled = localStorage.getItem("darkmode") === "true";

//     if (isDarkModeEnabled) {
//         console.log(isDarkModeEnabled + " none");

//         contrastToggleButton.style.display = "none";
//         document.documentElement.style.removeProperty('--text-color');
//         localStorage.setItem("contraste", "true");
        
//     } else {
//         console.log(isDarkModeEnabled + " block");
//         contrastToggleButton.style.display = "block";
//         localStorage.setItem("contraste", "false");
//     }
// }









// const themeToggleContraste = document.getElementById('theme-toggle-contraste');




// contrastToggleButton.addEventListener('click', function() {
//     console.log("ciao bellezzza");


//     let isHighContrastEnabled = localStorage.getItem("contraste") === "true";

//     console.log(isHighContrastEnabled);
    

    

//     if (isHighContrastEnabled == false) {
//         document.documentElement.style.setProperty('--text-color', '#ffffff');
//         console.log('Contrasto elevato disattivato: colore testo bianco click');  
//         localStorage.setItem("contraste", "true");
        
//     } else {
//         document.documentElement.style.setProperty('--text-color', '#9b9b9b');
//         console.log('Contrasto elevato attivato: colore testo grigio click');
//         localStorage.setItem("contraste", "false");
//     }

//     if ( themeToggleContraste.style.flexDirection == "row-reverse") {
//         themeToggleContraste.style.flexDirection = "row"
//     } else {
//         themeToggleContraste.style.flexDirection = "row-reverse"
//     }
// });




// // document.addEventListener('DOMContentLoaded', () => {
// //     updateDisplaySettings();
// // });
