<script>
    window.texts = {
        "referralCode": "<?= __('referral code') ?>"
    };
</script>

<form action="/administrators/update/<?= ($id ?? '') ?>" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">*<?= __('name') ?>:</label>
        <input type="text" name="name" value="<?= $name ?? '' ?>" id="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="login" class="form-label">*<?= __('login') ?>:</label>
        <input type="text" name="login" value="<?= $login ?? '' ?>" id="login" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label"><?= __('password') ?>:</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>
    <div class="mb-3">
        <label for="repeat-password" class="form-label"><?= __('repeat password') ?>:</label>
        <input type="password" name="repeatPassword" id="repeat-password" class="form-control">
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">*<?= __('role') ?>:</label>
        <select name="role" id="role" class="form-control" required>
			<?php foreach ($roles ?? [] as $r): ?>
                <option value="<?= $r['id'] ?>" <?= ((($role ?? '') == $r['id']) ? 'selected' : '') ?> ><?= $r['title'] ?></option>
			<?php endforeach; ?>
        </select>
    </div>
	<?php if (!empty($referral_code)): ?>
        <div class="mb-3" id="referral-code-wrapper">
            <label for="referral-code" class="form-label">*<?= __('referral code') ?>:</label>
            <input type="text" name="referralCode" value="<?= $referral_code ?? '' ?>" class="form-control"
                   id="referral-code" required>
        </div>
	<?php endif; ?>
    <div class="mb-3">
        <input type="submit" value="<?= __('save') ?>" class="btn">
    </div>
</form>