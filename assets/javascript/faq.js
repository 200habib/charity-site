document.addEventListener('DOMContentLoaded', () => {
    const faqItems = document.querySelectorAll('.faq__item');

    faqItems.forEach(item => {
        const header = item.querySelector('.faq__header');

        header.addEventListener('click', () => {
            item.classList.toggle('active');
        });
    });
});
