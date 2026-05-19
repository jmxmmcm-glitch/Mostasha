<?php
use App\Core\Application;
?>
<div class="row justify-content-center w-100">
    <div class="col-md-5">
        <div class="card shadow border-0 rounded-4">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary mb-2">نظام الطوارئ الطبية</h2>
                    <p class="text-muted">تسجيل الدخول للموظفين والأطباء</p>
                </div>
                
                <?php if (Application::$app->session->getFlash('error')): ?>
                    <div class="alert alert-danger rounded-3">
                        <?php echo Application::$app->session->getFlash('error') ?>
                    </div>
                <?php endif; ?>

                <form action="/login" method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم المستخدم</label>
                        <input type="text" name="username" class="form-control form-control-lg" placeholder="أدخل اسم المستخدم" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">كلمة المرور</label>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="أدخل كلمة المرور" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3">دخول للنظام</button>
                </form>
            </div>
        </div>
    </div>
</div>
