<?php $userName = \App\Core\Application::$app->user->full_name ?? ''; ?>
<section class="page-header">
    <div>
        <span class="header-chip"><i class="fas fa-house-medical"></i>مركز القيادة اليومي</span>
        <h1 class="page-title mt-3">مرحباً، <?php echo htmlspecialchars($userName); ?></h1>
        <p class="page-subtitle">متابعة فورية للحالات النشطة والفرز والسعة التشغيلية داخل مركز الطوارئ.</p>
    </div>
    <div class="page-actions">
        <a href="/patients/create" class="btn btn-primary"><i class="fas fa-user-plus ms-2"></i>إضافة مريض</a>
        <a href="/visits" class="btn btn-outline-secondary"><i class="fas fa-briefcase-medical ms-2"></i>سجل الزيارات</a>
    </div>
</section>

<section class="metrics-grid">
    <article class="metric-card span-4">
        <span class="metric-icon"><i class="fas fa-triangle-exclamation"></i></span>
        <p class="metric-label">حالات الطوارئ النشطة</p>
        <h2 class="metric-value"><?php echo $active_cases ?? 0; ?></h2>
        <p class="metric-note">جميع الحالات المفتوحة التي لم تُغلق بعد داخل النظام.</p>
    </article>

    <article class="metric-card span-4">
        <span class="metric-icon"><i class="fas fa-stethoscope"></i></span>
        <p class="metric-label">في قسم الفرز</p>
        <h2 class="metric-value"><?php echo $triage_cases ?? 0; ?></h2>
        <p class="metric-note">مرضى بانتظار استكمال العلامات الحيوية والتحويل للطبيب.</p>
    </article>

    <article class="metric-card span-4">
        <span class="metric-icon"><i class="fas fa-bed"></i></span>
        <p class="metric-label">أسرّة متاحة</p>
        <h2 class="metric-value"><?php echo $available_beds ?? 15; ?></h2>
        <p class="metric-note">سعة متاحة حالياً لاستقبال حالات تحتاج إلى تنويم أو متابعة.</p>
    </article>
</section>

<section class="overview-grid">
    <article class="content-card span-8">
        <div class="content-card-header">
            <div>
                <h3 class="section-title mb-1">نظرة تشغيلية سريعة</h3>
                <p class="section-subtitle">واجهة ملخّصة لتوزيع المهام اليومية دون ازدحام بصري.</p>
            </div>
            <span class="badge-soft badge-soft-primary"><i class="fas fa-wave-square"></i>جاهزية لحظية</span>
        </div>

        <div class="detail-grid">
            <div class="detail-card span-6">
                <p class="metric-label">أولوية الفريق</p>
                <h4 class="key-value mb-2">تقليل زمن الانتظار</h4>
                <p class="empty-copy mb-0">ابدأ بالحالات الموجودة في الفرز ثم انتقل للحالات تحت العلاج لإغلاق الدورة التشغيلية بسرعة.</p>
            </div>
            <div class="detail-card span-6">
                <p class="metric-label">توصية تشغيلية</p>
                <h4 class="key-value mb-2">توزيع واضح للمهام</h4>
                <p class="empty-copy mb-0">الممرضون يتابعون الفرز، الأطباء يكملون التشخيص، والإدارة تراجع الفواتير والموظفين.</p>
            </div>
        </div>
    </article>

    <article class="content-card span-4">
        <div class="content-card-header">
            <div>
                <h3 class="section-title mb-1">اختصارات سريعة</h3>
                <p class="section-subtitle">وصول مباشر لأكثر المسارات استخداماً.</p>
            </div>
        </div>

        <div class="d-grid gap-3">
            <a href="/patients" class="quick-action">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div>
                        <div class="key-value mb-1">إدارة المرضى</div>
                        <div class="empty-copy">عرض السجل والملفات الطبية</div>
                    </div>
                    <i class="fas fa-chevron-left text-muted"></i>
                </div>
            </a>
            <a href="/visits" class="quick-action">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div>
                        <div class="key-value mb-1">زيارات الطوارئ</div>
                        <div class="empty-copy">متابعة الحالات النشطة وخطوات العلاج</div>
                    </div>
                    <i class="fas fa-chevron-left text-muted"></i>
                </div>
            </a>
            <a href="/settings" class="quick-action">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div>
                        <div class="key-value mb-1">إعدادات المنشأة</div>
                        <div class="empty-copy">تحديث بيانات المركز والضريبة والتواصل</div>
                    </div>
                    <i class="fas fa-chevron-left text-muted"></i>
                </div>
            </a>
        </div>
    </article>
</section>
