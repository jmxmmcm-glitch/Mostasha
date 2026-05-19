<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">الفرز الطبي والعلامات الحيوية (Triage)</h2>
    <a href="/visits" class="btn btn-outline-secondary px-4">إلغاء وعودة للسجل</a>
</div>

<div class="row">
    <!-- Patient Info Summary -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <h5 class="fw-bold text-primary mb-3">بيانات المريض</h5>
                <p class="mb-1"><strong>الاسم:</strong> <?php echo htmlspecialchars($visit['full_name']); ?></p>
                <p class="mb-1"><strong>الرقم القومي:</strong> <?php echo htmlspecialchars($visit['national_id']); ?></p>
                <p class="mb-1"><strong>الجنس:</strong> <?php echo $visit['gender'] == 'male' ? 'ذكر' : 'أنثى'; ?></p>
                <hr>
                <p class="mb-1"><strong>وقت الوصول:</strong> <?php echo date('H:i', strtotime($visit['arrival_time'])); ?></p>
                <p class="mb-0"><strong>الأولوية المبدئية:</strong> 
                    <?php if($visit['priority'] == 'critical'): ?>
                        <span class="badge bg-danger">حرجة</span>
                    <?php elseif($visit['priority'] == 'urgent'): ?>
                        <span class="badge bg-warning text-dark">عاجلة</span>
                    <?php else: ?>
                        <span class="badge bg-success">غير عاجلة</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Triage Form -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <form action="/triage/create" method="post">
                    <input type="hidden" name="visit_id" value="<?php echo $visit['id']; ?>">
                    
                    <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">العلامات الحيوية (Vital Signs)</h5>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold text-muted">ضغط الدم (BP)</label>
                            <input type="text" name="blood_pressure" class="form-control form-control-lg bg-light" placeholder="مثال: 120/80">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold text-muted">نبض القلب (HR)</label>
                            <div class="input-group">
                                <input type="number" name="heart_rate" class="form-control form-control-lg bg-light">
                                <span class="input-group-text">bpm</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold text-muted">درجة الحرارة (Temp)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="temperature" class="form-control form-control-lg bg-light">
                                <span class="input-group-text">°C</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted">ملاحظات التمريض وأعراض الشكوى</label>
                        <textarea name="notes" class="form-control form-control-lg bg-light" rows="4"></textarea>
                    </div>

                    <div class="d-flex justify-content-end pt-3">
                        <button type="submit" class="btn btn-warning text-dark btn-lg fw-bold px-5">حفظ وتحويل للطبيب</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
