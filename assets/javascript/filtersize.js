const layoutToggle1x1 = document.getElementById('layout-toggle-1x1');
const layoutToggle1x2 = document.getElementById('layout-toggle-1x2');
const productList = document.querySelector('.productList');
const AddToBasket = document.querySelector('.productList__AddToBasket');

productList.classList.remove('active'); 
// AddToBasket.classList.remove('active'); 


layoutToggle1x1.addEventListener('click', function(event) {
    event.preventDefault();
    productList.classList.add('active');
    // AddToBasket.classList.add('active');
});

layoutToggle1x2.addEventListener('click', function(event) {
    event.preventDefault();
    productList.classList.remove('active');
    // AddToBasket.classList.remove('active');
});
