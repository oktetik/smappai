<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<?php
    /**
     * @var array   $languageRow  Empty array when creating
     * @var string  $action       store | update/{id}
     * @var string  $admin_route
     * @var string  $language     UI lang
     */
    helper(['form', 'html']);
    $isEdit = !empty($languageRow);
    $baseUrl = get_admin_url_with_language('languages', $language);
?>

<div class="kt-container-fixed">
    <!-- Page Title -->
    <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
        <div class="flex flex-col justify-center gap-2">
            <h1 class="text-xl font-medium leading-none text-mono">
                <?= esc($title) ?>
            </h1>
        </div>
        <div class="flex items-center gap-2.5">
            <a class="kt-btn kt-btn-outline" href="<?= $baseUrl ?>">
                <?= lang('Admin.back') ?>
            </a>
        </div>
    </div>
    <!-- End Page Title -->

    <?php if (session('errors')) : ?>
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?= form_open($baseUrl . '/' . $action, ['class' => 'card']) ?>
        <div class="card-body grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="form-label"><?= lang('Admin.label.default_language') ?> *</label>
                <input type="text" name="code" value="<?= old('code', $languageRow['code'] ?? '') ?>"
                       class="form-control" required maxlength="5">
            </div>

            <div>
                <label class="form-label">Locale *</label>
                <input type="text" name="locale" value="<?= old('locale', $languageRow['locale'] ?? '') ?>"
                       class="form-control" required maxlength="10">
            </div>

            <div>
                <label class="form-label">Native Name</label>
                <input type="text" name="native_name" value="<?= old('native_name', $languageRow['native_name'] ?? '') ?>"
                       class="form-control">
            </div>

            <div>
                <label class="form-label">Direction *</label>
                <select name="direction" class="form-select" required>
                    <option value="ltr" <?= old('direction', $languageRow['direction'] ?? '') === 'ltr' ? 'selected' : '' ?>>LTR</option>
                    <option value="rtl" <?= old('direction', $languageRow['direction'] ?? '') === 'rtl' ? 'selected' : '' ?>>RTL</option>
                </select>
            </div>

            <div>
                <label class="form-label">Status *</label>
                <select name="status" class="form-select" required>
                    <option value="active"  <?= old('status', $languageRow['status'] ?? '') === 'active'  ? 'selected' : '' ?>><?= lang('Admin.active') ?></option>
                    <option value="passive" <?= old('status', $languageRow['status'] ?? '') === 'passive' ? 'selected' : '' ?>><?= lang('Admin.inactive') ?></option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_default" name="is_default" value="1"
                       <?= old('is_default', $languageRow['is_default'] ?? 0) ? 'checked' : '' ?>>
                <label for="is_default"><?= lang('Admin.label.default_language') ?></label>
            </div>

        </div>
        <div class="card-footer flex justify-end gap-2">
            <button type="submit" class="kt-btn kt-btn-primary"><?= lang('Admin.save') ?></button>
            <a href="<?= $baseUrl ?>" class="kt-btn kt-btn-outline"><?= lang('Admin.cancel') ?></a>
        </div>
    <?= form_close() ?>
</div>

<?= $this->endSection() ?>