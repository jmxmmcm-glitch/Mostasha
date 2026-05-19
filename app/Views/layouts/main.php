<?php
use App\Core\Application;
use App\Models\Setting;

$sysSetting = new Setting();
try {
    $sysSetting->loadAll();
} catch (\Exception $e) {}

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$hospitalName = htmlspecialchars($sysSetting->hospital_name ?? 'Mostasha');
$user = Application::$app->user ?? null;
$userName = $user ? htmlspecialchars($user->full_name) : '';
$userRole = $user ? $user->role : '';

$navItems = [
    ['href' => '/', 'label' => 'لوحة التحكم', 'icon' => 'fa-house'],
    ['href' => '/patients', 'label' => 'المرضى', 'icon' => 'fa-user-injured'],
    ['href' => '/visits', 'label' => 'زيارات الطوارئ', 'icon' => 'fa-briefcase-medical'],
];

$adminItems = [
    ['href' => '/invoices', 'label' => 'الحسابات', 'icon' => 'fa-file-invoice-dollar'],
    ['href' => '/users', 'label' => 'الموظفون', 'icon' => 'fa-users'],
    ['href' => '/settings', 'label' => 'الإعدادات', 'icon' => 'fa-gear'],
];

$roleLabels = [
    'admin' => 'مدير النظام',
    'doctor' => 'طبيب',
    'nurse' => 'تمريض',
    'receptionist' => 'استقبال',
];

$activeClass = static function (string $path, string $currentPath): string {
    if ($path === '/') {
        return $currentPath === '/' ? 'active' : '';
    }
    return str_starts_with($currentPath, $path) ? 'active' : '';
};
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $hospitalName; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/modern-design-system.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="app-shell" id="appShell">
        <div class="mobile-overlay" id="mobileOverlay"></div>

        <aside class="app-sidebar glass-panel">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <a href="/" class="brand-block">
                    <span class="brand-logo"><i class="fas fa-hospital"></i></span>
                    <span>
                        <h2 class="brand-title"><?php echo $hospitalName; ?></h2>
                        <p class="brand-subtitle">منصة إدارة الطوارئ الطبية</p>
                    </span>
                </a>
                <button class="btn btn-ghost mobile-sidebar-close" id="mobileSidebarClose" type="button" aria-label="إغلاق القائمة">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <?php if (!Application::isGuest()): ?>
                <nav class="sidebar-nav">
                    <span class="nav-group-title">التنقل الرئيسي</span>
                    <?php foreach ($navItems as $item): ?>
                        <a class="sidebar-link <?php echo $activeClass($item['href'], $currentPath); ?>" href="<?php echo $item['href']; ?>">
                            <span class="sidebar-link-start">
                                <i class="fas <?php echo $item['icon']; ?>"></i>
                                <span><?php echo $item['label']; ?></span>
                            </span>
                            <i class="fas fa-chevron-left small opacity-50"></i>
                        </a>
                    <?php endforeach; ?>

                    <?php if ($userRole === 'admin'): ?>
                        <span class="nav-group-title">الإدارة</span>
                        <?php foreach ($adminItems as $item): ?>
                            <a class="sidebar-link <?php echo $activeClass($item['href'], $currentPath); ?>" href="<?php echo $item['href']; ?>">
                                <span class="sidebar-link-start">
                                    <i class="fas <?php echo $item['icon']; ?>"></i>
                                    <span><?php echo $item['label']; ?></span>
                                </span>
                                <i class="fas fa-chevron-left small opacity-50"></i>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </nav>

                <div class="sidebar-user">
                    <div class="user-meta mb-3">
                        <span class="user-avatar"><i class="fas fa-user"></i></span>
                        <div>
                            <p class="user-name"><?php echo $userName; ?></p>
                            <p class="user-role"><?php echo htmlspecialchars($roleLabels[$userRole] ?? $userRole); ?></p>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="/logout" class="btn btn-danger"><i class="fas fa-arrow-right-from-bracket ms-2"></i>تسجيل الخروج</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="sidebar-user">
                    <p class="user-role mb-3">الوصول الآمن لمنسوبي المنشأة الطبية.</p>
                    <a href="/login" class="btn btn-primary w-100">تسجيل الدخول</a>
                </div>
            <?php endif; ?>
        </aside>

        <div class="app-main">
            <header class="app-header glass-panel">
                <div class="header-actions">
                    <button class="btn btn-ghost mobile-toggle" id="mobileSidebarToggle" type="button" aria-label="فتح القائمة">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <span class="header-chip"><i class="fas fa-sparkles"></i>واجهة تشغيل حديثة</span>
                    </div>
                </div>

                <?php if (!Application::isGuest()): ?>
                    <div class="header-user">
                        <span class="header-chip"><i class="fas fa-shield-heart"></i>جاهزية تشغيلية عالية</span>
                        <span class="header-chip"><i class="fas fa-user-circle"></i><?php echo $userName; ?></span>
                    </div>
                <?php else: ?>
                    <div class="header-user">
                        <a href="/login" class="btn btn-primary">تسجيل الدخول</a>
                    </div>
                <?php endif; ?>
            </header>

            <main class="page-content container-fluid">
                <div class="page-shell">
                    {{content}}
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const shell = document.getElementById('appShell');
            const toggle = document.getElementById('mobileSidebarToggle');
            const closeBtn = document.getElementById('mobileSidebarClose');
            const overlay = document.getElementById('mobileOverlay');

            if (!shell) return;

            const closeSidebar = () => shell.classList.remove('sidebar-open');
            const openSidebar = () => shell.classList.add('sidebar-open');

            toggle && toggle.addEventListener('click', openSidebar);
            closeBtn && closeBtn.addEventListener('click', closeSidebar);
            overlay && overlay.addEventListener('click', closeSidebar);
            window.addEventListener('resize', () => {
                if (window.innerWidth > 991) closeSidebar();
            });
        })();

        <?php if (Application::$app->session->getFlash('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'نجاح العملية',
            text: '<?php echo addslashes(Application::$app->session->getFlash("success")); ?>',
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#3B82F6',
            background: '#0f172a',
            color: '#E2E8F0'
        });
        <?php endif; ?>

        <?php if (Application::$app->session->getFlash('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'تنبيه',
            text: '<?php echo addslashes(Application::$app->session->getFlash("error")); ?>',
            confirmButtonText: 'إغلاق',
            confirmButtonColor: '#EF4444',
            background: '#0f172a',
            color: '#E2E8F0'
        });
        <?php endif; ?>
    </script>
</body>
</html>
