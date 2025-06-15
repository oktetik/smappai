<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<?php
    /**
     * @var array   $languages
     * @var Pager   $pager
     * @var string  $admin_route
     * @var string  $language   Active UI language code (tr, en…)
     */
    helper(['html', 'form']);
    $baseUrl = get_admin_url_with_language('languages', $language);
?>

<div class="kt-container-fixed">
    <!-- Page Title -->
    <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
        <div class="flex flex-col justify-center gap-2">
            <h1 class="text-xl font-medium leading-none text-mono"><?= esc($title) ?></h1>
        </div>
        <div class="flex items-center gap-2.5">
            <a class="kt-btn kt-btn-primary" href="<?= $baseUrl . '/create' ?>">
                <?= lang('Admin.add') ?>
            </a>
        </div>
    </div>
    <!-- End Page Title -->
    
    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link <?= $filter === null ? 'active' : '' ?>" href="<?= $baseUrl ?>">
                <?= lang('Admin.all') ?: 'All' ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filter === 'active' ? 'active' : '' ?>" href="<?= $baseUrl . '/active' ?>">
                <?= lang('Admin.active') ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filter === 'pending' ? 'active' : '' ?>" href="<?= $baseUrl . '/pending' ?>">
                <?= lang('Admin.pending') ?: 'Pending' ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filter === 'passive' ? 'active' : '' ?>" href="<?= $baseUrl . '/passive' ?>">
                <?= lang('Admin.inactive') ?: 'Passive' ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filter === 'deleted' ? 'active' : '' ?>" href="<?= $baseUrl . '/deleted' ?>">
                <?= lang('Admin.deleted') ?: 'Deleted' ?>
            </a>
        </li>
    </ul>

    <?php if (session('success_message')) : ?>
        <div class="alert alert-success mb-4"><?= session('success_message') ?></div>
    <?php elseif (session('error_message')) : ?>
        <div class="alert alert-danger mb-4"><?= session('error_message') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body overflow-x-auto p-0">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= lang('Admin.label.default_language') ?></th>
                        <th>Code</th>
                        <th>Locale</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th class="text-end"><?= lang('Admin.edit') ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($languages as $row) : ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td>
                            <?php if ($row['is_default']) : ?>
                                <span class="badge bg-success">Default</span>
                            <?php else : ?>
                                <form method="post" action="<?= $baseUrl . '/default/' . $row['id'] ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <?= lang('Admin.menu.settings') ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($row['code']) ?></td>
                        <td><?= esc($row['locale']) ?></td>
                        <td><?= esc($row['native_name']) ?></td>
                        <td>
                            <?php if ($row['status'] === 'active') : ?>
                                <span class="badge bg-success"><?= lang('Admin.active') ?></span>
                            <?php else : ?>
                                <span class="badge bg-secondary"><?= lang('Admin.inactive') ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <?php if ($filter === 'deleted') : ?>
                                <?php if (! $row['is_system_language']) : ?>
                                    <form method="post" action="<?= $baseUrl . '/restore/' . $row['id'] ?>" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <?= lang('Admin.restore') ?: 'Restore' ?>
                                        </button>
                                    </form>
                                    <form method="post" action="<?= $baseUrl . '/purge/' . $row['id'] ?>" class="d-inline" onsubmit="return confirm('<?= lang('Admin.form.confirm_change') ?>');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <?= lang('Admin.hard_delete') ?: 'Hard Delete' ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php else : ?>
                                <a href="<?= $baseUrl . '/edit/' . $row['id'] ?>" class="btn btn-sm btn-light">
                                    <?= lang('Admin.edit') ?>
                                </a>
                                <?php if (! $row['is_system_language']) : ?>
                                    <form method="post" action="<?= $baseUrl . '/delete/' . $row['id'] ?>" class="d-inline" onsubmit="return confirm('<?= lang('Admin.form.confirm_change') ?>');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <?= lang('Admin.delete') ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer py-3">
            <?= $pager->links() ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>