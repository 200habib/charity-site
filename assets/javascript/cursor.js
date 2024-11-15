
let cursor = document.getElementById('cursor');
if (!cursor) {
    cursor = document.createElement('div');
    cursor.id = 'cursor';
    document.body.appendChild(cursor);
}

let cursorEnabled = localStorage.getItem('customCursor') === 'true';
if (cursorEnabled === null) {
    cursorEnabled = false;
    localStorage.setItem('customCursor', 'false');
}

function toggleCursor() {
    cursorEnabled = !cursorEnabled;
    localStorage.setItem('customCursor', cursorEnabled ? 'true' : 'false');

    cursor.style.display = cursorEnabled ? 'block' : 'none';
    document.body.style.cursor = cursorEnabled ? 'none' : 'default';
}

cursor.style.display = cursorEnabled ? 'block' : 'none';
document.body.style.cursor = cursorEnabled ? 'none' : 'default';

const toggleButton = document.querySelector('.customCursor');
if (toggleButton) {
    toggleButton.addEventListener('click', toggleCursor);
}

document.addEventListener('mousemove', (e) => {
    if (cursorEnabled) {
        cursor.style.left = `${e.clientX}px`;
        cursor.style.top = `${e.clientY}px`;
    }
});
