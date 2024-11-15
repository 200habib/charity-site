import './bootstrap.js';

const savedFontSize = localStorage.getItem('fontSize');
    if (savedFontSize) {
        document.documentElement.style.setProperty('--font-size', savedFontSize + 'px');
        let savedFontSizeH1= savedFontSize * 1.2
        document.documentElement.style.setProperty('--font-size-h1', savedFontSizeH1+ 'px');
    }

import './styles/styles.css';
import './javascript/darkMode.js';
import './javascript/menuburger.js';
import './javascript/footer.js';
// import './javascript/cursor.js';
import './javascript/cursorStorage.js';
import './javascript/home_profile.js';
import './javascript/ContrastAccessibility.js';
// import './javascript/filtre.js';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');