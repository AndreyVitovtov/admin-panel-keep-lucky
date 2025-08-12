<table class="table table-striped table-responsive table-hover table-bordered" id="table-traffic-stats">
    <thead>
    <tr>
        <?php if (isset($response[0]['date_from']) && isset($response[0]['date_to'])): ?>
            <?php if (date('Y-m-d', strtotime($response[0]['date_from'])) ==
                    date('Y-m-d', strtotime($response[0]['date_to']))): ?>
                <th><?= __('date') ?></th>
            <?php else: ?>
                <th><?= __('date from') ?></th>
                <th><?= __('date to') ?></th>
            <?php endif; ?>
        <?php endif; ?>
        <th><?= __('traffic') ?></th>
        <th><?= __('cost') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($response ?? [] as $row):
        if (!is_null($row['total_traffic'])) {
            $bytes = $row['total_traffic'];
            $gigabytes = round(($bytes / 1073741824), 2) . ' ' . __('gb');
        }
        ?>
        <tr>
            <?php if (isset($row['date_from']) && isset($row['date_to'])): ?>
                <?php if (date('Y-m-d', strtotime($row['date_from'])) ==
                        date('Y-m-d', strtotime($row['date_to']))): ?>
                    <td><?= date('Y-m-d', strtotime($row['date_from'])) ?></td>
                <?php else: ?>
                    <td><?= date('Y-m-d', strtotime($row['date_from'])) ?></td>
                    <td><?= date('Y-m-d', strtotime($row['date_to'])) ?></td>
                <?php endif; ?>
            <?php endif; ?>
            <td><?= $gigabytes ?? '-' ?></td>
            <td><?= (!empty($row['total_cost']) ? round($row['total_cost'], 4) : '-') ?></td>
        </tr>
        <?php
        unset($gigabytes);
    endforeach; ?>
    </tbody>
</table>