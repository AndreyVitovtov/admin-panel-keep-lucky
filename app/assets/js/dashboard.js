document.addEventListener('DOMContentLoaded', function () {
    const inputCountry = document.getElementById('country');
    const inputRegion = document.getElementById('region');
    const inputCity = document.getElementById('city');

    inputCountry.addEventListener('change', async function () {
        await loader();
        $('#region').val('').selectpicker('val', '');
        $('#city').val('').selectpicker('val', '');
        await updateLocation();
    });

    inputRegion.addEventListener('change', async function () {
        await loader();
        $('#country').val('').selectpicker('val', '');
        $('#city').val('').selectpicker('val', '');
        await updateLocation();
    });

    inputCity.addEventListener('change', async function () {
        await loader();
        $('#country').val('').selectpicker('val', '');
        $('#region').val('').selectpicker('val', '');
        await updateLocation();
    });

    document.querySelectorAll('.shop-checkbox').forEach((el) => {
        el.addEventListener('change', async function () {
            await loader();
            updateShops();
        });
    });

    document.querySelectorAll('.apk-checkbox').forEach((el) => {
        el.addEventListener('change', async function () {
            await loader();
            updateApk();
        });
    });

    let inputReferralCode = document.querySelector('#referral-code');
    if (inputReferralCode) {
        inputReferralCode.addEventListener('blur', async function () {
            await loader();
            updateReferralCode();
        });
    }

    document.getElementById('clear-filters').addEventListener('click', async function () {
        await loader();
        $('#country').val('').selectpicker('val', '');
        $('#region').val('').selectpicker('val', '');
        $('#city').val('').selectpicker('val', '');
        document.getElementById('date-from').value = '';
        document.getElementById('date-to').value = '';
        await updateLocation();
    });

    document.getElementById('date-from').addEventListener('change', async function () {
        await updateDate();
    });

    document.getElementById('date-to').addEventListener('change', async function () {
        await updateDate();
    });
});

function updateSorted(value) {
    loader();
    $.post(
        '/api/updateSorted',
        {sortedBy: value},
        async function (data) {
            await updateDataTraffic(data);
        }
    );
}

function updateShops() {
    let shops = [];
    document.querySelectorAll('.shop-checkbox').forEach((el) => {
        if (el.checked) shops.push(el.value);
    });
    $.post(
        '/api/updateShops',
        {shops},
        async function (data) {
            await updateDataTraffic(data);
            renderChart('countryChart', usersByCountries, data['usersByCountries']);
            renderChart('regionChart', usersByRegions, data['usersByRegions']);
            renderChart('cityChart', usersByCities, data['usersByCities']);
        }
    );
}

function updateApk() {
    let apk = [];
    document.querySelectorAll('.apk-checkbox').forEach((el) => {
        if (el.checked) apk.push(el.value);
    });
    $.post(
        '/api/updateApk',
        {apk},
        async function (data) {
            await updateDataTraffic(data);
            renderChart('countryChart', usersByCountries, data['usersByCountries']);
            renderChart('regionChart', usersByRegions, data['usersByRegions']);
            renderChart('cityChart', usersByCities, data['usersByCities']);
        }
    );
}

function updateReferralCode() {
    let elReferralCode = document.getElementById('referral-code');
    let referralCode = elReferralCode.value;
    $.post(
        '/api/updateReferralCode',
        {referralCode},
        async function (data) {
            await updateDataTraffic(data);
            renderChart('countryChart', usersByCountries, data['usersByCountries']);
            renderChart('regionChart', usersByRegions, data['usersByRegions']);
            renderChart('cityChart', usersByCities, data['usersByCities']);
        }
    );
}

async function updateLocation() {
    let elCountry = document.getElementById('country');
    let elRegion = document.getElementById('region');
    let elCity = document.getElementById('city');

    let country = elCountry.value;
    let region = elRegion.value;
    let city = elCity.value;

    $.post(
        '/api/updateLocation',
        {country, region, city},
        await function (data) {
            updateDataTraffic(data);
        }
    );
}

async function updateDate() {
    let elDateFrom = document.getElementById('date-from');
    let elDateTo = document.getElementById('date-to');

    if (elDateFrom.value === '' || elDateTo.value === '') return;

    await loader();

    let dateFrom = elDateFrom.value;
    let dateTo = elDateTo.value;

    $.post(
        '/api/updateDate',
        {dateFrom, dateTo},
        await function (data) {
            updateDataTraffic(data);
        }
    );
}

async function updateDataTraffic(data) {
    data = JSON.parse(data);

    console.log(data);
    let elNumberOfUsers = document.querySelector('.number-of-users');
    let elNumberOfUsersOnline = document.querySelector('.number-of-users-online');
    let elTableTrafficStats = document.querySelector('.table-traffic-stats');

    elNumberOfUsers.innerHTML = data.total_users ?? '-';
    elNumberOfUsersOnline.innerHTML = data.total_online_users ?? '-';
    elTableTrafficStats.innerHTML = data.tableTrafficStats ?? '-';
}

async function loader(elements = []) {
    function createLoader() {
        let divLoader = document.createElement('div');
        divLoader.className = 'loader';
        for (let i = 0; i < 3; i++) {
            let divRect = document.createElement('div');
            divRect.className = 'rect';
            divLoader.appendChild(divRect);
        }
        return divLoader;
    }

    if (elements.length === 0) {
        let elNumberOfUsers = document.querySelector('.number-of-users');
        elNumberOfUsers.innerHTML = '';
        elNumberOfUsers.appendChild(createLoader());

        let elNumberOfUsersOnline = document.querySelector('.number-of-users-online');
        elNumberOfUsersOnline.innerHTML = '';
        elNumberOfUsersOnline.appendChild(createLoader());

        let elTableTrafficStats = document.querySelector('.table-traffic-stats');
        elTableTrafficStats.innerHTML = '';
        elTableTrafficStats.appendChild(createLoader());
    } else {
        elements.forEach(el => {
            el.innerHTML = '';
            el.appendChild(createLoader());
        });
    }
}