<div class="mb-3">
    <h6>📶 <?= __('total traffic') ?>: <span id="total-traffic"><?= $totalTraffic ?? 0 ?></span></h6>
    <h6>💰 <?= __('total cost') ?>: <span id="total-cost"><?= $totalCost ?? 0 ?></span></h6>
</div>

<form action="" method="POST" class="d-flex flex-wrap align-items-end gap-3 mb-4">
    <div>
        <label for="country" class="form-label mb-1"><?= __('country') ?>:</label>
        <input type="text" name="country" id="country" class="form-control">
    </div>
    <div>
        <label for="refCode" class="form-label mb-1"><?= __('referral code') ?>:</label>
        <input type="text" name="refCode" id="refCode" class="form-control">
    </div>
    <div>
        <label for="date-from" class="form-label mb-1"><?= __('date from') ?>:</label>
        <input type="date" name="date-from" id="date-from" class="form-control">
    </div>
    <div>
        <label for="date-to" class="form-label mb-1"><?= __('date to') ?>:</label>
        <input type="date" name="date-to" id="date-to" class="form-control">
    </div>
    <div>
        <button type="submit" class="btn"><?= __('search') ?></button>
    </div>
</form>

