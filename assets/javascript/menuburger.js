const menu = document.querySelector('.menu');
const toggleButton = document.getElementById('navbar__icon_open_menu');
const closeMenuIcon = document.getElementById('navbar__icon_close');

closeMenuIcon.style.display = 'none';

toggleButton.addEventListener('click', function() {
    if (menu) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        menu.classList.add('open');
        document.body.style.overflow = 'hidden';

        toggleButton.style.display = 'none';
        closeMenuIcon.style.display = 'block';
    }
});

closeMenuIcon.addEventListener('click', function() {
    if (menu) {
        menu.classList.remove('open');
        document.body.style.overflow = '';

        closeMenuIcon.style.display = 'none';
        toggleButton.style.display = 'block';
    }
});
