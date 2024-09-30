// Selezione degli elementi dal DOM
const menu = document.querySelector('.menu');
const toggleButton = document.getElementById('navbar__icon_open_menu');
const closeMenuIcon = document.getElementById('navbar__icon_close');

// Nascondi l'icona di chiusura all'inizio
closeMenuIcon.style.display = 'none';

// Funzione per aprire il menu
toggleButton.addEventListener('click', function() {
    if (menu) {
        menu.classList.add('open'); // Aggiungi la classe 'open' per mostrare il menu
        document.body.style.overflow = 'hidden'; // Disabilita lo scroll del corpo

        // Alterna la visibilità delle icone
        toggleButton.style.display = 'none';
        closeMenuIcon.style.display = 'block';
    }
});

// Funzione per chiudere il menu
closeMenuIcon.addEventListener('click', function() {
    if (menu) {
        menu.classList.remove('open'); // Rimuovi la classe 'open' per nascondere il menu
        document.body.style.overflow = ''; // Ripristina lo scroll del corpo

        // Alterna la visibilità delle icone
        closeMenuIcon.style.display = 'none';
        toggleButton.style.display = 'block';
    }
});
