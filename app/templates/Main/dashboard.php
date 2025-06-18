<!-- Bootstrap 5 CSS -->


<!-- bootstrap-select CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">

<!-- Bootstrap 5 JS (с Popper) -->


<!-- bootstrap-select JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>



<script>
    //window.texts = {
    //    'usersStats': '<?php //= __('users stats') ?>//',
    //    'totalUsers': '<?php //= __('total users') ?>//',
    //    'onlineUsers': '<?php //= __('online users') ?>//',
    //    'quantity': '<?php //= __('quantity') ?>//',
    //    'usersByCountries': '<?php //= __('users by countries') ?>//',
    //    'usersByRegions': '<?php //= __('users by regions') ?>//',
    //    'usersByCities': '<?php //= __('users by cities') ?>//',
    //};
    //window.usersStats = {
    //    'total': '<?php //= $totalUsers ?? 0 ?>//',
    //    'online': '<?php //= $onlineUsers ?? 0 ?>//'
    //};
    //
    //window.usersByCountries = <?php //= json_encode($usersByCountries ?? []) ?>//;
    //window.usersByRegions = <?php //= json_encode($usersByRegions ?? []) ?>//;
    //window.usersByCities = <?php //= json_encode($usersByCities ?? []) ?>//;

</script>

<!--<style>-->
<!--    canvas {-->
<!--        max-height: 30vh;-->
<!--    }-->
<!---->
<!--    #usersByCountry, #usersByRegion {-->
<!--        max-width: 100%;-->
<!--        height: auto;-->
<!--    }-->
<!---->
<!--    .chart-wrapper {-->
<!--        display: flex;-->
<!--        justify-content: center;-->
<!--        align-items: center;-->
<!--        flex-grow: 1;-->
<!--    }-->
<!---->
<!---->
<!---->
<!--    .charts-row {-->
<!--        gap: 20px;-->
<!--    }-->
<!---->
<!--    .chart-wrapper {-->
<!--        flex: 1 1 calc(33.333% - 20px);-->
<!--        max-width: calc(33.333% - 20px);-->
<!--        padding: 10px;-->
<!--        box-sizing: border-box;-->
<!--    }-->
<!--</style>-->
<!---->
<!--<div class="row">-->
<!--    <div class="">-->
<!--        <canvas id="usersChart"></canvas>-->
<!--    </div>-->
<!--    <div class="charts-row d-flex flex-wrap justify-content-between">-->
<!--        <div class="chart-wrapper flex-fill">-->
<!--            <canvas id="usersByCountry"></canvas>-->
<!--        </div>-->
<!--        <div class="chart-wrapper flex-fill">-->
<!--            <canvas id="usersByRegion"></canvas>-->
<!--        </div>-->
<!--        <div class="chart-wrapper flex-fill">-->
<!--            <canvas id="usersByCity"></canvas>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

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



    /*   */


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

</style>

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
        <div class="dropdown-content">
            <div>
                <input type="checkbox" class="form-check-input" id="shop-all">
                <label for="shop-all" class="form-check-label"><?= __('all') ?></label>
            </div>
			<?php foreach ($shops ?? [] as $shop): ?>
                <div>
                    <input type="checkbox" name="shop[]" value="<?= $shop->id ?>" class="form-check-input"
                           id="shop-<?= $shop->id ?>">
                    <label for="shop-<?= $shop->id ?>" class="form-check-label"><?= $shop->title ?></label>
                </div>
			<?php endforeach; ?>
        </div>
    </div>

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
                    <input type="checkbox" name="apk[]" value="<?= $apk->id ?>" class="form-check-input"
                           id="apk-<?= $apk->id ?>">
                    <label for="apk-<?= $apk->id ?>" class="form-check-label"><?= $apk->title ?></label>
                </div>
			<?php endforeach; ?>
        </div>
    </div>

    <div class="filter-block">
        <h4><label for="referral-code"><?= __('referral code') ?></label></h4>
        <input type="text" name="referral-code" class="form-control" id="referral-code">
    </div>
</div>
<div class="traffic-filter-location">

</div>

<script>
    function toggleDropdown(header) {
        const content = header.nextElementSibling;
        header.classList.toggle("active");
        content.classList.toggle("open");
    }
</script>

<hr>

<div class="container mt-3">

    <!-- Фильтр "Страна" -->
    <label for="filter-country" class="form-label">Страна</label>
    <select id="filter-country" class="selectpicker form-select" data-live-search="true" title="Выберите страну">
        <option value="ru">Россия</option>
        <option value="ua">Украина</option>
        <option value="by">Беларусь</option>
        <option value="kz">Казахстан</option>
        <!-- ...другие страны -->
    </select>

    <!-- Фильтр "Регион" -->
    <label for="filter-region" class="form-label mt-3">Регион</label>
    <select id="filter-region" class="selectpicker form-select" data-live-search="true" title="Выберите регион">
        <option>Москва</option>
        <option>Санкт-Петербург</option>
        <option>Киев</option>
        <option>Минск</option>
        <!-- ...другие регионы -->
    </select>

    <!-- Фильтр "Город" -->
    <label for="filter-city" class="form-label mt-3">Город</label>
    <select id="filter-city" class="selectpicker form-select" data-live-search="true" title="Выберите город">
        <option>Москва</option>
        <option>Харьков</option>
        <option>Минск</option>
        <option>Алматы</option>
        <!-- ...другие города -->
    </select>

<!--     Другие фильтры и показатели -->
<!--    <div class="mt-4">-->
<!--        <label>Пользователей всего: <span id="total-users">12345</span></label><br>-->
<!--        <label>Онлайн пользователей: <span id="online-users">234</span></label>-->
<!--    </div>-->

<!--     Временной отрезок -->
<!--    <div class="mt-4">-->
<!--        <label class="form-label">Временной отрезок</label>-->
<!--        <div class="d-flex gap-2">-->
<!--            <input type="date" class="form-control" id="date-from" placeholder="С">-->
<!--            <input type="date" class="form-control" id="date-to" placeholder="По">-->
<!--        </div>-->
<!--    </div>-->

</div>
