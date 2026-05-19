<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">إضافة موظف جديد</h2>
    <a href="/users" class="btn btn-outline-secondary px-4">عودة للقائمة</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <form action="/users/create" method="post">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-muted">الاسم الكامل <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control form-control-lg bg-light" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-muted">المنصب (الصلاحيات) <span class="text-danger">*</span></label>
                    <select name="role" class="form-select form-select-lg bg-light" required>
                        <option value="receptionist">موظف استقبال</option>
                        <option value="nurse">ممرض / ممرضة</option>
                        <option value="doctor">طبيب</option>
                        <option value="admin">مدير نظام</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-muted">اسم المستخدم (للدخول) <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control form-control-lg bg-light" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-muted">كلمة المرور المؤقتة <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control form-control-lg bg-light" required>
                </div>
            </div>

            <div class="d-flex justify-content-end border-top pt-4">
                <a href="/users" class="btn btn-light btn-lg me-3 px-5">إلغاء</a>
                <button type="submit" class="btn btn-primary btn-lg fw-bold px-5">حفظ وإضافة الموظف</button>
            </div>
        </form>
    </div>
</div>
