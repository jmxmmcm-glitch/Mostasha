<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-cogs"></i> إعدادات المركز الطبي</h2>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-5">
        <form action="/settings" method="post">
            <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">البيانات الأساسية للعيادة / المستشفى</h5>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-muted">اسم المركز <span class="text-danger">*</span></label>
                    <input type="text" name="hospital_name" value="<?php echo htmlspecialchars($setting->hospital_name); ?>" class="form-control form-control-lg bg-light" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-muted">الرقم الضريبي (VAT)</label>
                    <input type="text" name="vat_number" value="<?php echo htmlspecialchars($setting->vat_number); ?>" class="form-control form-control-lg bg-light">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-muted">رقم الهاتف للعملاء</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($setting->phone); ?>" class="form-control form-control-lg bg-light">
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-muted">عنوان المركز (يطبع على الفاتورة)</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($setting->address); ?>" class="form-control form-control-lg bg-light">
                </div>
            </div>

            <div class="d-flex justify-content-end pt-3 mt-4 border-top">
                <button type="submit" class="btn btn-primary btn-lg fw-bold px-5"><i class="fas fa-save"></i> تحديث الإعدادات</button>
            </div>
        </form>
    </div>
</div>
