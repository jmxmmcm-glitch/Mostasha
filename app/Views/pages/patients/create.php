<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">إضافة مريض جديد</h2>
    <a href="/patients" class="btn btn-outline-secondary px-4">عودة للسجل</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <form action="/patients/create" method="post">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-muted">الاسم الرباعي <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control form-control-lg bg-light" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-muted">الرقم القومي / الإقامة <span class="text-danger">*</span></label>
                    <input type="text" name="national_id" class="form-control form-control-lg bg-light" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <label class="form-label fw-bold text-muted">تاريخ الميلاد</label>
                    <input type="date" name="dob" class="form-control form-control-lg bg-light">
                </div>
                <div class="col-md-4 mb-4">
                    <label class="form-label fw-bold text-muted">الجنس</label>
                    <select name="gender" class="form-select form-select-lg bg-light">
                        <option value="male">ذكر</option>
                        <option value="female">أنثى</option>
                    </select>
                </div>
                <div class="col-md-4 mb-4">
                    <label class="form-label fw-bold text-muted">رقم الهاتف</label>
                    <input type="text" name="phone" class="form-control form-control-lg bg-light">
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold text-muted">العنوان / السكن</label>
                <textarea name="address" class="form-control form-control-lg bg-light" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-end border-top pt-4">
                <a href="/patients" class="btn btn-light btn-lg me-3 px-5">إلغاء</a>
                <button type="submit" class="btn btn-primary btn-lg fw-bold px-5">حفظ بيانات المريض</button>
            </div>
        </form>
    </div>
</div>
