<style>
    tr:hover {
        cursor: pointer;
    }
</style>

<form action="/users/searchByRefCode" method="GET">
    <div class="mb-3">
        <input type="checkbox" name="online" value="1" id="online" <?= (($online ?? '') ? 'checked' : '') ?> class="form-check-input">
        -
        <label for="online" class="form-check-label"><?= __('online') ?></label>
    </div>
    <div class="mb-3">
        <label for="referral-code" class="form-label"><?= __('referral code') ?>:</label>
        <input type="text" name="referralCode" value="<?= $referralCode ?? '' ?>" class="form-control"
               id="referral-code">
    </div>
    <div class="mb-3">
        <input type="submit" value="<?= __('search') ?>" class="btn">
    </div>
</form>

<?php if (!empty($users)): ?>
    <table class="table table-striped table-responsive table-hover">
        <thead>
        <tr>
            <th><?= __('id') ?></th>
            <th><?= __('username') ?></th>
            <th><?= __('email') ?></th>
            <th><?= __('referral code') ?></th>
            <th><?= __('is blocked') ?></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($users ?? [] as $user): ?>
            <tr data-id="<?= $user['id'] ?>">
                <td><?= $user['id'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['referral_code'] ?></td>
                <td><i class="icon-<?= ($user['is_blocked'] ? 'block' : 'check-1') ?>"></i></td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>