<?php use App\Core\Application; ?>
<div class="auth-panel">
    <section class="auth-visual">
        <div>
            <span class="header-chip mb-3"><i class="fas fa-wave-pulse"></i>Mostasha Emergency Suite</span>
            <h1 class="hero-title page-title mb-3">إدارة تشغيل الطوارئ الطبية بكفاءة أسرع ووضوح أعلى.</h1>
            <p class="auth-copy">واجهة عربية حديثة مصممة لتسريع الاستقبال والفرز والمتابعة الطبية مع تجربة مرنة للأطباء والتمريض والإدارة.</p>
        </div>

        <div class="hero-kpis">
            <div class="hero-kpi">
                <strong>24/7</strong>
                <span class="list-meta">تشغيل مستمر للمنشأة</span>
            </div>
            <div class="hero-kpi">
                <strong>RTL</strong>
                <span class="list-meta">تجربة عربية احترافية</span>
            </div>
            <div class="hero-kpi">
                <strong>أمان عالي</strong>
                <span class="list-meta">وصول مخصص حسب الصلاحيات</span>
            </div>
            <div class="hero-kpi">
                <strong>سريع</strong>
                <span class="list-meta">واجهة واضحة تقلل التشتت</span>
            </div>
        </div>
    </section>

    <section class="auth-form-wrap">
        <span class="header-chip mb-3"><i class="fas fa-lock"></i>دخول آمن للموظفين</span>
        <h2 class="auth-title">تسجيل الدخول</h2>
        <p class="auth-copy">أدخل بيانات الحساب للمتابعة إلى لوحة التحكم وإدارة الحالات الطبية النشطة.</p>

        <?php if (Application::$app->session->getFlash('error')): ?>
            <div class="alert alert-danger mb-4">
                <i class="fas fa-circle-exclamation ms-2"></i>
                <?php echo Application::$app->session->getFlash('error'); ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="post" class="auth-form">
            <div>
                <label class="form-label">اسم المستخدم</label>
                <input type="text" name="username" class="form-control form-control-lg" placeholder="أدخل اسم المستخدم" required>
            </div>

            <div>
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control form-control-lg" placeholder="أدخل كلمة المرور" required>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-arrow-left-to-bracket ms-2"></i>
                دخول للنظام
            </button>
        </form>

        <p class="auth-footer">مصمم للاستخدام السريع داخل أقسام الطوارئ مع تركيز على الوضوح وسهولة الوصول.</p>
    </section>
</div>
