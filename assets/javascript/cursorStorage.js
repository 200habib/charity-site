
let cursor = document.getElementById('cursor');
if (!cursor) {
    cursor = document.createElement('div');
    cursor.id = 'cursor';
    document.body.appendChild(cursor);
}

function updateCursorVisibility() {
    const cursorEnabled = localStorage.getItem('customCursor') === 'true';

    cursor.style.display = cursorEnabled ? 'block' : 'none';

    document.body.style.cursor = cursorEnabled ? 'none' : 'default';

    document.addEventListener('mousemove', (e) => {
        if (window.innerWidth > 1000) { 
            if (cursorEnabled) {
                cursor.style.left = `${e.pageX}px`; 
                cursor.style.top = `${e.pageY}px`;   
            }
        }

    });
}

updateCursorVisibility();
