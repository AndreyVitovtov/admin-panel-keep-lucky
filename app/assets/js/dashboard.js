document.addEventListener('DOMContentLoaded', function () {
    const inputCountry = document.getElementById('country');
    const inputRegion = document.getElementById('region');
    const inputCity = document.getElementById('city');

    inputCountry.addEventListener('change', function () {
        $('#region').val('').selectpicker('val', '');
        $('#city').val('').selectpicker('val', '');
        console.log(inputCountry.value);
    });

    inputRegion.addEventListener('change', function () {
        $('#country').val('').selectpicker('val', '');
        $('#city').val('').selectpicker('val', '');
        console.log(inputRegion.value);
    });

    inputCity.addEventListener('change', function () {
        $('#country').val('').selectpicker('val', '');
        $('#region').val('').selectpicker('val', '');
        console.log(inputCity.value);
    });
});