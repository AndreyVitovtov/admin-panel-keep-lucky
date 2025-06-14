$(document).ready(() => {
    if ($('.mob').is(':visible')) {
        let header;
        header = $(`.content .header`);
        header.css('width', header.outerWidth());
    }

    $('.menu-button').click(() => {
        if ($('.menu.animate-left:visible').length === 0) {
            $('.menu').addClass('animate-left').removeClass('animate-right');
            $('.content').addClass('animate-left').removeClass('animate-right');
            setTimeout(() => {
                $('.menu-button i').removeClass('icon-cancel').addClass('icon-menu');
            }, 100);
        } else {
            $('.menu').addClass('animate-right').removeClass('animate-left');
            $('.content').addClass('animate-right').removeClass('animate-left');
            setTimeout(() => {
                $('.menu-button i').removeClass('icon-menu').addClass('icon-cancel');
            }, 100);
        }
    });

    $('.menu-item div').click((e) => {
        let menuRoll = $(e.target).closest('.menu-item').children('.menu-roll');
        if ($(menuRoll).hasClass('active')) $(menuRoll).css('max-height', '0');
        else {
            $('.menu-roll').each((i, el) => {
                if ($(el).hasClass('active')) {
                    $(el).css('max-height', '0');
                    $(el).closest('.menu-item').toggleClass('active');
                    $(el).closest('.menu-item').children('.menu-roll').toggleClass('active');
                    $(el).closest('.menu-item').children('.flex-between').children('.menu-angle').toggleClass('active');
                }
            });
            $(menuRoll).css('max-height', menuRoll[0].scrollHeight + "px");
        }
        $(menuRoll).toggleClass('active');
        $(e.target).closest('.menu-item').toggleClass('active');
        $(e.target).closest('.menu-item').children('.flex-between').children('.menu-angle').toggleClass('active');
    });

    $($('.menu-roll.activate:visible').closest('.menu-item').children('div')[0]).trigger('click');

    let alertMessage = $('.alert-message');
    if (alertMessage.length !== 0) {
        alertMessage.fadeIn('slow');
        setTimeout(() => alertMessage.fadeOut('slow'), 2500);
    }

    $('.form-confirm').submit((e) => {
        e.preventDefault();
        let title = $(e.target).data('title');
        let body = $(e.target).data('body');
        $('.modal .modal-title').html(title);
        $('.modal .modal-body').html(body);
        $('.modal').modal('show');
        $('.form-confirm-submit').data('id', $(e.target).data('id'));
    });

    $('.form-confirm-submit').click((e) => {
        document.getElementById($(e.target).data('id')).submit();
        $('.modal').modal('hide');
    });

    $('.theme').click(() => {
        $('.theme').toggleClass('dark').toggleClass('light');
    });
    
    function setupDropdownCheckboxes(allSelector, itemSelector, menuSelector) {
        const allCheckbox = document.querySelector(allSelector);
        const itemCheckboxes = document.querySelectorAll(itemSelector);

        if (!allCheckbox) return;

        allCheckbox.addEventListener('change', function () {
            itemCheckboxes.forEach(cb => cb.checked = allCheckbox.checked);
        });

        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                if (!cb.checked) {
                    allCheckbox.checked = false;
                } else {
                    const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                    allCheckbox.checked = allChecked;
                }
            });
        });

        const menu = document.querySelector(menuSelector);
        if (menu) {
            menu.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }
    }

    setupDropdownCheckboxes('.all-shops', 'input[name="shops[]"]', '.dropdown-menu.application');
    setupDropdownCheckboxes('.all-apk', 'input[name="apk[]"]', '.dropdown-menu.apk');
});