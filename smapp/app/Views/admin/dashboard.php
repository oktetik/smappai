<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <h1><?= $title ?></h1>
    <p><?= lang('Admin.home.welcome_message') ?></p>
    <!-- Dashboard Metrics Start -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Metric 1</h5>
                    <p class="card-text">Some value or chart here</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Metric 2</h5>
                    <p class="card-text">Some value or chart here</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Metric 3</h5>
                    <p class="card-text">Some value or chart here</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard Metrics End -->
</div>
<?= $this->endSection() ?>