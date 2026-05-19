<?php use App\Core\Application; ?>
<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-users-gear"></i>الإدارة والصلاحيات</span>
        <h1 class="page-title mt-3">إدارة الموظفين والصلاحيات</h1>
        <p class="page-subtitle">عرض منظم لأعضاء الفريق مع أدوارهم داخل النظام لتسهيل الوصول والإشراف.</p>
    </div>
    <div class="page-actions">
        <a href="/users/create" class="btn btn-primary"><i class="fas fa-user-plus ms-2"></i>إضافة موظف جديد</a>
    </div>
</section>

<section class="table-card">
    <div class="table-card-header">
        <div>
            <h3 class="section-title mb-1">قائمة الموظفين</h3>
            <p class="section-subtitle">الصلاحيات معروضة بوضوح مع تصميم أكثر هدوءاً وملاءمة للشاشات الصغيرة.</p>
        </div>
        <span class="badge-soft badge-soft-primary"><i class="fas fa-user-shield"></i><?php echo !empty($users) ? count($users) : 0; ?> موظف</span>
    </div>

    <div class="table-shell">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>م</th>
                    <th>الاسم الكامل</th>
                    <th>اسم المستخدم</th>
                    <th>المنصب / الصلاحية</th>
                    <th>تاريخ الإضافة</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td data-label="م"><span class="badge-soft badge-soft-neutral">#<?php echo $u['id']; ?></span></td>
                            <td data-label="الاسم الكامل">
                                <div class="data-stack">
                                    <span class="data-value"><?php echo htmlspecialchars($u['full_name']); ?></span>
                                    <span class="data-label">عضو ضمن النظام</span>
                                </div>
                            </td>
                            <td data-label="اسم المستخدم"><span class="badge-soft badge-soft-neutral"><?php echo htmlspecialchars($u['username']); ?></span></td>
                            <td data-label="المنصب">
                                <?php if($u['role'] == 'admin'): ?>
                                    <span class="badge-soft badge-soft-danger">مدير نظام</span>
                                <?php elseif($u['role'] == 'doctor'): ?>
                                    <span class="badge-soft badge-soft-primary">طبيب</span>
                                <?php elseif($u['role'] == 'nurse'): ?>
                                    <span class="badge-soft badge-soft-info">ممرض / ممرضة</span>
                                <?php else: ?>
                                    <span class="badge-soft badge-soft-success">استقبال</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="تاريخ الإضافة"><?php echo date('Y-m-d', strtotime($u['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-users"></i></div>
                                <h3 class="section-title">لا يوجد موظفون مضافون</h3>
                                <p class="empty-copy">يمكنك إنشاء حسابات للأطباء والتمريض والاستقبال وتوزيع الصلاحيات من هنا.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
