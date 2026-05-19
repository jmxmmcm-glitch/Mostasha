<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-address-card"></i>تسجيل ملف جديد</span>
        <h1 class="page-title mt-3">إضافة مريض جديد</h1>
        <p class="page-subtitle">نموذج واضح ومقسّم بعناية لتسجيل البيانات الأساسية بسرعة داخل الاستقبال.</p>
    </div>
    <div class="page-actions">
        <a href="/patients" class="btn btn-outline-secondary"><i class="fas fa-arrow-right ms-2"></i>عودة للسجل</a>
    </div>
</section>

<section class="form-panel">
    <div class="content-card-header">
        <div>
            <h3 class="section-title mb-1">البيانات الأساسية للمريض</h3>
            <p class="section-subtitle">جميع الحقول المهمة موضوعة بتسلسل يسهّل الإدخال من الموبايل وسطح المكتب.</p>
        </div>
        <span class="badge-soft badge-soft-primary"><i class="fas fa-shield-heart"></i>حقول عربية كاملة</span>
    </div>

    <form action="/patients/create" method="post">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">الاسم الرباعي <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control form-control-lg" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">الرقم القومي / الإقامة <span class="text-danger">*</span></label>
                <input type="text" name="national_id" class="form-control form-control-lg" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">تاريخ الميلاد</label>
                <input type="date" name="dob" class="form-control form-control-lg">
            </div>
            <div class="col-md-4">
                <label class="form-label">الجنس</label>
                <select name="gender" class="form-select form-select-lg">
                    <option value="male">ذكر</option>
                    <option value="female">أنثى</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">رقم الهاتف</label>
                <input type="text" name="phone" class="form-control form-control-lg">
            </div>
            <div class="col-12">
                <label class="form-label">العنوان / السكن</label>
                <textarea name="address" class="form-control form-control-lg" rows="4"></textarea>
            </div>
        </div>

        <div class="form-actions mt-4">
            <p class="empty-copy mb-0">سيظهر الملف مباشرة داخل سجل المرضى بعد الحفظ.</p>
            <div class="action-group">
                <a href="/patients" class="btn btn-light">إلغاء</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk ms-2"></i>حفظ بيانات المريض</button>
            </div>
        </div>
    </form>
</section>
