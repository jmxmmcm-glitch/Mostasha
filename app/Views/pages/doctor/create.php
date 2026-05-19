<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">فحص الطبيب والملف الطبي</h2>
    <a href="/visits" class="btn btn-outline-secondary px-4">عودة للسجل</a>
</div>

<div class="row">
    <!-- Patient Info and Triage Summary -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body bg-light rounded-4">
                <h5 class="fw-bold text-primary mb-3">بيانات المريض</h5>
                <p class="mb-1"><strong>الاسم:</strong> <?php echo htmlspecialchars($visit['full_name']); ?></p>
                <p class="mb-1"><strong>الرقم القومي:</strong> <?php echo htmlspecialchars($visit['national_id']); ?></p>
                <p class="mb-1"><strong>العمر:</strong> <?php echo date_diff(date_create($visit['dob']), date_create('today'))->y; ?> سنة</p>
            </div>
        </div>

        <?php if (!empty($triage)): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-4 border-start border-warning border-4">
            <div class="card-body">
                <h5 class="fw-bold text-dark mb-3">تقييم الفرز (Triage)</h5>
                <p class="mb-1"><strong>ضغط الدم:</strong> <span class="text-danger fw-bold"><?php echo htmlspecialchars($triage['blood_pressure']); ?></span></p>
                <p class="mb-1"><strong>نبض القلب:</strong> <?php echo htmlspecialchars($triage['heart_rate']); ?> bpm</p>
                <p class="mb-1"><strong>الحرارة:</strong> <?php echo htmlspecialchars($triage['temperature']); ?> °C</p>
                <hr>
                <p class="mb-0 text-muted"><strong>ملاحظات التمريض:</strong><br><?php echo nl2br(htmlspecialchars($triage['notes'])); ?></p>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning border-0 rounded-4">لا توجد بيانات فرز مسجلة لهذا المريض.</div>
        <?php endif; ?>
    </div>
    
    <!-- Medical Examination Form -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <form action="/doctor/create" method="post">
                    <input type="hidden" name="visit_id" value="<?php echo $visit['id']; ?>">
                    
                    <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">التشخيص والعلاج</h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted">التشخيص الطبي (Diagnosis) <span class="text-danger">*</span></label>
                        <textarea name="diagnosis" class="form-control form-control-lg bg-light" rows="3" required placeholder="اكتب التشخيص هنا..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted">خطة العلاج (Treatment Plan)</label>
                        <textarea name="treatment_plan" class="form-control form-control-lg bg-light" rows="3" placeholder="الإجراءات المتخذة في الطوارئ..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted">الوصفة الطبية (Prescriptions)</label>
                        <textarea name="prescriptions" class="form-control form-control-lg bg-light" rows="3" placeholder="الأدوية الموصوفة..."></textarea>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded-3">
                        <label class="form-label fw-bold text-dark">القرار الطبي النهائي (تحديث حالة المريض)</label>
                        <select name="next_status" class="form-select form-select-lg border-primary">
                            <option value="discharged" selected>خروج (Discharged)</option>
                            <option value="admitted">تنويم / دخول المستشفى (Admitted)</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end pt-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold px-5">حفظ وإنهاء الزيارة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
