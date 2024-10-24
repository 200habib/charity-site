const cursorEnabled = localStorage.getItem('customCursor');
const cursor = document.querySelector('.cursor');

cursor.style.display= "none"

if (cursorEnabled !== null) {
    
    document.addEventListener('mousemove', (e) => {
        cursor.style.left = `${e.clientX}px`;
        cursor.style.top = `${e.clientY}px`;
    });
}