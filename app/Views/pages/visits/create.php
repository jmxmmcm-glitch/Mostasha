<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-suitcase-medical"></i>فتح ملف طوارئ</span>
        <h1 class="page-title mt-3">تسجيل زيارة طوارئ جديدة</h1>
        <p class="page-subtitle">خطوة سريعة لبدء المسار العلاجي وربط الحالة بالأولوية المناسبة منذ لحظة الوصول.</p>
    </div>
    <div class="page-actions">
        <a href="/patients" class="btn btn-outline-secondary"><i class="fas fa-arrow-right ms-2"></i>عودة للبحث عن مريض</a>
    </div>
</section>

<section class="form-panel">
    <?php if ($patient): ?>
        <div class="info-strip mb-4">
            <span class="info-icon"><i class="fas fa-user"></i></span>
            <div>
                <div class="key-value mb-1"><?php echo htmlspecialchars($patient['full_name']); ?></div>
                <div class="empty-copy">الرقم القومي: <?php echo htmlspecialchars($patient['national_id']); ?></div>
            </div>
        </div>

        <form action="/visits/create" method="post">
            <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">حالة الزيارة المبدئية</label>
                    <select name="status" class="form-select form-select-lg">
                        <option value="triage" selected>فرز طبي (Triage)</option>
                        <option value="waiting">انتظار في الاستقبال</option>
                        <option value="in_treatment">إدخال مباشر للعلاج (حالة حرجة)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">أولوية الحالة المبدئية</label>
                    <select name="priority" class="form-select form-select-lg">
                        <option value="non-urgent" selected>غير عاجلة</option>
                        <option value="urgent">عاجلة</option>
                        <option value="critical">حرجة (إنقاذ حياة)</option>
                    </select>
                </div>
            </div>

            <div class="form-actions mt-4">
                <p class="empty-copy mb-0">بعد الحفظ ستنتقل الحالة مباشرة إلى المسار المحدد داخل لوحة الزيارات.</p>
                <div class="action-group">
                    <a href="/patients" class="btn btn-light">إلغاء</a>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-check ms-2"></i>تأكيد فتح ملف طوارئ</button>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-user-slash"></i></div>
            <h3 class="section-title">لم يتم اختيار مريض بعد</h3>
            <p class="empty-copy">يرجى الانتقال إلى سجل المرضى أولاً ثم اختيار المريض المناسب قبل فتح زيارة طوارئ جديدة.</p>
            <div class="mt-3">
                <a href="/patients" class="btn btn-primary">الذهاب إلى سجل المرضى</a>
            </div>
        </div>
    <?php endif; ?>
</section>
