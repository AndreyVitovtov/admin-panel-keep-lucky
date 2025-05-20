document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('filter-form');
    let pageInput;
    if (form) {
        pageInput = form.querySelector('input[name="page"]');
    }
    const onlineCheckbox = document.getElementById('online');

    if (onlineCheckbox) {
        onlineCheckbox.addEventListener('change', () => {
            pageInput.value = 1;
            form.submit();
        });
    }

    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();

            const page = link.getAttribute('href').split('page=')[1];
            if (page) {
                pageInput.value = page;
                form.submit();
            }
        });
    });

    document.querySelectorAll('tbody tr').forEach(tr => {
        tr.addEventListener('click', () => {
            window.location.href = '/users/details/' + tr.dataset.id;
        });
    })
});