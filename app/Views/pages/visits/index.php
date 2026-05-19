<?php use App\Core\Application; ?>
<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-briefcase-medical"></i>متابعة الحالات</span>
        <h1 class="page-title mt-3">سجل زيارات الطوارئ</h1>
        <p class="page-subtitle">حالة كل زيارة وإجراءها التالي تظهر في جدول أوضح وأسهل للمراجعة السريعة.</p>
    </div>
    <div class="page-actions">
        <a href="/patients" class="btn btn-primary"><i class="fas fa-plus ms-2"></i>تسجيل زيارة جديدة</a>
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
            <h3 class="section-title mb-1">قائمة الزيارات الحالية</h3>
            <p class="section-subtitle">عرض مبسّط لمسار كل حالة من الاستقبال وحتى إغلاق الزيارة.</p>
        </div>
        <span class="badge-soft badge-soft-warning"><i class="fas fa-timer"></i><?php echo !empty($visits) ? count($visits) : 0; ?> زيارة</span>
    </div>

    <div class="table-shell">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>رقم الزيارة</th>
                    <th>اسم المريض</th>
                    <th>وقت الوصول</th>
                    <th>الحالة</th>
                    <th>الأولوية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($visits)): ?>
                    <?php foreach ($visits as $v): ?>
                        <tr>
                            <td data-label="رقم الزيارة"><span class="badge-soft badge-soft-neutral">#<?php echo $v['id']; ?></span></td>
                            <td data-label="اسم المريض">
                                <div class="data-stack">
                                    <span class="data-value"><?php echo htmlspecialchars($v['full_name']); ?></span>
                                    <span class="data-label">ملف طوارئ نشط</span>
                                </div>
                            </td>
                            <td data-label="وقت الوصول"><?php echo date('Y-m-d H:i', strtotime($v['arrival_time'])); ?></td>
                            <td data-label="الحالة">
                                <?php if ($v['status'] == 'triage'): ?>
                                    <span class="badge-soft badge-soft-warning">فرز طبي</span>
                                <?php elseif ($v['status'] == 'in_treatment'): ?>
                                    <span class="badge-soft badge-soft-primary">قيد العلاج</span>
                                <?php elseif ($v['status'] == 'waiting'): ?>
                                    <span class="badge-soft badge-soft-info">بانتظار الطبيب</span>
                                <?php elseif ($v['status'] == 'discharged'): ?>
                                    <span class="badge-soft badge-soft-success">خروج</span>
                                <?php elseif ($v['status'] == 'admitted'): ?>
                                    <span class="badge-soft badge-soft-danger">تنويم</span>
                                <?php else: ?>
                                    <span class="badge-soft badge-soft-neutral"><?php echo htmlspecialchars($v['status']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td data-label="الأولوية">
                                <?php if ($v['priority'] == 'critical'): ?>
                                    <span class="badge-soft badge-soft-danger">حرجة</span>
                                <?php elseif ($v['priority'] == 'urgent'): ?>
                                    <span class="badge-soft badge-soft-warning">عاجلة</span>
                                <?php else: ?>
                                    <span class="badge-soft badge-soft-success">غير عاجلة</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="الإجراءات">
                                <div class="action-group">
                                    <?php $role = \App\Core\Application::$app->user->role; ?>
                                    <?php if ($v['status'] == 'triage'): ?>
                                        <?php if ($role == 'admin' || $role == 'nurse'): ?>
                                            <a href="/triage/create?visit_id=<?php echo $v['id']; ?>" class="btn btn-sm btn-warning">إجراء الفرز</a>
                                        <?php else: ?>
                                            <span class="data-label"><i class="fas fa-clock ms-1"></i>في الفرز</span>
                                        <?php endif; ?>
                                    <?php elseif ($v['status'] == 'waiting' || $v['status'] == 'in_treatment'): ?>
                                        <?php if ($role == 'admin' || $role == 'doctor'): ?>
                                            <a href="/doctor/create?visit_id=<?php echo $v['id']; ?>" class="btn btn-sm btn-primary">فحص الطبيب</a>
                                        <?php else: ?>
                                            <span class="data-label"><i class="fas fa-user-doctor ms-1"></i>عند الطبيب</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge-soft badge-soft-success"><i class="fas fa-check ms-1"></i>مكتمل</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-notes-medical"></i></div>
                                <h3 class="section-title">لا توجد زيارات طوارئ حالية</h3>
                                <p class="empty-copy">ابدأ من سجل المرضى لفتح زيارة جديدة وربطها بالملف الطبي المناسب.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
