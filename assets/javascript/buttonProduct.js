document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.product__buton_basket_content').forEach(container => {
        const decrementButton = container.querySelector('.product__buton_basket:first-of-type');
        const incrementButton = container.querySelector('.product__buton_basket:last-of-type');
        const inputField = container.querySelector('.productList__total');

        decrementButton.addEventListener('click', (event) => {
            event.preventDefault();
            let currentValue = Number(inputField.value);
            if (isNaN(currentValue)) currentValue = 1;
            if (currentValue > 1) inputField.value = currentValue - 1;
        });

        incrementButton.addEventListener('click', (event) => {
            event.preventDefault();
            let currentValue = Number(inputField.value);
            if (isNaN(currentValue)) currentValue = 1;
            inputField.value = currentValue + 1;
        });

        inputField.addEventListener('input', () => {
            let value = Number(inputField.value);
            if (isNaN(value) || value < 1) value = 1;
            inputField.value = value;
        });
    });
});
