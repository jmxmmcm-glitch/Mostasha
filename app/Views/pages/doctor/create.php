<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-user-doctor"></i>الفحص الطبي</span>
        <h1 class="page-title mt-3">فحص الطبيب والملف الطبي</h1>
        <p class="page-subtitle">مراجعة بيانات المريض ونتائج الفرز ثم توثيق التشخيص وخطة العلاج والقرار النهائي.</p>
    </div>
    <div class="page-actions">
        <a href="/visits" class="btn btn-outline-secondary"><i class="fas fa-arrow-right ms-2"></i>عودة للسجل</a>
    </div>
</section>

<section class="overview-grid">
    <article class="content-card span-4">
        <div class="content-card-header">
            <div>
                <h3 class="section-title mb-1">ملخص الحالة</h3>
                <p class="section-subtitle">عرض سريع للبيانات الأساسية قبل اتخاذ القرار الطبي.</p>
            </div>
        </div>

        <div class="d-flex flex-column gap-3 mb-4">
            <div class="summary-item">
                <div class="data-label mb-1">الاسم</div>
                <div class="data-value"><?php echo htmlspecialchars($visit['full_name']); ?></div>
            </div>
            <div class="summary-item">
                <div class="data-label mb-1">الرقم القومي</div>
                <div class="data-value"><?php echo htmlspecialchars($visit['national_id']); ?></div>
            </div>
            <div class="summary-item">
                <div class="data-label mb-1">العمر</div>
                <div class="data-value"><?php echo date_diff(date_create($visit['dob']), date_create('today'))->y; ?> سنة</div>
            </div>
        </div>

        <?php if (!empty($triage)): ?>
            <div class="surface-card p-3">
                <h4 class="section-title mb-3">نتائج الفرز</h4>
                <div class="d-flex flex-column gap-3">
                    <div class="summary-item">
                        <div class="data-label mb-1">ضغط الدم</div>
                        <div class="data-value"><?php echo htmlspecialchars($triage['blood_pressure']); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="data-label mb-1">نبض القلب</div>
                        <div class="data-value"><?php echo htmlspecialchars($triage['heart_rate']); ?> bpm</div>
                    </div>
                    <div class="summary-item">
                        <div class="data-label mb-1">درجة الحرارة</div>
                        <div class="data-value"><?php echo htmlspecialchars($triage['temperature']); ?> °C</div>
                    </div>
                    <div class="summary-item">
                        <div class="data-label mb-1">ملاحظات التمريض</div>
                        <div class="empty-copy mb-0"><?php echo nl2br(htmlspecialchars($triage['notes'])); ?></div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mb-0">لا توجد بيانات فرز مسجلة لهذه الزيارة.</div>
        <?php endif; ?>
    </article>

    <article class="form-panel span-8">
        <div class="content-card-header">
            <div>
                <h3 class="section-title mb-1">التشخيص والعلاج</h3>
                <p class="section-subtitle">اكتب التقييم الطبي بشكل منظّم ثم حدّث النتيجة النهائية للزيارة.</p>
            </div>
            <span class="badge-soft badge-soft-primary"><i class="fas fa-file-waveform"></i>قرار طبي موثق</span>
        </div>

        <form action="/doctor/create" method="post">
            <input type="hidden" name="visit_id" value="<?php echo $visit['id']; ?>">
            <div class="row g-4">
                <div class="col-12">
                    <label class="form-label">التشخيص الطبي (Diagnosis) <span class="text-danger">*</span></label>
                    <textarea name="diagnosis" class="form-control form-control-lg" rows="4" required placeholder="اكتب التشخيص هنا..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">خطة العلاج (Treatment Plan)</label>
                    <textarea name="treatment_plan" class="form-control form-control-lg" rows="4" placeholder="الإجراءات المتخذة في الطوارئ..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">الوصفة الطبية (Prescriptions)</label>
                    <textarea name="prescriptions" class="form-control form-control-lg" rows="4" placeholder="الأدوية الموصوفة..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">القرار الطبي النهائي</label>
                    <select name="next_status" class="form-select form-select-lg">
                        <option value="discharged" selected>خروج (Discharged)</option>
                        <option value="admitted">تنويم / دخول المستشفى (Admitted)</option>
                    </select>
                </div>
            </div>

            <div class="form-actions mt-4">
                <p class="empty-copy mb-0">عند الحفظ سيتم إغلاق المسار الحالي وتحديث حالة الزيارة فوراً.</p>
                <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk ms-2"></i>حفظ وإنهاء الزيارة</button>
            </div>
        </form>
    </article>
</section>
