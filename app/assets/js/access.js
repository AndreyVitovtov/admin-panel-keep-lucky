document.addEventListener('DOMContentLoaded', function () {
    let form = document.getElementById('accessForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            document.querySelectorAll('.generated-access').forEach(el => el.remove());

            const adminId = document.querySelector('select#adminId').value;
            let inputAdminId = document.createElement('input');
            inputAdminId.type = 'hidden';
            inputAdminId.name = 'adminId';
            inputAdminId.value = adminId;
            inputAdminId.classList.add('generated-access');
            form.appendChild(inputAdminId);

            const checkedAccesses = document.querySelectorAll('#accessForm input[type="checkbox"]:checked');
            checkedAccesses.forEach((checkbox, index) => {
                let inputSection = document.createElement('input');
                inputSection.type = 'hidden';
                inputSection.name = `accesses[${index}][section]`;
                inputSection.value = checkbox.dataset.section;
                inputSection.classList.add('generated-access');
                form.appendChild(inputSection);

                let inputOption = document.createElement('input');
                inputOption.type = 'hidden';
                inputOption.name = `accesses[${index}][option]`;
                inputOption.value = checkbox.dataset.option;
                inputOption.classList.add('generated-access');
                form.appendChild(inputOption);
            });

            form.submit();
        });
    }
});