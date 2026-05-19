<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-heart-pulse"></i>الفرز الطبي</span>
        <h1 class="page-title mt-3">الفرز والعلامات الحيوية</h1>
        <p class="page-subtitle">تسجيل سريع لبيانات المريض الحيوية مع عرض مختصر للحالة قبل التحويل للطبيب.</p>
    </div>
    <div class="page-actions">
        <a href="/visits" class="btn btn-outline-secondary"><i class="fas fa-arrow-right ms-2"></i>عودة للسجل</a>
    </div>
</section>

<section class="overview-grid">
    <article class="content-card span-4">
        <div class="content-card-header">
            <div>
                <h3 class="section-title mb-1">بيانات المريض</h3>
                <p class="section-subtitle">معلومات الوصول والحالة الأولية.</p>
            </div>
        </div>
        <div class="d-flex flex-column gap-3">
            <div class="summary-item">
                <div class="data-label mb-1">الاسم</div>
                <div class="data-value"><?php echo htmlspecialchars($visit['full_name']); ?></div>
            </div>
            <div class="summary-item">
                <div class="data-label mb-1">الرقم القومي</div>
                <div class="data-value"><?php echo htmlspecialchars($visit['national_id']); ?></div>
            </div>
            <div class="summary-item">
                <div class="data-label mb-1">الجنس</div>
                <div class="data-value"><?php echo $visit['gender'] == 'male' ? 'ذكر' : 'أنثى'; ?></div>
            </div>
            <div class="summary-item">
                <div class="data-label mb-1">وقت الوصول</div>
                <div class="data-value"><?php echo date('H:i', strtotime($visit['arrival_time'])); ?></div>
            </div>
            <div class="summary-item">
                <div class="data-label mb-1">الأولوية المبدئية</div>
                <div>
                    <?php if($visit['priority'] == 'critical'): ?>
                        <span class="badge-soft badge-soft-danger">حرجة</span>
                    <?php elseif($visit['priority'] == 'urgent'): ?>
                        <span class="badge-soft badge-soft-warning">عاجلة</span>
                    <?php else: ?>
                        <span class="badge-soft badge-soft-success">غير عاجلة</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </article>

    <article class="form-panel span-8">
        <div class="content-card-header">
            <div>
                <h3 class="section-title mb-1">تسجيل العلامات الحيوية</h3>
                <p class="section-subtitle">أدخل البيانات الأساسية ثم أضف ملاحظات التمريض قبل تحويل الحالة للطبيب.</p>
            </div>
            <span class="badge-soft badge-soft-warning"><i class="fas fa-stethoscope"></i>خطوة تمريضية</span>
        </div>

        <form action="/triage/create" method="post">
            <input type="hidden" name="visit_id" value="<?php echo $visit['id']; ?>">
            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label">ضغط الدم (BP)</label>
                    <input type="text" name="blood_pressure" class="form-control form-control-lg" placeholder="مثال: 120/80">
                </div>
                <div class="col-md-4">
                    <label class="form-label">نبض القلب (HR)</label>
                    <div class="input-group">
                        <input type="number" name="heart_rate" class="form-control form-control-lg">
                        <span class="input-group-text">bpm</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">درجة الحرارة (Temp)</label>
                    <div class="input-group">
                        <input type="number" step="0.1" name="temperature" class="form-control form-control-lg">
                        <span class="input-group-text">°C</span>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">ملاحظات التمريض وأعراض الشكوى</label>
                    <textarea name="notes" class="form-control form-control-lg" rows="4"></textarea>
                </div>
            </div>

            <div class="form-actions mt-4">
                <p class="empty-copy mb-0">سيتم نقل الحالة بعد الحفظ إلى مرحلة الطبيب داخل النظام.</p>
                <button type="submit" class="btn btn-warning"><i class="fas fa-arrow-left ms-2"></i>حفظ وتحويل للطبيب</button>
            </div>
        </form>
    </article>
</section>
