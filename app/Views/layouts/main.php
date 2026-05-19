<?php 
use App\Core\Application; 
use App\Models\Setting;
$sysSetting = new Setting();
try {
    $sysSetting->loadAll();
} catch (\Exception $e) {} // Fail silently if DB not ready
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($sysSetting->hospital_name ?? 'نظام إدارة الطوارئ الطبية'); ?></title>
    <!-- Bootstrap 5 RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome and SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
    </style>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><i class="fas fa-hospital me-2"></i> <?php echo htmlspecialchars($sysSetting->hospital_name ?? 'المركز الطبي'); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (!Application::isGuest()): ?>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/patients">المرضى</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/visits">زيارات الطوارئ</a>
                    </li>
                    <?php if (Application::$app->user->role === 'admin'): ?>
                    <li class="nav-item ms-3 border-start ps-3 border-secondary d-flex align-items-center gap-2">
                        <a class="nav-link text-warning" href="/invoices"><i class="fas fa-file-invoice-dollar"></i> الحسابات</a>
                        <a class="nav-link text-warning" href="/users"><i class="fas fa-users"></i> الموظفين</a>
                        <a class="nav-link text-warning" href="/settings"><i class="fas fa-cog"></i> الإعدادات</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="nav-link text-light me-3">مرحباً، <?php echo Application::$app->user->full_name; ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white px-3 py-1 mt-1" href="/logout">تسجيل الخروج</a>
                    </li>
                </ul>
                <?php else: ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/login">تسجيل الدخول</a>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        {{content}}
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if (Application::$app->session->getFlash('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'نجاح',
                text: '<?php echo addslashes(Application::$app->session->getFlash("success")); ?>',
                confirmButtonText: 'حسناً',
                confirmButtonColor: '#198754'
            });
        <?php endif; ?>

        <?php if (Application::$app->session->getFlash('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'تنبيه',
                text: '<?php echo addslashes(Application::$app->session->getFlash("error")); ?>',
                confirmButtonText: 'إغلاق',
                confirmButtonColor: '#dc3545'
            });
        <?php endif; ?>
    </script>
</body>
</html>
