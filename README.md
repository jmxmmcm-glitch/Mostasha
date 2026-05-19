# نظام إدارة الطوارئ الطبية 🏥 (Emergency Medical System / Mostasha)

نظام إلكتروني متكامل لإدارة أقسام الطوارئ في المستشفيات والمراكز الطبية. مبني بـ **PHP 8 (Pure MVC)** و **PostgreSQL**، ومعدّ للاستضافة على **Render** عبر Docker.

## 🌟 الميزات الرئيسية

- **نظام حماية وصلاحيات (RBAC):** أدوار (مدير، طبيب، ممرض، استقبال).
- **إدارة المرضى والزيارات.**
- **الفرز الطبي (Triage)** للتمريض.
- **الفحص الطبي والوصفات** للأطباء.
- **لوحة تحكم تفاعلية** للحالات النشطة والأسرّة المتاحة.
- **السجل الطبي التاريخي (Timeline)** للمريض.
- **واجهة عربية RTL** كاملة بـ Bootstrap 5.

## 🛠️ التقنيات

- **Backend:** PHP 8.2 (MVC, No framework) — PDO
- **Database:** PostgreSQL 16+ (سابقاً MySQL — تم التحويل لاستضافة Render)
- **Web Server:** Apache 2 (داخل Docker)
- **Frontend:** HTML5, Bootstrap 5 RTL, FontAwesome, SweetAlert2.

## 🚀 النشر على Render

تم نشر المشروع على Render كـ Web Service من Dockerfile مع قاعدة PostgreSQL مُدارة.

### الإعداد بعد أول نشر
1. افتح خدمة الويب على Render وتأكد أن متغير `DATABASE_URL` مرتبط بقاعدة PostgreSQL (يُحقن تلقائياً).
2. زر مرة واحدة:
   ```
   https://<your-service>.onrender.com/setup_db.php
   ```
   هذا ينفّذ `database.sql` على القاعدة وينشئ الجداول والمستخدم الإداري الافتراضي.
3. سجّل الدخول:
   - اسم المستخدم: `admin`
   - كلمة المرور: `password`

### متغيرات البيئة المطلوبة
| المتغير | الوصف |
|--|--|
| `DATABASE_URL` | رابط PostgreSQL كامل — يُحقن من Render تلقائياً |
| `PORT` | منفذ الاستماع — يُحقن من Render تلقائياً |

كبديل لـ `DATABASE_URL` يمكن استخدام `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`.

## 💻 التشغيل المحلي

```bash
# مع PostgreSQL محلي
export DATABASE_URL="postgresql://postgres:postgres@localhost:5432/emc_db"
php -S localhost:8000 -t public

# ثم زر http://localhost:8000/setup_db.php مرة واحدة
```

أو بـ Docker:
```bash
docker build -t mostasha .
docker run -e DATABASE_URL="..." -p 8000:10000 mostasha
```

## 📁 هيكلية المشروع
- `/app/Controllers` — منطق التطبيق
- `/app/Models`      — التعامل مع القاعدة
- `/app/Views`       — واجهات العرض
- `/app/Core`        — Router, Database (PDO/pgsql مع facade متوافق مع mysqli), Session…
- `/public`          — `index.php` ونقطة الدخول + `setup_db.php`
- `Dockerfile`       — صورة Apache+PHP+pdo_pgsql جاهزة لـ Render
- `database.sql`     — مخطط الجداول بصياغة PostgreSQL

---
*تم بناء هذا المشروع ليكون أساساً قوياً وقابلاً للتوسع لإدارة المراكز الطبية.*
