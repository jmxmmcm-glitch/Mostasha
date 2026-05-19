<div class="row">
    <div class="col-12">
        <h1 class="mb-4 fw-bold">مرحباً بك، <?php echo \App\Core\Application::$app->user->full_name ?? ''; ?></h1>
        <p class="lead text-muted">لوحة التحكم التفاعلية لمركز الطوارئ الطبي.</p>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3 shadow-sm border-0 rounded-4">
            <div class="card-header border-0 fs-5">حالات الطوارئ النشطة</div>
            <div class="card-body">
                <h2 class="card-title display-4 fw-bold"><?php echo $active_cases ?? 0; ?> حالة</h2>
                <p class="card-text">جميع الحالات التي لم يتم تخريجها بعد.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning mb-3 shadow-sm border-0 rounded-4 text-dark">
            <div class="card-header border-0 fs-5">في قسم الفرز (Triage)</div>
            <div class="card-body">
                <h2 class="card-title display-4 fw-bold"><?php echo $triage_cases ?? 0; ?> مريض</h2>
                <p class="card-text">في انتظار التقييم الحيوي في الفرز.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3 shadow-sm border-0 rounded-4">
            <div class="card-header border-0 fs-5">أسرّة متاحة</div>
            <div class="card-body">
                <h2 class="card-title display-4 fw-bold"><?php echo $available_beds ?? 15; ?> سرير</h2>
                <p class="card-text">جاهزة لاستقبال مرضى في العناية.</p>
            </div>
        </div>
    </div>
</div>
