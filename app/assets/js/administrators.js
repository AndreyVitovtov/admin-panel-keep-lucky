document.addEventListener('DOMContentLoaded', function () {
    let shops = [];
    let apks = [];
    let referralCode = '';

    const role = document.getElementById('role');
    let adminId = role.dataset.admin_id;
    if (!role) return;

    const containerDiv = role.closest('div.mb-3');

    function renderShopsAndApks() {
        document.querySelectorAll('.shop-wrapper').forEach(el => el.remove());

        $.post('/api/getShopsAndApk', {
            'adminId': adminId,
        }, function (data) {
            const elDiv = document.createElement('div');
            elDiv.classList.add('mt-3', 'shop-wrapper');

            const rowDiv = document.createElement('div');
            rowDiv.classList.add('row');

            if (data.shops?.length) {
                const colShops = document.createElement('div');
                colShops.classList.add('col-md-6');

                const shopLabel = document.createElement('label');
                shopLabel.textContent = '*Shops:';
                shopLabel.classList.add('form-label', 'mb-2', 'd-block');
                colShops.appendChild(shopLabel);

                data.shops.forEach((shop, k) => {
                    const container = document.createElement('div');
                    container.classList.add('form-check', 'd-flex', 'align-items-center', 'mb-2', 'gap-2');

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'shop[]';
                    checkbox.value = shop;
                    checkbox.id = 'shop-' + k;
                    checkbox.classList.add('form-check-input', 'mt-0');
                    if (shops.includes(shop)) checkbox.checked = true;

                    const label = document.createElement('label');
                    label.setAttribute('for', checkbox.id);
                    label.textContent = shop;
                    label.classList.add('form-check-label', 'ms-2');

                    container.appendChild(checkbox);
                    container.appendChild(label);
                    colShops.appendChild(container);
                });

                rowDiv.appendChild(colShops);
            }

            if (data.apk?.length) {
                const colApk = document.createElement('div');
                colApk.classList.add('col-md-6');

                const apkLabel = document.createElement('label');
                apkLabel.textContent = '*Apk:';
                apkLabel.classList.add('form-label', 'mb-2', 'd-block');
                colApk.appendChild(apkLabel);

                data.apk.forEach((apk, k) => {
                    const container = document.createElement('div');
                    container.classList.add('form-check', 'd-flex', 'align-items-center', 'mb-2', 'gap-2');

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'apk[]';
                    checkbox.value = apk;
                    checkbox.id = 'apk-' + k;
                    checkbox.classList.add('form-check-input', 'mt-0');
                    if (apks.includes(apk)) checkbox.checked = true;

                    const label = document.createElement('label');
                    label.setAttribute('for', checkbox.id);
                    label.textContent = apk;
                    label.classList.add('form-check-label', 'ms-2');

                    container.appendChild(checkbox);
                    container.appendChild(label);
                    colApk.appendChild(container);
                });

                rowDiv.appendChild(colApk);
            }

            elDiv.appendChild(rowDiv);

            const referralDiv = document.createElement('div');
            referralDiv.classList.add('mb-3', 'mt-2');

            const referralLabel = document.createElement('label');
            referralLabel.setAttribute('for', 'referralCode');
            referralLabel.textContent = 'Referral Code:';
            referralLabel.classList.add('form-label');

            const referralInput = document.createElement('input');
            referralInput.type = 'text';
            referralInput.id = 'referralCode';
            referralInput.name = 'referralCode';
            referralInput.classList.add('form-control');
            referralInput.value = referralCode;

            referralDiv.appendChild(referralLabel);
            referralDiv.appendChild(referralInput);

            elDiv.appendChild(referralDiv);

            containerDiv.insertAdjacentElement('afterend', elDiv);
        }, 'json');
    }

    const selectedText = role.options[role.selectedIndex].text;
    if (selectedText === 'admin') {
        renderShopsAndApks();
    }

    role.addEventListener('change', function (e) {
        const selectedText = e.target.options[e.target.selectedIndex].text;

        if (selectedText === 'superadmin') {
            apks = [];
            shops = [];
            document.querySelectorAll('input[name="apk[]"]:checked').forEach(el => {
                apks.push(el.value);
            });

            document.querySelectorAll('input[name="shop[]"]:checked').forEach(el => {
                shops.push(el.value);
            });

            referralCode = document.getElementById('referralCode').value;
        }

        document.querySelectorAll('.shop-wrapper').forEach(el => el.remove());

        if (selectedText === 'admin') {
            renderShopsAndApks();
        }
    });
});
