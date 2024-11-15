const rolesCheckboxes = document.querySelectorAll('#user2_roles_0, #user2_roles_1, #user2_roles_2');
const userProfileGroup = document.getElementById('userProfileGroup');
const companyGroup = document.getElementById('companyGroup');

const selectedValues = [];

function updateFieldVisibility() {
    selectedValues.length = 0;

    rolesCheckboxes.forEach((cb) => {
        if (cb.checked) {
            selectedValues.push(cb.value);
        }
    });

    if (selectedValues.length === 0) {
        rolesCheckboxes[0].checked = true; 
        selectedValues.push(rolesCheckboxes[0].value);
    }

    userProfileGroup.style.display = (selectedValues.length === 1 && selectedValues.includes('ROLE_USER')) ? 'block' : 'none';

    companyGroup.style.display = selectedValues.includes('ROLE_CHARITY_ASSOCIATION') || selectedValues.includes('ROLE_SELLER') ? 'block' : 'none';

    console.log(selectedValues);
}

updateFieldVisibility();

rolesCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
            if (checkbox.value === 'ROLE_SELLER') {
                document.querySelector('#user2_roles_2').checked = false; 
            }
            else if (checkbox.value === 'ROLE_CHARITY_ASSOCIATION') {
                document.querySelector('#user2_roles_0').checked = false; 
            }
        }
        updateFieldVisibility();
    });
});
