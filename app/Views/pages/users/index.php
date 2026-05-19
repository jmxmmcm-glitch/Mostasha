<?php use App\Core\Application; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-users"></i> إدارة الموظفين والصلاحيات</h2>
    <a href="/users/create" class="btn btn-primary fw-bold px-4">+ إضافة موظف جديد</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">م</th>
                        <th>الاسم الكامل</th>
                        <th>اسم المستخدم (للدخول)</th>
                        <th>المنصب / الصلاحية</th>
                        <th class="pe-4">تاريخ الإضافة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="ps-4"><strong>#<?php echo $u['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($u['full_name']); ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($u['username']); ?></span></td>
                            <td>
                                <?php if($u['role'] == 'admin'): ?>
                                    <span class="badge bg-danger">مدير نظام</span>
                                <?php elseif($u['role'] == 'doctor'): ?>
                                    <span class="badge bg-primary">طبيب</span>
                                <?php elseif($u['role'] == 'nurse'): ?>
                                    <span class="badge bg-info text-dark">ممرض/ة</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">استقبال</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4"><?php echo date('Y-m-d', strtotime($u['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted py-5">لا يوجد موظفين.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
