<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">تسجيل زيارة طوارئ جديدة</h2>
    <a href="/patients" class="btn btn-outline-secondary px-4">عودة للبحث عن مريض</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <?php if ($patient): ?>
            <div class="alert alert-info mb-4 border-0 rounded-3">
                <i class="fs-5 me-2">🏥</i>
                <strong>المريض المحدد:</strong> <?php echo htmlspecialchars($patient['full_name']); ?> 
                (الرقم القومي: <?php echo htmlspecialchars($patient['national_id']); ?>)
            </div>
            
            <form action="/visits/create" method="post">
                <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-muted">حالة الزيارة المبدئية</label>
                        <select name="status" class="form-select form-select-lg bg-light">
                            <option value="triage" selected>فرز طبي (Triage)</option>
                            <option value="waiting">انتظار في الاستقبال</option>
                            <option value="in_treatment">إدخال مباشر للعلاج (حالة حرجة)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-muted">أولوية الحالة المبدئية (تقييم الاستقبال)</label>
                        <select name="priority" class="form-select form-select-lg bg-light">
                            <option value="non-urgent" selected>غير عاجلة</option>
                            <option value="urgent">عاجلة</option>
                            <option value="critical">حرجة (إنقاذ حياة)</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end border-top pt-4">
                    <a href="/patients" class="btn btn-light btn-lg me-3 px-5">إلغاء</a>
                    <button type="submit" class="btn btn-danger btn-lg fw-bold px-5">تأكيد فتح ملف طوارئ</button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning border-0 rounded-3">
                يرجى تحديد مريض أولاً من <a href="/patients" class="fw-bold">سجل المرضى</a> لتسجيل زيارة طوارئ.
            </div>
        <?php endif; ?>
    </div>
</div>
