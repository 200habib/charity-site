const productCategoryFilter = document.querySelector('.product__category_filter');
const filters = document.querySelector('.filters');

productCategoryFilter.addEventListener('click', function(event) {
    if (filters && !filters.classList.contains('visible') && !filters.classList.contains('hidden')) {
        filters.classList.remove('hidden');
        filters.classList.add('visible');
    } else {
        filters.classList.remove('visible');
    }
});

document.addEventListener('click', function(event) {
    if (!productCategoryFilter.contains(event.target) && !filters.contains(event.target)) {
        filters.classList.remove('visible');
    }
});

