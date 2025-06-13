<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>
<!-- Page Title -->
<div class="kt-container-fixed">
    <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
        <div class="flex flex-col justify-center gap-2">
            <h1 class="text-xl font-medium leading-none text-mono"><?= $title ?></h1>
            <div class="flex items-center gap-2 text-sm font-normal text-secondary-foreground"><?= lang('Admin.home.welcome_message') ?></div>
        </div>
        <div class="flex items-center gap-2.5">
            <a class="kt-btn kt-btn-outline" href="html/demo1/public-profile/profiles/default.html">View Profile</a>
        </div>
    </div>
</div>
<!-- Page Title End -->
<?= $this->endSection() ?>