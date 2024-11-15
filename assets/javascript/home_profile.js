document.addEventListener('DOMContentLoaded', () => {
    const profileImage = document.querySelector('.navbar__show_menu_profile');
    const profileMenu = document.getElementById('profileMenu');

    if (profileImage) {
        profileImage.addEventListener('click', () => {
            profileMenu.classList.toggle('active');
        });
    }

});
