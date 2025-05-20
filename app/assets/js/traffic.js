document.addEventListener('DOMContentLoaded', function () {
    const inputCountry = document.getElementById('country');
    const inputRefCode = document.getElementById('refCode');

    ['keydown', 'change'].forEach(event => {
        inputCountry.addEventListener(event, function () {
            inputRefCode.value = '';
        });
    });

    ['keydown', 'change'].forEach(event => {
        inputRefCode.addEventListener(event, function () {
            inputCountry.value = '';
        });
    });
});