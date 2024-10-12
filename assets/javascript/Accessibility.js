document.addEventListener('DOMContentLoaded', function () {
    const rangeInput = document.getElementById('range');
    const rootElement = document.documentElement; 
    const resetButton = document.getElementById('resetButton');
    const saveButton = document.getElementById('saveButton');

    function updateFontSize(value) {
        rootElement.style.setProperty('--font-size', `${value}px`);
    }

    rangeInput.addEventListener('input', function () {
        updateFontSize(rangeInput.value);
    });

    resetButton.addEventListener('click', function () {
        rangeInput.value = 20;
        updateFontSize(20);
    });

    saveButton.addEventListener('click', function () {
        localStorage.setItem('fontSize', rangeInput.value);
        alert('Préférence de taille de texte sauvegardée.');
    });

    
    const savedFontSize = localStorage.getItem('fontSize');
    if (savedFontSize) {
        rangeInput.value = savedFontSize;
        updateFontSize(savedFontSize);
    } else {
        updateFontSize(rangeInput.value);
    }
});
