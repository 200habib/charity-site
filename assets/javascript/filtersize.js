const layoutToggle1x1 = document.getElementById('layout-toggle-1x1');
const layoutToggle1x2 = document.getElementById('layout-toggle-1x2');
const productList = document.querySelector('.productList');
const AddToBasket = document.querySelectorAll('.card-img-top');


productList.classList.remove('active');
AddToBasket.forEach(button => button.classList.remove('active'));

layoutToggle1x1.addEventListener('click', function(event) {
    event.preventDefault();
    productList.classList.add('active');
    AddToBasket.forEach(button => button.classList.add('active'));
});

layoutToggle1x2.addEventListener('click', function(event) {
    event.preventDefault();
    productList.classList.remove('active');
    AddToBasket.forEach(button => button.classList.remove('active'));
});
