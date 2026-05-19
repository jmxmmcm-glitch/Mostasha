<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-user-gear"></i>إضافة مستخدم</span>
        <h1 class="page-title mt-3">إضافة موظف جديد</h1>
        <p class="page-subtitle">أنشئ حساباً جديداً مع تحديد الصلاحية المناسبة لضمان وصول واضح وآمن داخل النظام.</p>
    </div>
    <div class="page-actions">
        <a href="/users" class="btn btn-outline-secondary"><i class="fas fa-arrow-right ms-2"></i>عودة للقائمة</a>
    </div>
</section>

<section class="form-panel">
    <div class="content-card-header">
        <div>
            <h3 class="section-title mb-1">بيانات الموظف</h3>
            <p class="section-subtitle">تم ترتيب الحقول لتقليل الوقت المطلوب لإضافة أعضاء الفريق.</p>
        </div>
        <span class="badge-soft badge-soft-primary"><i class="fas fa-shield-halved"></i>صلاحيات مرنة</span>
    </div>

    <form action="/users/create" method="post">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control form-control-lg" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">المنصب (الصلاحيات) <span class="text-danger">*</span></label>
                <select name="role" class="form-select form-select-lg" required>
                    <option value="receptionist">موظف استقبال</option>
                    <option value="nurse">ممرض / ممرضة</option>
                    <option value="doctor">طبيب</option>
                    <option value="admin">مدير نظام</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">اسم المستخدم (للدخول) <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control form-control-lg" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">كلمة المرور المؤقتة <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control form-control-lg" required>
            </div>
        </div>

        <div class="form-actions mt-4">
            <p class="empty-copy mb-0">يُفضّل تسليم الموظف كلمة المرور المؤقتة وتحديثها بعد أول دخول.</p>
            <div class="action-group">
                <a href="/users" class="btn btn-light">إلغاء</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus ms-2"></i>حفظ وإضافة الموظف</button>
            </div>
        </div>
    </form>
</section>
