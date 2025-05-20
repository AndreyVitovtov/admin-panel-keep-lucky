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
        max-height: 50%;
    }
</style>

<canvas id="usersChart"></canvas>