<style>
    .pagination {
        display: flex;
        align-content: center;
        justify-content: end;
    }

    .pagination a,
    .pagination span {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        border: 1px solid #ccc;
        text-decoration: none;
        margin: 0 2px;
        box-sizing: border-box;
        font-size: 14px;
        border-radius: 4px;
    }

    .pagination a {
        color: #393F4E;
    }

    .pagination .active {
        background-color: #393F4E;
        color: #fff;
        font-weight: bold;
    }

    tr:hover {
        cursor: pointer;
    }
</style>

<form method="GET" id="filter-form">
    <input type="hidden" name="page" value="<?= $page ?? 1 ?>">
    <div class="mb-3">
        <input type="checkbox" name="online" id="online" value="1"
               class="form-check-input" <?= $online ?? false ? 'checked' : '' ?>>
        -
        <label for="online" class="form-check-label"><?= __('online') ?></label>
    </div>
</form>

<table class="table table-striped table-responsive table-hover" id="table-users">
    <thead>
    <tr>
        <th><?= __('id') ?></th>
        <th><?= __('username') ?></th>
        <th><?= __('email') ?></th>
        <th><?= __('referral code') ?></th>
        <th><?= __('country') ?></th>
        <th><?= __('city') ?></th>
<!--        <th>--><?php //= __('is blocked') ?><!--</th>-->
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users ?? [] as $user): ?>
        <tr data-id="<?= $user['id'] ?>">
            <td><?= $user['id'] ?></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['referral_code'] ?></td>
            <td><?= (empty($user['country']) ? '-' : $user['country']) ?></td>
            <td><?= (empty($user['city']) ? '-' : $user['city']) ?></td>
<!--            <td><i class="icon---><?php //= ($user['is_blocked'] ? 'lock-1' : 'lock-open-1') ?><!--"></i></td>-->
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php
if (($totalUsers ?? 0) > COUNT_PAGINATION) {
    echo '<div class="pagination">' . pagination($totalUsers ?? 0, COUNT_PAGINATION, $page) . '</div>';
}

?>