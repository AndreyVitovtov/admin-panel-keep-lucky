<style>
    .user-block {
        margin-right: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 40px;
        position: relative;
        border: solid 1px #d1d1d1;
        padding: 1px;
        border-radius: 5px;
        height: 20px;
        cursor: pointer;
    }

    .user-block > div:nth-child(2) {
        padding-right: 5px;
    }

    .user-block i {
        font-size: 13px;
        color: var(--color-button);
    }

    .user-block .user-block-switcher {
        position: absolute;
        width: 20px;
        height: 20px;
        background-color: var(--color-button);
    }

    .user-block.lock .user-block-switcher {
        left: 0;
        border-radius: 5px 0 0 5px;
    }

    .user-block.unlock .user-block-switcher {
        right: 0;
        border-radius: 0 5px 5px 0;
    }

    .vertical-middle {
        vertical-align: middle;
    }
</style>

<table class="table table-striped table-responsive table-hover mb-5">
    <thead>
    <tr>
        <th><?= __('title') ?></th>
        <th><?= __('value') ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th><?= __('id') ?></th>
        <td><?= $userId ?? 0 ?></td>
    </tr>
    <tr>
        <th><?= __('referral code') ?></th>
        <td>
            <?= getRole() == 'admin3' ? $userStats['referral_code'] ?? '-' : '<a href="/users/searchByRefCode/' . ($userStats['referral_code'] ?? '-') .
                '">' . ($userStats['referral_code'] ?? '-') . '</a>' ?>
        </td>
    </tr>
    <tr>
        <th><?= __('srxt balance') ?></th>
        <td><?= $userStats['srxt_balance'] ?? 0 ?></td>
    </tr>
    <tr>
        <th><?= __('balance') ?></th>
        <td><?= $userStats['balance'] ?? 0 ?></td>
    </tr>
    <tr>
        <th><?= __('country') ?></th>
        <td><?= (empty($userStats['country']) ? '-' : $userStats['country']) ?></td>
    </tr>
    <tr>
        <th><?= __('city') ?></th>
        <td><?= (empty($userStats['city']) ? '-' : $userStats['city']) ?></td>
    </tr>
    <tr>
        <th><?= __('price for one gb') ?></th>
        <td><?= $userStats['price_for_one_gb'] ?? 0 ?></td>
    </tr>
    <tr>
        <th><?= __('traffic sold') ?></th>
        <td><?= $userStats['traffic_sold'] ?? 0 ?></td>
    </tr>
    <tr>
        <th><?= __('is block') ?></th>
        <td class="vertical-middle">
            <div class="user-block <?= (($userInfo['is_blocked'] ?? false) ? 'lock' : 'unlock') ?>"
                 data-id="<?= ($userId ?? 0) ?>">
                <div><i class="icon-lock-open-1"></i></div>
                <div><i class="icon-lock-1"></i></div>
                <div class="user-block-switcher"></div>
            </div>
        </td>
    </tr>
    </tbody>
</table>

<h4><?= __('top up balance') ?></h4>
<form action="/users/topUpBalance" method="POST" class="mb-5">
    <div class="mb-3">
        <label for="write-off-amount"><?= __('amount') ?>:</label>
        <input type="number" name="amount" class="form-control" id="write-off-amount" required>
        <input type="hidden" name="id" value="<?= ($userId ?? 0) ?>">
    </div>
    <div>
        <input type="submit" value="<?= __('top up') ?>" class="btn">
    </div>
</form>

<h4><?= __('write off balance') ?></h4>
<form action="/users/writeOffBalance" method="POST" class="mb-5">
    <div class="mb-3">
        <label for="write-off-amount"><?= __('amount') ?>:</label>
        <input type="number" name="amount" class="form-control" id="write-off-amount" required>
        <input type="hidden" name="id" value="<?= ($userId ?? 0) ?>">
    </div>
    <div>
        <input type="submit" value="<?= __('write off') ?>" class="btn">
    </div>
</form>

<?php if (!empty($userStats['transactions'])): ?>
    <h4><?= __('transactions') ?></h4>
    <table class="table table-striped table-responsive table-hover mb-5">
        <thead>
        <tr>
            <th><?= __('id') ?></th>
            <th><?= __('user id') ?></th>
            <th><?= __('amount') ?></th>
            <th><?= __('status') ?></th>
            <th><?= __('type') ?></th>
            <th><?= __('currency type') ?></th>
            <th><?= __('created at') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($userStats['transactions'] ?? [] as $transaction): ?>
            <tr>
                <td><?= $transaction['id'] ?></td>
                <td><?= $transaction['user_id'] ?></td>
                <td><?= $transaction['amount'] ?></td>
                <td><?= $transaction['status'] ?></td>
                <td><?= $transaction['type'] ?></td>
                <td><?= $transaction['currency_type'] ?></td>
                <td><?= date('Y-m-d H:i:s', strtotime($transaction['created_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>