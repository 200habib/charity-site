document.addEventListener('DOMContentLoaded', function () {
    const unitTypeSelect = document.querySelector('.product__unitType');
    const volumeField = document.querySelector('.product__volume');
    const weightField = document.querySelector('.product__weight');

    const inputVolumeLitre = document.getElementById('product_volumeLitre');
    const inputWeight = document.getElementById('product_weight');
    
if (volumeField) {
    volumeField.style.display = 'none';
    weightField.style.display = 'block';

    function handleUnitTypeChange() {
        if (unitTypeSelect.value === 'weight') {
            weightField.style.display = 'block';
            volumeField.style.display = 'none';
        } else if (unitTypeSelect.value === 'volumeLitre') {
            weightField.style.display = 'none';
            volumeField.style.display = 'block';
        }
    }
    unitTypeSelect.addEventListener('change', handleUnitTypeChange);
}



});
