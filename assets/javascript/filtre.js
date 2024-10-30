const productCategoryFilter = document.querySelector('.product__category_filter');

productCategoryFilter.addEventListener('click', function() {
    const filters = document.querySelector('.filters');
    
    if (filters && !filters.classList.contains('visible') && !filters.classList.contains('hidden')) {
      filters.classList.remove('hidden');
      filters.classList.add('visible');
    }
    else {
        filters.classList.remove('visible');
    }
  });
