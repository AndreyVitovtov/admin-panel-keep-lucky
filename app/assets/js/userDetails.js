document.addEventListener('DOMContentLoaded', function () {
    let userBlock = $('.user-block');
    let id = userBlock.data('id');
    userBlock.click((e) => {
        userBlock.toggleClass('unlock').toggleClass('lock');
        let type;
        if (userBlock.hasClass('unlock')) type = 'unlock';
        else type = 'lock';

        $.post('/users/block',
            {
                type: type,
                id: id,
            },
            function (data) {
                console.log(data);
            }
        );
    });
});