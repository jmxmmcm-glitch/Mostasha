<?php use App\Core\Application; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">سجل زيارات الطوارئ</h2>
    <a href="/patients" class="btn btn-primary fw-bold px-4">+ تسجيل زيارة جديدة (اختر مريض)</a>
</div>

<?php if (Application::$app->session->getFlash('success')): ?>
    <div class="alert alert-success border-0 rounded-3">
        <?php echo Application::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">رقم الزيارة</th>
                        <th>اسم المريض</th>
                        <th>وقت الوصول</th>
                        <th>الحالة</th>
                        <th>الأولوية</th>
                        <th class="pe-4">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($visits)): ?>
                        <?php foreach ($visits as $v): ?>
                        <tr>
                            <td class="ps-4"><strong>#<?php echo $v['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($v['full_name']); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($v['arrival_time'])); ?></td>
                            <td>
                                <?php if($v['status'] == 'triage'): ?>
                                    <span class="badge bg-warning text-dark">فرز طبي</span>
                                <?php elseif($v['status'] == 'in_treatment'): ?>
                                    <span class="badge bg-primary">قيد العلاج</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo $v['status']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($v['priority'] == 'critical'): ?>
                                    <span class="badge bg-danger">حرجة</span>
                                <?php elseif($v['priority'] == 'urgent'): ?>
                                    <span class="badge bg-warning text-dark">عاجلة</span>
                                <?php else: ?>
                                    <span class="badge bg-success">غير عاجلة</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4">
                                <?php 
                                    $role = \App\Core\Application::$app->user->role;
                                    if($v['status'] == 'triage'): 
                                ?>
                                    <?php if ($role == 'admin' || $role == 'nurse'): ?>
                                        <a href="/triage/create?visit_id=<?php echo $v['id']; ?>" class="btn btn-sm btn-warning text-dark"><i class="fas fa-stethoscope"></i> إجراء الفرز</a>
                                    <?php else: ?>
                                        <span class="text-muted"><i class="fas fa-clock"></i> في الفرز</span>
                                    <?php endif; ?>
                                <?php elseif($v['status'] == 'waiting' || $v['status'] == 'in_treatment'): ?>
                                    <?php if ($role == 'admin' || $role == 'doctor'): ?>
                                        <a href="/doctor/create?visit_id=<?php echo $v['id']; ?>" class="btn btn-sm btn-primary text-white"><i class="fas fa-user-md"></i> فحص الطبيب</a>
                                    <?php else: ?>
                                        <span class="text-muted"><i class="fas fa-spinner fa-spin"></i> عند الطبيب</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-success"><i class="fas fa-check-circle"></i> مكتمل</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted py-5">لا توجد زيارات طوارئ حالية.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
