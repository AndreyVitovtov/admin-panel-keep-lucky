document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('filter-form');
    const onlineCheckbox = document.getElementById('online');

    if (onlineCheckbox) {
        onlineCheckbox.addEventListener('change', () => {
            form.submit();
        });
    }

    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            form.submit();
        });
    });

    document.querySelectorAll('tbody tr').forEach(tr => {
        tr.addEventListener('click', () => {
            window.location.href = '/users/details/' + tr.dataset.id;
        });
    });

    $(document).ready(function () {
        $('#table-users').DataTable({
            pageLength: 100
        });
    });
});