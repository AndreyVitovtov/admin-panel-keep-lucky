<script>
    window.texts = {
        'usersStats': '<?= __('users stats') ?>',
        'totalUsers': '<?= __('total users') ?>',
        'onlineUsers': '<?= __('online users') ?>',
        'quantity': '<?= __('quantity') ?>',
        'usersByCountries': '<?= __('users by countries') ?>',
        'usersByRegions': '<?= __('users by regions') ?>',
        'usersByCities': '<?= __('users by cities') ?>',
    };
    window.usersStats = {
        'total': '<?= $totalUsers ?? 0 ?>',
        'online': '<?= $onlineUsers ?? 0 ?>'
    };

    window.usersByCountries = <?= json_encode($usersByCountries ?? []) ?>;
    window.usersByRegions = <?= json_encode($usersByRegions ?? []) ?>;
    window.usersByCities = <?= json_encode($usersByCities ?? []) ?>;

</script>

<style>
    canvas {
        max-height: 30vh;
    }

    #usersByCountry, #usersByRegion {
        max-width: 100%;
        height: auto;
    }

    .chart-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
    }



    .charts-row {
        gap: 20px;
    }

    .chart-wrapper {
        flex: 1 1 calc(33.333% - 20px);
        max-width: calc(33.333% - 20px);
        padding: 10px;
        box-sizing: border-box;
    }
</style>

<div class="row">
    <div class="">
        <canvas id="usersChart"></canvas>
    </div>
    <div class="charts-row d-flex flex-wrap justify-content-between">
        <div class="chart-wrapper flex-fill">
            <canvas id="usersByCountry"></canvas>
        </div>
        <div class="chart-wrapper flex-fill">
            <canvas id="usersByRegion"></canvas>
        </div>
        <div class="chart-wrapper flex-fill">
            <canvas id="usersByCity"></canvas>
        </div>
    </div>
</div>