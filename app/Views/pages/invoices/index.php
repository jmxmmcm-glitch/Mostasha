<?php use App\Core\Application; ?>
<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-file-invoice-dollar"></i>المحاسبة الطبية</span>
        <h1 class="page-title mt-3">الفواتير والمحاسبة</h1>
        <p class="page-subtitle">عرض هادئ وواضح للفواتير مع إبراز حالة السداد والإجمالي المستحق لكل مريض.</p>
    </div>
</section>

<section class="table-card">
    <div class="table-card-header">
        <div>
            <h3 class="section-title mb-1">سجل الفواتير</h3>
            <p class="section-subtitle">واجهة أكثر احترافية لعمليات المراجعة اليومية والتحصيل.</p>
        </div>
        <span class="badge-soft badge-soft-primary"><i class="fas fa-receipt"></i><?php echo !empty($invoices) ? count($invoices) : 0; ?> فاتورة</span>
    </div>

    <div class="table-shell">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>رقم الفاتورة</th>
                    <th>المريض</th>
                    <th>تاريخ الإصدار</th>
                    <th>المبلغ</th>
                    <th>الضريبة</th>
                    <th>الإجمالي</th>
                    <th>الحالة</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($invoices)): ?>
                    <?php foreach ($invoices as $i): ?>
                        <tr>
                            <td data-label="رقم الفاتورة"><span class="badge-soft badge-soft-neutral">#INV-<?php echo $i['id']; ?></span></td>
                            <td data-label="المريض">
                                <div class="data-stack">
                                    <span class="data-value"><?php echo htmlspecialchars($i['full_name']); ?></span>
                                    <span class="data-label">فاتورة زيارة طبية</span>
                                </div>
                            </td>
                            <td data-label="تاريخ الإصدار"><?php echo date('Y-m-d H:i', strtotime($i['invoice_date'])); ?></td>
                            <td data-label="المبلغ"><?php echo $i['subtotal']; ?> ﷼</td>
                            <td data-label="الضريبة"><?php echo $i['vat_amount']; ?> ﷼</td>
                            <td data-label="الإجمالي"><strong><?php echo $i['total_amount']; ?> ﷼</strong></td>
                            <td data-label="الحالة">
                                <?php if($i['status'] == 'paid'): ?>
                                    <span class="badge-soft badge-soft-success">مدفوعة</span>
                                <?php else: ?>
                                    <span class="badge-soft badge-soft-danger">غير مدفوعة</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="إجراء">
                                <?php if($i['status'] == 'unpaid'): ?>
                                    <a href="/invoices/pay?id=<?php echo $i['id']; ?>" class="btn btn-sm btn-success">سداد</a>
                                <?php else: ?>
                                    <span class="data-label">تم التحصيل</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-file-circle-xmark"></i></div>
                                <h3 class="section-title">لا توجد فواتير حالياً</h3>
                                <p class="empty-copy">ستظهر الفواتير الصادرة هنا مع تفاصيل السداد والضرائب والإجماليات.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
