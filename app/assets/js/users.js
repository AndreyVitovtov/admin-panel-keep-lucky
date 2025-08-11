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
        let table = $('#table-users').DataTable({
            pageLength: 100,
            language: {
                search: window.languages.search,
                lengthMenu: window.languages.lengthMenu,
                info: window.languages.info,
            }
        });

        let columns = table.columns().header().toArray().map(h => $(h).text());

        let select = $('<select id="columnSelect"></select>')
            .append('<option value="all">All</option>');

        columns.forEach((name, index) => {
            select.append(`<option value="${index}">${name}</option>`);
        });

        let by = $('<span class="search-by">' + window.languages.by + '</span>');

        $('.dt-search').append(by);
        $('.dt-search').append(select);

        function applySearch() {
            let searchVal = $('.dt-search input').val();
            let col = $('#columnSelect').val();

            if (col === "all") {
                table.columns().search('');
                table.search(searchVal).draw();
            } else {
                table.columns().search('');
                table.column(col).search(searchVal).draw();
            }
        }

        $('.dt-search input').off().on('input', applySearch);
        $('#columnSelect').on('change', applySearch);
    });
});