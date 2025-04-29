document.addEventListener('DOMContentLoaded', function () {
    let form = document.getElementById('accessForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            document.querySelectorAll('.generated-access').forEach(el => el.remove());

            const appId = document.querySelector('select#applicationId').value;
            let inputAppId = document.createElement('input');
            inputAppId.type = 'hidden';
            inputAppId.name = 'applicationId';
            inputAppId.value = appId;
            inputAppId.classList.add('generated-access');
            form.appendChild(inputAppId);

            const adminId = document.querySelector('select#adminId').value;
            let inputAdminId = document.createElement('input');
            inputAdminId.type = 'hidden';
            inputAdminId.name = 'adminId';
            inputAdminId.value = adminId;
            inputAdminId.classList.add('generated-access');
            form.appendChild(inputAdminId);

            const checkedAccesses = document.querySelectorAll('#accessForm input[type="checkbox"]:checked');
            checkedAccesses.forEach((checkbox, index) => {
                let inputController = document.createElement('input');
                inputController.type = 'hidden';
                inputController.name = `accesses[${index}][controller]`;
                inputController.value = checkbox.dataset.controller;
                inputController.classList.add('generated-access');
                form.appendChild(inputController);

                let inputMethod = document.createElement('input');
                inputMethod.type = 'hidden';
                inputMethod.name = `accesses[${index}][method]`;
                inputMethod.value = checkbox.dataset.method;
                inputMethod.classList.add('generated-access');
                form.appendChild(inputMethod);
            });

            form.submit();
        });
    }
});