<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-file-medical"></i>السجل الطبي</span>
        <h1 class="page-title mt-3">السجل الطبي التاريخي</h1>
        <p class="page-subtitle">ملف زمني واضح يعرض الزيارات السابقة، نتائج الفرز، التشخيص، والعلاج بصورة أكثر ترتيباً.</p>
    </div>
    <div class="page-actions">
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print ms-2"></i>طباعة السجل</button>
        <a href="/patients" class="btn btn-outline-secondary"><i class="fas fa-arrow-right ms-2"></i>عودة</a>
    </div>
</section>

<section class="profile-banner">
    <div class="profile-banner-main">
        <span class="profile-icon">👤</span>
        <div>
            <h2 class="profile-name mb-2"><?php echo htmlspecialchars($patient['full_name']); ?></h2>
            <div class="meta-list">
                <span><i class="fas fa-id-card"></i> <?php echo htmlspecialchars($patient['national_id']); ?></span>
                <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($patient['phone'] ?: 'غير محدد'); ?></span>
                <span><i class="fas fa-cake-candles"></i> <?php echo htmlspecialchars($patient['dob'] ?: 'غير محدد'); ?></span>
            </div>
        </div>
    </div>
    <span class="badge-soft badge-soft-primary"><i class="fas fa-notes-medical"></i>ملف مريض متكامل</span>
</section>

<section>
    <div class="content-card-header mb-3">
        <div>
            <h3 class="section-title mb-1">تاريخ زيارات الطوارئ</h3>
            <p class="section-subtitle">يظهر كل حدث في تسلسل زمني يسهل مراجعته وطباعته.</p>
        </div>
    </div>

    <?php if (empty($visits)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-folder-open"></i></div>
            <h3 class="section-title">لا يوجد سجل زيارات سابق</h3>
            <p class="empty-copy">لم يتم توثيق أي زيارة طوارئ لهذا المريض حتى الآن.</p>
        </div>
    <?php else: ?>
        <div class="timeline">
            <?php foreach ($visits as $v): ?>
                <article class="timeline-card">
                    <span class="timeline-marker"></span>
                    <div class="timeline-header mb-3">
                        <div>
                            <h4 class="timeline-title mb-1">زيارة رقم #<?php echo $v['id']; ?></h4>
                            <p class="empty-copy mb-0"><?php echo date('Y-m-d h:i A', strtotime($v['arrival_time'])); ?></p>
                        </div>
                        <div>
                            <?php if($v['status'] == 'discharged'): ?>
                                <span class="badge-soft badge-soft-success">خروج / شفاء</span>
                            <?php elseif($v['status'] == 'admitted'): ?>
                                <span class="badge-soft badge-soft-danger">تنويم / دخول</span>
                            <?php elseif($v['status'] == 'in_treatment' || $v['status'] == 'waiting'): ?>
                                <span class="badge-soft badge-soft-primary">تحت الإجراء</span>
                            <?php else: ?>
                                <span class="badge-soft badge-soft-warning">فرز طبي</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="timeline-body-grid">
                        <div class="timeline-panel">
                            <h6><span class="badge-soft badge-soft-warning ms-2">فرز طبي</span>العلامات الحيوية المبدئية</h6>
                            <?php if ($v['blood_pressure']): ?>
                                <div class="d-flex flex-column gap-3 mt-3">
                                    <div class="summary-item">
                                        <div class="data-label mb-1">ضغط الدم</div>
                                        <div class="data-value"><?php echo htmlspecialchars($v['blood_pressure']); ?></div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="data-label mb-1">النبض</div>
                                        <div class="data-value"><?php echo htmlspecialchars($v['heart_rate']); ?> bpm</div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="data-label mb-1">الحرارة</div>
                                        <div class="data-value"><?php echo htmlspecialchars($v['temperature']); ?> °C</div>
                                    </div>
                                    <?php if($v['triage_notes']): ?>
                                        <div class="summary-item">
                                            <div class="data-label mb-1">ملاحظة تمريض</div>
                                            <div class="empty-copy mb-0"><?php echo htmlspecialchars($v['triage_notes']); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <p class="empty-copy mb-0 mt-3">لم يتم تسجيل تقييم للفرز لهذه الزيارة.</p>
                            <?php endif; ?>
                        </div>

                        <div class="timeline-panel">
                            <h6><span class="badge-soft badge-soft-primary ms-2">فحص طبي</span>التقرير والتشخيص</h6>
                            <?php if ($v['diagnosis']): ?>
                                <div class="d-flex flex-column gap-3 mt-3">
                                    <div class="summary-item">
                                        <div class="data-label mb-1">الطبيب المعالج</div>
                                        <div class="data-value">د. <?php echo htmlspecialchars($v['doctor_name']); ?></div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="data-label mb-1">التشخيص</div>
                                        <div class="empty-copy mb-0"><?php echo nl2br(htmlspecialchars($v['diagnosis'])); ?></div>
                                    </div>
                                    <?php if($v['treatment_plan']): ?>
                                        <div class="summary-item">
                                            <div class="data-label mb-1">خطة العلاج</div>
                                            <div class="empty-copy mb-0"><?php echo nl2br(htmlspecialchars($v['treatment_plan'])); ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($v['prescriptions']): ?>
                                        <div class="summary-item">
                                            <div class="data-label mb-1">الأدوية الموصوفة</div>
                                            <div class="empty-copy mb-0"><?php echo nl2br(htmlspecialchars($v['prescriptions'])); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mt-3 mb-0">لا يوجد تقرير طبي مسجل لهذه الزيارة<?php echo $v['status'] == 'in_treatment' || $v['status'] == 'waiting' ? ' (المريض لم ينته من العلاج بعد)' : ''; ?>.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
