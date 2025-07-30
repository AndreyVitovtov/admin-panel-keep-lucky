<?php extract(getSessionParams()); ?>

<style>
    label {
        cursor: pointer;
    }
</style>

<form method="POST">
    <div class="mb-3">
        <label for="adminId" class="form-label"><?= __('select admin') ?>:</label>
        <select name="admin" id="adminId" class="form-select">
			<?php foreach ($admins ?? [] as $admin): ?>
                <option value="<?= $admin['id'] ?>" <?= (($adminId ?? 0) == $admin['id'] ? 'selected' : '') ?>><?= $admin['name'] ?></option>
			<?php endforeach; ?>
        </select>
    </div>
    <div class="mb-5">
        <input type="submit" value="<?= __('ok') ?>" class="btn">
    </div>
</form>

<?php if ($adminId ?? false): ?>
    <form action="/administrators/saveAccess" method="POST" id="accessForm" class="mb-5">
        <table class="table table-striped table-hover mb-3">
            <thead>
            <tr>
                <th><?= __('section') ?></th>
                <th><?= __('option') ?></th>
                <th><?= __('available') ?></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($accessesOptions ?? [] as $key => $option): ?>
                <tr>
                    <td><label for="<?= $key ?>"><?= __($option['section']) ?></label></td>
                    <td><label for="<?= $key ?>"><?= __($option['title']) ?></label></td>
                    <td>
                        <input type="checkbox" <?= (in_array($option['title'], ($accessesSelected[$option['section']] ?? [])) ? 'checked' : '') ?>
                               data-section="<?= $option['section'] ?>" data-option="<?= $option['title'] ?>"
                               id="<?= $key ?>">
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
        <div class="mb-3">
            <input type="submit" value="<?= __('save') ?>" class="btn">
        </div>
    </form>
<?php endif; ?>


