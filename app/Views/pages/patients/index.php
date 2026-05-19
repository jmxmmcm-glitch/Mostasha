<?php use App\Core\Application; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">سجل المرضى</h2>
    <a href="/patients/create" class="btn btn-primary fw-bold px-4">+ تسجيل مريض جديد</a>
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
                        <th class="ps-4">رقم الملف (ID)</th>
                        <th>الرقم القومي</th>
                        <th>الاسم الرباعي</th>
                        <th>رقم الهاتف</th>
                        <th class="pe-4">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($patients)): ?>
                        <?php foreach ($patients as $p): ?>
                        <tr>
                            <td class="ps-4"><strong>#<?php echo $p['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($p['national_id']); ?></td>
                            <td><?php echo htmlspecialchars($p['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($p['phone']); ?></td>
                            <td class="pe-4">
                                <a href="/patients/view?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-info text-white">تفاصيل وملف طبي</a>
                                <a href="/visits/create?patient_id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger text-white">إدخال للطوارئ</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted py-5">لا يوجد مرضى مسجلين حتى الآن.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
