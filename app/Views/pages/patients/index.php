<?php use App\Core\Application; ?>
<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-user-injured"></i>إدارة المرضى</span>
        <h1 class="page-title mt-3">سجل المرضى</h1>
        <p class="page-subtitle">عرض سريع ومنظّم للملفات الأساسية مع وصول مباشر للملف الطبي أو فتح زيارة طوارئ.</p>
    </div>
    <div class="page-actions">
        <a href="/patients/create" class="btn btn-primary"><i class="fas fa-user-plus ms-2"></i>تسجيل مريض جديد</a>
    </div>
</section>

<?php if (Application::$app->session->getFlash('success')): ?>
    <div class="alert alert-success">
        <i class="fas fa-circle-check ms-2"></i>
        <?php echo Application::$app->session->getFlash('success'); ?>
    </div>
<?php endif; ?>

<section class="table-card">
    <div class="table-card-header">
        <div>
            <h3 class="section-title mb-1">قائمة المرضى</h3>
            <p class="section-subtitle">واجهة أبسط مع تركيز على البيانات الأساسية والإجراءات المتكررة.</p>
        </div>
        <span class="badge-soft badge-soft-primary"><i class="fas fa-database"></i><?php echo !empty($patients) ? count($patients) : 0; ?> سجل</span>
    </div>

    <div class="table-shell">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>رقم الملف</th>
                    <th>الرقم القومي</th>
                    <th>الاسم الرباعي</th>
                    <th>رقم الهاتف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($patients)): ?>
                    <?php foreach ($patients as $p): ?>
                        <tr>
                            <td data-label="رقم الملف"><span class="badge-soft badge-soft-neutral">#<?php echo $p['id']; ?></span></td>
                            <td data-label="الرقم القومي"><?php echo htmlspecialchars($p['national_id']); ?></td>
                            <td data-label="الاسم">
                                <div class="data-stack">
                                    <span class="data-value"><?php echo htmlspecialchars($p['full_name']); ?></span>
                                    <span class="data-label">ملف مريض نشط</span>
                                </div>
                            </td>
                            <td data-label="الهاتف"><?php echo htmlspecialchars($p['phone'] ?: 'غير محدد'); ?></td>
                            <td data-label="الإجراءات">
                                <div class="action-group">
                                    <a href="/patients/view?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-info">تفاصيل الملف</a>
                                    <a href="/visits/create?patient_id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger">إدخال للطوارئ</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-user-plus"></i></div>
                                <h3 class="section-title">لا يوجد مرضى مسجلون حالياً</h3>
                                <p class="empty-copy">ابدأ بإضافة أول ملف مريض لتمكين تسجيل الزيارات ومتابعة التاريخ الطبي.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
