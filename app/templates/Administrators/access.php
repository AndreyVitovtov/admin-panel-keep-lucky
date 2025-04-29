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
    <div class="mb-3">
        <label for="applicationId" class="form-label"><?= __('select application') ?>:</label>
        <select name="application" id="applicationId" class="form-select">
			<?php foreach ($applications ?? [] as $application): ?>
                <option value="<?= $application['id'] ?>" <?= (($applicationId ?? 0) == $application['id'] ? 'selected' : '') ?>><?= $application['title'] ?></option>
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
                <th><?= __('controller') ?></th>
                <th><?= __('method') ?></th>
                <th><?= __('available') ?></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($routes ?? [] as $key => $route): ?>
                <tr>
                    <td><label for="<?= $key ?>"><?= $route['controller'] ?></label></td>
                    <td><label for="<?= $key ?>"><?= $route['method'] ?></label></td>
                    <td>
                        <input type="checkbox" <?= (in_array($route['method'], ($accesses[$route['controller']] ?? [])) ? 'checked' : '') ?>
                               data-controller="<?= $route['controller'] ?>" data-method="<?= $route['method'] ?>"
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


