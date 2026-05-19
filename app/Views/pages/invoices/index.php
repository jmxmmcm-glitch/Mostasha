<?php use App\Core\Application; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-file-invoice-dollar"></i> الفواتير والمحاسبة</h2>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">رقم الفاتورة</th>
                        <th>المريض</th>
                        <th>تاريخ الإصدار</th>
                        <th>المبلغ (غير شامل)</th>
                        <th>الضريبة (15%)</th>
                        <th>الإجمالي</th>
                        <th>الحالة</th>
                        <th class="pe-4">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($invoices)): ?>
                        <?php foreach ($invoices as $i): ?>
                        <tr>
                            <td class="ps-4"><strong>#INV-<?php echo $i['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($i['full_name']); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($i['invoice_date'])); ?></td>
                            <td><?php echo $i['subtotal']; ?> ﷼</td>
                            <td><?php echo $i['vat_amount']; ?> ﷼</td>
                            <td><strong class="text-primary"><?php echo $i['total_amount']; ?> ﷼</strong></td>
                            <td>
                                <?php if($i['status'] == 'paid'): ?>
                                    <span class="badge bg-success">مدفوعة</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">غير مدفوعة</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4">
                                <?php if($i['status'] == 'unpaid'): ?>
                                    <a href="/invoices/pay?id=<?php echo $i['id']; ?>" class="btn btn-sm btn-success text-white"><i class="fas fa-money-bill-wave"></i> سداد</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center text-muted py-5">لا توجد فواتير مصدرة حالياً.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
