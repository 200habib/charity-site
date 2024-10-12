import './bootstrap.js';

const savedFontSize = localStorage.getItem('fontSize');
    if (savedFontSize) {
        document.documentElement.style.setProperty('--font-size', savedFontSize + 'px');
        let savedFontSizeH1= savedFontSize * 1.2
        document.documentElement.style.setProperty('--font-size-h1', savedFontSizeH1+ 'px');
    }
    
// import './styles/app.css';
import './styles/styles.css';
import './javascript/darkMode.js';
import './javascript/menuburger.js';
import './javascript/faq.js';
import './javascript/product.js';
import './javascript/Accessibility.js';


console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');