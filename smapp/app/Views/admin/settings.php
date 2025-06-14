<?= $this->extend('admin/layout/master') ?>
<?php helper('form'); ?>
<?= $this->section('content') ?>

<!-- Page Title -->
<div class="kt-container-fixed">
    <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
        <div class="flex flex-col justify-center gap-2">
            <h1 class="text-xl font-medium leading-none text-mono"><?= admin_lang('home.welcome_message') ?></h1>
            <div class="flex items-center gap-2 text-sm font-normal text-secondary-foreground">Central Hub for Personal Customization</div>
        </div>
        <div class="flex items-center gap-2.5">
            <a class="kt-btn kt-btn-outline" href="html/demo1/public-profile/profiles/default.html">View Profile</a>
        </div>
    </div>
</div>
<!-- Page Title End -->

<div class="container my-5">
    <h1><?= $title ?></h1>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php elseif (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php elseif (isset($info_message)): ?>
        <div class="alert alert-info"><?= $info_message ?></div>
    <?php endif; ?>

    <?php 
    $db = db_connect();
$db->query('SELECT 1');
?>

    <form action="<?= get_admin_url_with_language('settings', $language) ?>" method="post">
        <div class="mb-3">
            <label for="admin_route" class="form-label"><?= admin_lang('label.admin_route') ?></label>
            <input type="text" name="admin_route" id="admin_route" class="form-control" value="<?= set_value('admin_route', get_smapp_config('admin_route')) ?>">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="force_https" id="force_https" class="form-check-input" <?= get_smapp_config('force_https', false) ? 'checked' : '' ?>>
            <label for="force_https" class="form-check-label"><?= admin_lang('label.force_https') ?></label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="force_www" id="force_www" class="form-check-input" <?= get_smapp_config('force_www', false) ? 'checked' : '' ?>>
            <label for="force_www" class="form-check-label"><?= admin_lang('label.force_www') ?></label>
        </div>
        <div class="mb-3">
            <label for="default_language" class="form-label"><?= admin_lang('label.default_language') ?></label>
            <select name="default_language" id="default_language" class="form-select">
                <?php foreach($supported_languages as $lang_code => $lang_name): ?>
                    <option value="<?= $lang_code ?>" <?= get_smapp_config('default_language', 'tr') === $lang_code ? 'selected' : '' ?>><?= $lang_name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="force_language_route" id="force_language_route" class="form-check-input" <?= get_smapp_config('force_language_route', true) ? 'checked' : '' ?>>
            <label for="force_language_route" class="form-check-label"><?= admin_lang('label.force_language_route') ?></label>
<!-- Smart Language Detection -->
        <hr class="my-4">
        <h4><?= admin_lang('settings.language_settings') ?></h4>

        <div class="mb-3 form-check">
            <input type="checkbox" name="language_detect_use_user" id="language_detect_use_user" class="form-check-input" <?= get_smapp_config('language_detect_use_user', true) ? 'checked' : '' ?>>
            <label for="language_detect_use_user" class="form-check-label">Use Logged-in User Preference</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="language_detect_use_ip" id="language_detect_use_ip" class="form-check-input" <?= get_smapp_config('language_detect_use_ip', true) ? 'checked' : '' ?>>
            <label for="language_detect_use_ip" class="form-check-label">Use IP Country</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="language_detect_use_browser" id="language_detect_use_browser" class="form-check-input" <?= get_smapp_config('language_detect_use_browser', true) ? 'checked' : '' ?>>
            <label for="language_detect_use_browser" class="form-check-label">Use Browser Language</label>
        </div>

        <div class="mb-3">
            <label for="language_detect_priority" class="form-label">Detection Priority (comma-separated)</label>
            <input type="text"
                   name="language_detect_priority"
                   id="language_detect_priority"
                   class="form-control"
                   value="<?= set_value('language_detect_priority', implode(',', get_smapp_config('language_detect_priority', ['user','ip','browser','default']))) ?>">
            <small class="text-muted">Example: user,ip,browser,default</small>
        </div>
<!-- Environment Settings -->
        <hr class="my-4">
        <h4><?= admin_lang('settings.environment_settings') ?></h4>

        <div class="mb-3">
            <label for="ci_environment" class="form-label"><?= admin_lang('label.ci_environment') ?></label>
            <select name="ci_environment" id="ci_environment" class="form-select">
                <?php
                    $envOptions = ['production', 'development', 'testing'];
                    $currentEnv = set_value('ci_environment', get_smapp_config('ci_environment', 'development'));
                    foreach ($envOptions as $opt): ?>
                        <option value="<?= $opt ?>" <?= $opt === $currentEnv ? 'selected' : '' ?>>
                            <?= ucfirst($opt) ?>
                        </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="app_baseURL" class="form-label"><?= admin_lang('label.app_baseURL') ?></label>
            <input type="text"
                   name="app_baseURL"
                   id="app_baseURL"
                   class="form-control"
                   value="<?= set_value('app_baseURL', get_smapp_config('app_baseURL', 'http://localhost/')) ?>">
        </div>

        <div class="mb-3">
            <label for="logger_threshold" class="form-label"><?= admin_lang('label.logger_threshold') ?></label>
            <input type="number"
                   name="logger_threshold"
                   id="logger_threshold"
                   class="form-control"
                   value="<?= set_value('logger_threshold', get_smapp_config('logger_threshold', 4)) ?>">
        </div>
        </div>
        <button type="submit" class="btn btn-primary"><?= admin_lang('button.save') ?></button>
    </form>
</div>
<?= $this->endSection() ?>