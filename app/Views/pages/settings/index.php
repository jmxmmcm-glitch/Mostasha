<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-sliders"></i>إعدادات المنشأة</span>
        <h1 class="page-title mt-3">إعدادات المركز الطبي</h1>
        <p class="page-subtitle">إدارة بيانات المنشأة الأساسية من خلال نموذج أكثر وضوحاً واتساقاً مع بقية النظام.</p>
    </div>
</section>

<section class="overview-grid">
    <article class="settings-summary span-4">
        <div class="content-card-header">
            <div>
                <h3 class="section-title mb-1">ملخص التهيئة</h3>
                <p class="section-subtitle">البيانات الأساسية المستخدمة داخل الواجهة والفواتير.</p>
            </div>
        </div>
        <div class="d-flex flex-column gap-3">
            <div class="summary-item">
                <div class="data-label mb-1">اسم المركز</div>
                <div class="data-value"><?php echo htmlspecialchars($setting->hospital_name); ?></div>
            </div>
            <div class="summary-item">
                <div class="data-label mb-1">رقم الهاتف</div>
                <div class="data-value"><?php echo htmlspecialchars($setting->phone ?: 'غير محدد'); ?></div>
            </div>
            <div class="summary-item">
                <div class="data-label mb-1">الرقم الضريبي</div>
                <div class="data-value"><?php echo htmlspecialchars($setting->vat_number ?: 'غير محدد'); ?></div>
            </div>
        </div>
    </article>

    <article class="form-panel span-8">
        <div class="content-card-header">
            <div>
                <h3 class="section-title mb-1">تحديث البيانات الأساسية</h3>
                <p class="section-subtitle">أي تعديل هنا ينعكس على اسم المركز وبيانات الفواتير داخل النظام.</p>
            </div>
            <span class="badge-soft badge-soft-primary"><i class="fas fa-building"></i>بيانات رسمية</span>
        </div>

        <form action="/settings" method="post" class="settings-form">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">اسم المركز <span class="text-danger">*</span></label>
                    <input type="text" name="hospital_name" value="<?php echo htmlspecialchars($setting->hospital_name); ?>" class="form-control form-control-lg" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الرقم الضريبي (VAT)</label>
                    <input type="text" name="vat_number" value="<?php echo htmlspecialchars($setting->vat_number); ?>" class="form-control form-control-lg">
                </div>
                <div class="col-md-6">
                    <label class="form-label">رقم الهاتف للعملاء</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($setting->phone); ?>" class="form-control form-control-lg">
                </div>
                <div class="col-md-6">
                    <label class="form-label">عنوان المركز</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($setting->address); ?>" class="form-control form-control-lg">
                </div>
            </div>

            <div class="form-actions mt-4">
                <p class="empty-copy mb-0">احرص على تحديث البيانات الرسمية لتظهر بشكل صحيح في المستندات المالية.</p>
                <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk ms-2"></i>تحديث الإعدادات</button>
            </div>
        </form>
    </article>
</section>
