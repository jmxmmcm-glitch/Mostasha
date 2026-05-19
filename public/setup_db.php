<?php
/**
 * Setup script: imports database.sql into the configured PostgreSQL database.
 *
 * On Render, after the first deploy, visit:
 *     https://<your-service>.onrender.com/setup_db.php
 * to provision the schema. This script is idempotent.
 */

// Bootstrap the application's autoloader & DB config
define('ROOT_DIR', dirname(__DIR__));

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base   = ROOT_DIR . '/app/';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;
    $rel = substr($class, strlen($prefix));
    $file = $base . str_replace('\\', '/', $rel) . '.php';
    if (file_exists($file)) require $file;
});

header('Content-Type: text/html; charset=utf-8');
echo "<!doctype html><html dir='rtl' lang='ar'><meta charset='utf-8'>";
echo "<title>إعداد قاعدة البيانات</title>";
echo "<body style='font-family:sans-serif;max-width:800px;margin:40px auto;padding:20px;line-height:1.8'>";
echo "<h2>⚙️ إعداد قاعدة البيانات تلقائياً...</h2>";

try {
    $config = require ROOT_DIR . '/app/Config/database.php';
    $db = new App\Core\Database($config);
    echo "<p>✅ تم الاتصال بـ PostgreSQL بنجاح.</p>";
} catch (Throwable $e) {
    die("<p style='color:red'>❌ فشل الاتصال: " . htmlspecialchars($e->getMessage()) . "</p>");
}

$sqlFile = ROOT_DIR . '/database.sql';
if (!file_exists($sqlFile)) {
    die("<p style='color:red'>❌ لم يتم العثور على ملف database.sql.</p>");
}

$sql = file_get_contents($sqlFile);
$db->multi_query($sql);

echo "<h3 style='color:green'>🎉 تم استيراد الجداول والبيانات بنجاح!</h3>";
echo "<p>بيانات الدخول الافتراضية: <code>admin / password</code></p>";
echo "<p><a href='/'>🏥 العودة إلى النظام وتسجيل الدخول</a></p>";
echo "</body></html>";
