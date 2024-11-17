document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.product__quantity-controls').forEach(container => {
        const decrementButton = container.querySelector('.product__quantity-button:first-of-type');
        const incrementButton = container.querySelector('.product__quantity-button:last-of-type');
        const inputField = container.querySelector('.product__quantity-value');
        const form = container.closest('form');

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

        form.addEventListener('submit', (event) => {
            const quantityValue = Number(inputField.value);
            if (isNaN(quantityValue) || quantityValue < 1) {
                inputField.value = 1;
            }
        });
    });
});
