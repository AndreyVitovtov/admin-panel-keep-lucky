<!-- bootstrap-select CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css"
      rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<style>
    .traffic-filter {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        align-items: start;
        gap: 20px;
    }

    .filter-block {
        min-width: 200px;
    }

    .my-filter .filter-block {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex: 1 1 200px;
        min-width: 200px;
    }

    .my-filter .dropdown-menu.inner.show {
        border: none;
    }

    .dropdown-toggle {
        display: inline-flex;
        align-items: center; /* вертикальное центрирование */
        gap: 5px; /* расстояние между текстом и стрелкой */
        cursor: pointer;
        margin: 0;
        padding: 5px 0;
        user-select: none;
        position: relative;
    }

    /* Скрываем псевдоэлемент стрелки */
    .dropdown-toggle::after {
        content: none;
    }

    /* Стили для контейнера SVG стрелки */
    .arrow {
        display: inline-flex;
        transition: transform 0.3s ease;
        transform-origin: center center;
        width: 12px;
        height: 12px;
    }

    /* Вращение стрелки при активном состоянии */
    .dropdown-toggle.active .arrow {
        transform: rotate(180deg);
    }

    .dropdown-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .dropdown-content.open {
        max-height: 500px; /* ограничение по максимуму содержимого */
    }

    .bootstrap-select .bs-searchbox input.form-control {
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #ced4da !important;
        padding: 6px 10px !important;
        font-size: 0.875rem !important;
        height: auto !important;
    }

    .bootstrap-select .bs-searchbox {
        padding: 4px 8px !important;
    }

    .bootstrap-select .dropdown-menu {
        border-radius: 0.25rem !important;
        border: 1px solid #ced4da !important;
    }

    /* Основной контейнер фильтров */
    .traffic-filter,
    .traffic-filter-location {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        margin-bottom: 30px;
    }

    .filter-block {
        background-color: #f8f9fa;
        padding: 16px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        flex: 1 1 250px;
        min-width: 250px;
    }

    /* Заголовки с выпадающими стрелками */
    .filter-block .dropdown-toggle {
        font-size: 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        margin-bottom: 12px;
        padding-bottom: 6px;
        border-bottom: 1px solid #dee2e6;
    }

    .dropdown-toggle .arrow svg {
        transition: transform 0.3s ease;
    }

    .dropdown-toggle.active .arrow svg {
        transform: rotate(180deg);
    }

    .dropdown-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        padding-left: 4px;
    }

    .dropdown-content.open {
        max-height: 500px;
        padding-top: 8px;
    }

    .dropdown-content label {
        margin-left: 4px;
        font-size: 0.95rem;
    }

    /* Стили для selectpicker */
    .bootstrap-select .dropdown-toggle {
        border-radius: 6px !important;
        padding: 8px 12px !important;
        font-size: 0.95rem;
    }

    .bootstrap-select .bs-searchbox input.form-control {
        border-radius: 6px !important;
        box-shadow: none !important;
        border: 1px solid #ced4da !important;
        padding: 6px 10px !important;
        font-size: 0.875rem !important;
    }

    .bootstrap-select .bs-searchbox {
        padding: 8px !important;
    }

    .bootstrap-select .dropdown-menu {
        border-radius: 6px !important;
        border: 1px solid #dee2e6 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Отступы между выпадающими фильтрами */
    .container label {
        font-weight: 500;
        margin-bottom: 6px;
    }

    .container .form-select {
        padding: 8px 12px;
        font-size: 0.95rem;
        border-radius: 6px;
    }

    /* Медиа-запросы для адаптивности */
    @media (max-width: 768px) {
        .traffic-filter {
            flex-direction: column;
        }
    }
</style>

<?php if (isRole('superadmin')): ?>
    <div class="traffic-filter">
        <div class="filter-block">
            <h4 class="dropdown-toggle" onclick="toggleDropdown(this)">
				<?= __('shop') ?>
                <span class="arrow" aria-hidden="true">
                <svg width="12" height="12" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg" fill="black">
                    <path d="M1 3 L5 7 L9 3 Z"/>
                </svg>
            </span>
            </h4>
            <div class="dropdown-content" id="shop-filter">
                <div>
                    <input type="checkbox" class="form-check-input" id="shop-all">
                    <label for="shop-all" class="form-check-label"><?= __('all') ?></label>
                </div>
				<?php foreach ($shops ?? [] as $shop): ?>
                    <div>
                        <input type="checkbox"
                               name="shop[]"
                               value="<?= $shop->id ?>"
                               class="form-check-input shop-checkbox"
                               id="shop-<?= $shop->id ?>"
						       <?php if (in_array($shop->id, $selectedShops ?? [])): ?>checked<?php endif; ?>
                        >
                        <label for="shop-<?= $shop->id ?>" class="form-check-label"><?= $shop->title ?></label>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const allCheckboxes = document.querySelectorAll('input[type="checkbox"][id$="-all"]');

                allCheckboxes.forEach(allCheckbox => {
                    const groupPrefix = allCheckbox.id.replace('-all', '');
                    const groupSelector = `input[type="checkbox"][name="${groupPrefix}[]"]`;
                    const groupCheckboxes = document.querySelectorAll(groupSelector);

                    allCheckbox.addEventListener('change', function () {
                        groupCheckboxes.forEach(cb => cb.checked = this.checked);
                        if (groupPrefix === 'shop') updateShops();
                        if (groupPrefix === 'apk') updateApk();
                    });

                    groupCheckboxes.forEach(cb => {
                        cb.addEventListener('change', function () {
                            allCheckbox.checked = Array.from(groupCheckboxes).every(c => c.checked);
                        });
                    });
                });
            });
        </script>

        <div class="filter-block">
            <h4 class="dropdown-toggle" onclick="toggleDropdown(this)">
				<?= __('apk') ?>
                <span class="arrow" aria-hidden="true">
                <svg width="12" height="12" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg" fill="black">
                    <path d="M1 3 L5 7 L9 3 Z"/>
                </svg>
            </span>
            </h4>
            <div class="dropdown-content">
                <div>
                    <input type="checkbox" class="form-check-input" id="apk-all">
                    <label for="apk-all" class="form-check-label"><?= __('all') ?></label>
                </div>
				<?php foreach ($apks ?? [] as $apk): ?>
                    <div>
                        <input type="checkbox"
                               name="apk[]"
                               value="<?= $apk->id ?>"
                               class="form-check-input apk-checkbox"
                               id="apk-<?= $apk->id ?>"
						       <?php if (in_array($apk->id, $selectedApks ?? [])): ?>checked<?php endif; ?>
                        >
                        <label for="apk-<?= $apk->id ?>" class="form-check-label"><?= $apk->title ?></label>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>

        <div class="filter-block">
            <h6><label for="referral-code"><?= __('referral code') ?></label></h6>
            <input type="text" name="referral-code" value="<?= ($referralCode ?? '') ?>" class="form-control"
                   id="referral-code">
        </div>
    </div>
<?php endif; ?>


<script>
    function toggleDropdown(header) {
        const content = header.nextElementSibling;
        header.classList.toggle("active");
        content.classList.toggle("open");
    }
</script>

<style>
    .my-filter {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .my-filter .filter-block {
        flex: 1 1 200px;
        min-width: 200px;
    }

    .my-filter .bootstrap-select .dropdown-toggle {
        border-radius: 6px !important;
        padding: 8px 12px !important;
        font-size: 0.95rem;
    }

    .my-filter .bootstrap-select .bs-searchbox input.form-control {
        border-radius: 6px !important;
        box-shadow: none !important;
        border: 1px solid #ced4da !important;
        padding: 6px 10px !important;
        font-size: 0.875rem !important;
    }

    .my-filter .bootstrap-select .bs-searchbox {
        padding: 8px !important;
    }

    .my-filter .bootstrap-select .dropdown-menu {
        border-radius: 6px !important;
        border: 1px solid #dee2e6 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }


    .my-filter .bootstrap-select .dropdown-menu {
        max-height: 300px; /* Ограничиваем высоту */
        overflow-y: auto; /* Включаем вертикальный скролл */
        padding: 0.25rem 0; /* Отступы сверху и снизу */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Мягкая тень */
        border-radius: 0.375rem; /* Более закругленные углы */
        border: 1px solid #ced4da;
    }

    .my-filter .bootstrap-select .dropdown-menu .bs-searchbox {
        padding: 0.5rem 0.75rem;
    }

    .my-filter .bootstrap-select .dropdown-menu .inner > li {
        padding: 0.375rem 1rem;
        font-size: 0.9rem;
    }

    .my-filter .bootstrap-select .dropdown-menu .inner > li:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    /* Улучшаем скролл для Chrome/Edge */
    .my-filter .bootstrap-select .dropdown-menu::-webkit-scrollbar {
        width: 8px;
    }

    .my-filter .bootstrap-select .dropdown-menu::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.15);
        border-radius: 4px;
    }

    /* Firefox */
    .my-filter .bootstrap-select .dropdown-menu {
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.15) transparent;
    }


    .dropdown.bootstrap-select.form-select {
        border: none;
        background: transparent;
        padding: 0;
    }

    .date-range-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        margin-bottom: 30px;
    }

    .date-filter-block {
        flex: 1 1 250px;
        min-width: 250px;
    }

    .date-filter-block label {
        display: block;
        font-weight: 500;
        font-size: 0.95rem;
        margin-bottom: 6px;
    }

    .date-filter-block input[type="date"] {
        padding: 8px 12px;
        font-size: 0.95rem;
        border: 1px solid #ced4da;
        border-radius: 6px;
        background-color: #fff;
    }

</style>

<div class="my-filter">
    <div class="filter-block">
        <label for="country" class="form-label"><?= __('country') ?></label>
        <select id="country" name="country" class="selectpicker form-select" data-live-search="true"
                title="<?= __('select country') ?>">
			<?php foreach ($countries ?? [] as $country): ?>
                <option value="<?= htmlspecialchars($country) ?>" <?= ($selectedCountry ?? '' == htmlspecialchars($country) ? 'selected' : '') ?>><?= htmlspecialchars($country) ?></option>
			<?php endforeach; ?>
        </select>
    </div>

    <div class="filter-block">
        <label for="region" class="form-label"><?= __('region') ?></label>
        <select id="region" name="region" class="selectpicker form-select" data-live-search="true"
                title="<?= __('select region') ?>">
			<?php foreach ($regions ?? [] as $region): ?>
                <option value="<?= htmlspecialchars($region) ?>" <?= ($selectedRegion ?? '' == htmlspecialchars($region) ? 'selected' : '') ?>><?= htmlspecialchars($region) ?></option>
			<?php endforeach; ?>
        </select>
    </div>

    <div class="filter-block">
        <label for="city" class="form-label"><?= __('city') ?></label>
        <select id="city" name="city" class="selectpicker form-select" data-live-search="true"
                title="<?= __('select city') ?>">
			<?php foreach ($cities ?? [] as $city): ?>
                <option value="<?= htmlspecialchars($city) ?>" <?= ($selectedCity ?? '' == htmlspecialchars($city) ? 'selected' : '') ?>><?= htmlspecialchars($city) ?></option>
			<?php endforeach; ?>
        </select>
    </div>
</div>

<script>
    // Инициализируем bootstrap-select только внутри .my-filter
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('.my-filter');
        if (!container) return;

        const selects = container.querySelectorAll('select.selectpicker');
        selects.forEach(select => {
            // Если нужно, повторная инициализация
            if (typeof bootstrap.Selectpicker === 'function') {
                bootstrap.Selectpicker.getOrCreateInstance(select);
            }
        });
    });
</script>

<div class="date-range-filters mt-5">
    <div class="date-filter-block">
        <label for="date-from"><?= __('date from') ?></label>
        <input type="date" name="date-from" value="<?= $dateFrom ?? '' ?>" id="date-from" class="form-control">
    </div>
    <div class="date-filter-block">
        <label for="date-to"><?= __('date to') ?></label>
        <input type="date" name="date-to" value="<?= $dateTo ?? '' ?>" id="date-to" class="form-control">
    </div>
</div>

<div class="text-end mt-3">
    <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-filters">
		<?= __('clear filters') ?>
    </button>
</div>

<div class="mt-5">
    <table class="table table-striped table-hover table-bordered">
        <tr>
            <th><?= __('number of users') ?></th>
            <td class="number-of-users"><?= $dataForDashboard['total_users'] ?? '-' ?></td>
        </tr>
        <tr>
            <th><?= __('number of users online') ?></th>
            <td class="number-of-users-online"><?= $dataForDashboard['total_online_users'] ?? '-' ?></td>
        </tr>
        <tr>
            <th><?= __('volume of traffic sold') ?></th>
            <td class="volume-of-traffic-sold"><?= $dataForDashboard['total_traffic'] ?? '-' ?></td>
        </tr>
        <tr>
            <th><?= __('total amount to be paid for traffic') ?></th>
            <td class="total-amount-to-be-paid-for-traffic"><?= $dataForDashboard['total_cost'] ?? '-' ?></td>
        </tr>
    </table>
</div>
