<style>
    tr:hover {
        cursor: pointer;
    }
</style>

<form method="POST" class="mb-5">
    <div class="mb-3">
        <label for="login" class="form-label"><?= __('login') ?>:</label>
        <input type="text" name="login" value="<?= $login ?? '' ?>" class="form-control" id="login">
    </div>
    <div class="mb-3">
        <input type="submit" value="<?= __('search') ?>" class="btn">
    </div>
</form>

<?php if (!empty($user)): ?>
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
        <tr data-id="<?= $user['id'] ?>">
            <td><?= $user['id'] ?></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['referral_code'] ?></td>
            <td><i class="icon-<?= ($user['is_blocked'] ? 'block' : 'check-1') ?>"></i></td>
        </tr>
        </tbody>
    </table>
<?php endif; ?>