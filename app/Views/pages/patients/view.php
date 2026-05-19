<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-file-medical-alt"></i> السجل الطبي التاريخي</h2>
    <div>
        <button onclick="window.print()" class="btn btn-primary px-4 me-2"><i class="fas fa-print"></i> طباعة السجل</button>
        <a href="/patients" class="btn btn-outline-secondary px-4">عودة</a>
    </div>
</div>

<!-- Patient Profile Header -->
<div class="card shadow-sm border-0 rounded-4 mb-5 bg-primary text-white">
    <div class="card-body p-4 d-flex align-items-center">
        <div class="display-3 me-4">👤</div>
        <div>
            <h3 class="fw-bold mb-2"><?php echo htmlspecialchars($patient['full_name']); ?></h3>
            <div class="d-flex gap-4 opacity-75">
                <span><strong>الرقم القومي:</strong> <?php echo htmlspecialchars($patient['national_id']); ?></span>
                <span><strong>رقم الهاتف:</strong> <?php echo htmlspecialchars($patient['phone'] ?: 'غير محدد'); ?></span>
                <span><strong>تاريخ الميلاد:</strong> <?php echo $patient['dob'] ?: 'غير محدد'; ?></span>
            </div>
        </div>
    </div>
</div>

<h4 class="fw-bold mb-4 text-dark">تاريخ الزيارات للطوارئ</h4>

<?php if (empty($visits)): ?>
    <div class="alert alert-light border-0 rounded-4 text-center py-5 shadow-sm">
        <h5 class="text-muted">لا يوجد أي سجل زيارات سابقة لهذا المريض.</h5>
    </div>
<?php else: ?>
    <div class="timeline ps-3 border-start border-3 border-primary ms-3">
        <?php foreach ($visits as $v): ?>
            <div class="card shadow-sm border-0 rounded-4 mb-4 position-relative">
                <!-- Timeline Dot -->
                <span class="position-absolute translate-middle p-2 bg-primary border border-light rounded-circle" style="top: 25px; left: -18px;"></span>
                
                <div class="card-header bg-light border-0 d-flex justify-content-between py-3 rounded-top-4">
                    <span class="fw-bold text-primary">زيارة رقم #<?php echo $v['id']; ?></span>
                    <span class="text-muted"><?php echo date('Y-m-d h:i A', strtotime($v['arrival_time'])); ?></span>
                </div>
                
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-5 border-end">
                            <h6 class="fw-bold text-muted mb-3"><span class="badge bg-warning text-dark me-2">فرز طبي</span> العلامات الحيوية المبدئية</h6>
                            <?php if ($v['blood_pressure']): ?>
                                <p class="mb-1"><strong>الضغط:</strong> <?php echo htmlspecialchars($v['blood_pressure']); ?></p>
                                <p class="mb-1"><strong>النبض:</strong> <?php echo htmlspecialchars($v['heart_rate']); ?> bpm</p>
                                <p class="mb-1"><strong>الحرارة:</strong> <?php echo htmlspecialchars($v['temperature']); ?> °C</p>
                                <?php if($v['triage_notes']): ?>
                                    <p class="mb-0 mt-2 text-muted small"><em>ملاحظة تمريض: <?php echo htmlspecialchars($v['triage_notes']); ?></em></p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-muted small">لم يتم تسجيل تقييم للفرز.</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-7 ps-4">
                            <h6 class="fw-bold text-muted mb-3"><span class="badge bg-primary me-2">فحص طبي</span> التقرير والتشخيص</h6>
                            <?php if ($v['diagnosis']): ?>
                                <p class="mb-2"><strong>الطبيب المعالج:</strong> د. <?php echo htmlspecialchars($v['doctor_name']); ?></p>
                                <div class="bg-light p-3 rounded-3 mb-2">
                                    <strong class="d-block text-dark mb-1">التشخيص:</strong>
                                    <?php echo nl2br(htmlspecialchars($v['diagnosis'])); ?>
                                </div>
                                <?php if($v['treatment_plan']): ?>
                                    <div class="bg-light p-3 rounded-3 mb-2">
                                        <strong class="d-block text-dark mb-1">خطة العلاج:</strong>
                                        <?php echo nl2br(htmlspecialchars($v['treatment_plan'])); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if($v['prescriptions']): ?>
                                    <div class="bg-light p-3 rounded-3">
                                        <strong class="d-block text-dark mb-1">الأدوية الموصوفة:</strong>
                                        <?php echo nl2br(htmlspecialchars($v['prescriptions'])); ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-secondary border-0 py-2">لا يوجد تقرير طبي مسجل. <?php echo $v['status'] == 'in_treatment' || $v['status'] == 'waiting' ? '(المريض لم ينته من العلاج)' : ''; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-end text-muted small pb-3">
                    الحالة النهائية للزيارة: <strong>
                        <?php 
                            if($v['status'] == 'discharged') echo 'خروج / شفاء';
                            elseif($v['status'] == 'admitted') echo 'تنويم / دخول المستشفى';
                            else echo 'تحت الإجراء / غير منتهية';
                        ?>
                    </strong>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
@media print {
    .navbar, .btn, .alert, .timeline .position-absolute {
        display: none !important;
    }
    body {
        background-color: white !important;
        padding: 0 !important;
    }
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
        page-break-inside: avoid;
        margin-bottom: 20px !important;
    }
    .border-start {
        border-left: none !important;
    }
    .timeline {
        margin-left: 0 !important;
        padding-left: 0 !important;
    }
}
</style>
