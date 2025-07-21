document.addEventListener('DOMContentLoaded', function () {
    const inputCountry = document.getElementById('country');
    const inputRegion = document.getElementById('region');
    const inputCity = document.getElementById('city');

    inputCountry.addEventListener('change', async function () {
        $('#region').val('').selectpicker('val', '');
        $('#city').val('').selectpicker('val', '');
        await updateLocation();
    });

    inputRegion.addEventListener('change', async function () {
        $('#country').val('').selectpicker('val', '');
        $('#city').val('').selectpicker('val', '');
        await updateLocation();
    });

    inputCity.addEventListener('change', async function () {
        $('#country').val('').selectpicker('val', '');
        $('#region').val('').selectpicker('val', '');
        await updateLocation();
    });

    document.querySelectorAll('.shop-checkbox').forEach((el) => {
        el.addEventListener('change', function () {
            updateShops();
        });
    });

    document.querySelectorAll('.apk-checkbox').forEach((el) => {
        el.addEventListener('change', function () {
            updateApk();
        });
    });

    document.querySelector('#referral-code').addEventListener('blur', function () {
        updateReferralCode();
    });

    document.getElementById('clear-filters').addEventListener('click', async function () {
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
    let elVolumeOfTrafficSold = document.querySelector('.volume-of-traffic-sold');
    let elTotalAmountToBePaidForTraffic = document.querySelector('.total-amount-to-be-paid-for-traffic');

    elNumberOfUsers.innerHTML = data.total_users ?? '-';
    elNumberOfUsersOnline.innerHTML = data.total_online_users ?? '-';
    elVolumeOfTrafficSold.innerHTML = data.total_traffic ?? '-';
    elTotalAmountToBePaidForTraffic.innerHTML = data.total_cost ?? '-';
}