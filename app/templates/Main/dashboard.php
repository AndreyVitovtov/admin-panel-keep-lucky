<script>
    window.texts = {
        'usersStats': '<?= __('users stats') ?>',
        'totalUsers': '<?= __('total users') ?>',
        'onlineUsers': '<?= __('online users') ?>',
        'quantity': '<?= __('quantity') ?>'
    };
    window.usersStats = {
        'total': '<?= $totalUsers ?? 0 ?>',
        'online': '<?= $onlineUsers ?? 0 ?>'
    };
</script>

<style>
    canvas {
        max-height: 30vh;
    }

    #usersByLocation {
        max-width: 100%;
        height: auto;
    }

    .chart-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
    }
</style>

<div class="row">
    <div class="">
        <canvas id="usersChart"></canvas>
    </div>
    <div class="d-flex flex-column align-items-center mt-5">
        <div class="d-flex justify-content-between w-100 mb-3">
            <div class="me-2 w-50">
                <select name="" id="" class="form-select">
                    <option value=""><?= __('select country') ?></option>
                </select>
            </div>
            <div class="w-50">
                <select name="" id="" class="form-select">
                    <option value=""><?= __('select region') ?></option>
                </select>
            </div>
        </div>
        <div class="chart-wrapper w-100">
            <canvas id="usersByLocation"></canvas>
        </div>
    </div>
</div>