# نظام إدارة مكتب المحاماة — Laravel 12

هذا الأرشيف يحتوي البنية الخلفية والواجهات المطلوبة، مبنية بالكامل على مخطط قاعدة البيانات (ERD) الذي اعتمدته (الصورة، ~22 جدول)، وليس على المواصفة النصية الأصلية.

## 1. الفروقات المتعمدة عن المخطط الأصلي (ولماذا)

| في المخطط | ماذا فعلت ولماذا |
|---|---|
| جدول باسم `sessions` | أنشأته فعليًا باسم `case_sessions` (النموذج `CaseSession`) لتفادي التصادم مع جدول الجلسات الداخلي في Laravel نفسه (`SESSION_DRIVER=database`) — وهو بالضبط نفس السبب الذي دفع النسخة النصية الأصلية لتسمية جدولها `legal_sessions`. |
| جدول `cases` | الكيان (Model) باسم `CourtCase` وليس `Case`، لأن `case` كلمة محجوزة في PHP 8.1+ (تُستخدم داخل الـ enums) ولا يمكن استخدامها كاسم كلاس. |
| أعمدة المفاتيح الأجنبية مثل `users_id`, `clients_id`, `cases_id` | هذه ليست تسمية Laravel الافتراضية (`user_id`)، لذا تم تحديد كل علاقة يدويًا بـ foreignKey صريح في كل Model. |
| `attachments.related_type` | مُطبّق كـ polymorphic مخصص (`related_id` + `related_type` enum: case/contract/user) بدل تسمية Laravel القياسية، مع `morphMap` صريح في الموديل. |
| علاقة `contracts` بالمدفوعات | لا يوجد عمود `cases_id` أو `contracts_id` مباشر في جدول `financial_records` يربطها بالعقد تحديدًا (المخطط يربط `financial_records` بـ `cases_id`/`clients_id` فقط)، لذلك دفعات العقد تُحسب عبر `clients_id` المشترك بين العقد والموكل. إن كان لديك عمود `contracts_id` فعلي في قاعدة بياناتك الحقيقية، أخبرني لأصحّح العلاقة مباشرة. |

## 2. ما تم بناؤه فعليًا

- **Migrations كاملة** لجميع الجداول الـ 22 بالترتيب الصحيح لتفادي أخطاء المفاتيح الأجنبية.
- **Eloquent Models** لكل جدول مع `$table`, `$fillable`, casts, وكل العلاقات (`belongsTo`, `hasMany`, `belongsToMany`, `morphMany`, `morphTo`).
- **Factories + Seeders** لكل الجداول الرئيسية، وملف `DatabaseSeeder.php` ينفذها بالترتيب الصحيح.
- **Controllers** كاملة (Resource Controllers) لكل الكيانات الرئيسية مع Validation و Eager Loading و إعادة التوجيه.
- **واجهات Blade كاملة لكل كيان يمكن أن يملك واجهة CRUD** (43 ملف Blade إجمالًا):

  | الكيان | index | create | edit | show | ملاحظة |
  |---|:---:|:---:|:---:|:---:|---|
  | القضايا `cases` | ✅ | ✅ | ✅ | ✅ | index بـ badges ملوّنة، show بلوحة منقسمة + مدير مستندات بالسحب والإفلات |
  | العقود `contracts` | ✅ | ✅ | ✅ | ✅ | show بسجل مالي كامل (مدفوع/متبقي) + نموذج تسجيل إيصال فوري |
  | المحاكم `courts` | ✅ | ✅ | ✅ | ✅ | |
  | التصنيفات `categories` | ✅ | ✅ | ✅ | — | لا حاجة لصفحة عرض منفصلة |
  | الموكلون `clients` | ✅ | — | ✅ | ✅ | يُنشأ الموكل تلقائيًا من صفحة المستخدمين، لا يوجد إنشاء مباشر |
  | المستخدمون `users` | ✅ | ✅ | ✅ | ✅ | مع اختيار الأدوار (roles) |
  | الأدوار `roles` | ✅ | ✅ | ✅ | — | مع اختيار الصلاحيات (checkboxes) |
  | الصلاحيات `permissions` | ✅ | نموذج مضمّن | — | — | مجمّعة حسب الوحدة (module) |
  | الحجوزات `bookings` | ✅ | ✅ | ✅ | ✅ | |
  | المواعيد `appointments` | ✅ | ✅ | ✅ | ✅ | |
  | المقالات `articles` | ✅ | ✅ | ✅ | ✅ | |
  | الشركات المميزة `featured-companies` | ✅ | نموذج مضمّن | — | — | |
  | السوابق القضائية `legal-precedents` | ✅ | نموذج مضمّن | — | — | |
  | رسائل التواصل `contact-channels` | ✅ | — | تحديث الحالة inline | — | تُستقبل من الموقع العام، لا تُنشأ من لوحة التحكم |
  | تقييمات الخدمة `service-ratings` | ✅ | نموذج مضمّن | — | — | |
  | السجلات المالية `financial-records` | ✅ | نموذج مضمّن | — | — | عرض عام لكل الحركات، إضافة إلى ذلك النموذج المضمّن في صفحة كل عقد |

  "نموذج مضمّن" تعني: بدل صفحة create منفصلة، يوجد نموذج إضافة مباشر بجانب الجدول في نفس صفحة index (نفس أسلوب لوحات التحكم الخفيفة لهذه الكيانات الثانوية).

- تخطيط رئيسي `cms/parent.blade.php` يستخدم الآن **حزمة AdminLTE v4 الرسمية فعليًا** (وليس تقليدًا بـ Bootstrap فقط كما في النسخة السابقة من هذا الأرشيف):
  - AdminLTE v4 مبني على Bootstrap 5.3 مدمج داخل نفس ملف الـ CSS (لا حاجة لرابط Bootstrap منفصل).
  - يُحمَّل بناء الـ **RTL الرسمي** `adminlte.rtl.min.css` (وليس LTR)، مع `dir="rtl"` على `<html>`، حسب توثيق AdminLTE الرسمي لدعم RTL.
  - الإصدارات المثبّتة (CDN عبر jsDelivr، مطابقة لصفحة "Getting Started" الرسمية وقت الكتابة): `admin-lte@4.9.1`, `bootstrap@5.3.8`, `bootstrap-icons@1.13.1`, `overlayscrollbars@2.11.0`, `@popperjs/core@2.11.8`.
  - بنية HTML تتبع تسمية الأصناف الرسمية لإصدار v4 بالضبط (`app-wrapper`, `app-header`, `app-sidebar`, `app-main`, `app-content`, `sidebar-brand`, `sidebar-menu`) — وهي مختلفة عن أصناف AdminLTE v3 القديمة (`main-header`, `main-sidebar`, `content-wrapper`...) إن كنت تقارن بأمثلة أقدم على الإنترنت.
  - أيقونات القائمة الجانبية لا تزال Bootstrap Icons (`bi bi-*`) — متوافقة تمامًا مع AdminLTE v4 نفسه بدون أي تغيير.
  - الرابط النشط في القائمة الجانبية يتم تمييزه تلقائيًا حسب الصفحة الحالية عبر `request()->routeIs(...)`.

تمتد كل الواجهات من هذا التخطيط عبر `@extends('cms.parent')` و `@section('content')`.
- ملف `routes/web.php` بالكامل يستخدم `Route::resource` فقط (بدون أي JSON/API endpoints)، حسب الشرط المطلوب.

⚠️ **ملاحظة عن الشبكة**: التخطيط يعتمد على CDN (jsDelivr) لتحميل AdminLTE وBootstrap، لذا يحتاج الخادم اتصالًا بالإنترنت عند فتح الصفحة في المتصفح. إن كان مشروعك يعمل بدون إنترنت (بيئة معزولة/Intranet)، أخبرني لأجهّز نسخة تُحمَّل فيها هذه الملفات محليًا عبر `npm`/`composer` بدل الـ CDN.

## 3. خطوات التشغيل

1. أنشئ مشروع Laravel 12 جديد:
   ```bash
   composer create-project laravel/laravel lawyer-office
   ```
2. انسخ محتويات هذا الأرشيف (`app/`, `database/`, `resources/views/cms/`, `routes/web.php`) فوق المشروع الجديد.
3. انسخ `.env.example` الموجود هنا إلى `.env` مشروعك، وعدّل بيانات قاعدة البيانات (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
   - إن كنت على Windows/XAMPP، أنشئ المجلد `C:\xampp\htdocs\lawyer-office\blade_cache` يدويًا قبل التشغيل لتفعيل حل مشكلة "Access is Denied".
4. ولّد مفتاح التطبيق:
   ```bash
   php artisan key:generate
   ```
5. أنشئ رابط التخزين العام (لازم لرفع المرفقات):
   ```bash
   php artisan storage:link
   ```
6. شغّل الهجرة والبذور دفعة واحدة:
   ```bash
   php artisan migrate:fresh --seed
   ```
7. شغّل الخادم:
   ```bash
   php artisan serve
   ```
8. افتح `http://localhost:8000` — بيانات دخول المدير الافتراضية بعد البذر:
   - البريد: `admin@lawfirm.test`
   - كلمة المرور: `password`

## 4. بنية الملفات في هذا الأرشيف

```
lawyer-office/
├── .env.example
├── routes/web.php
├── app/
│   ├── Models/            (20 موديل)
│   └── Http/Controllers/  (18 كونترولر + Controller الأساسي)
├── database/
│   ├── migrations/        (22 هجرة، مرتبة حسب الاعتمادية)
│   ├── factories/         (16 مصنع)
│   └── seeders/           (6 seeders + DatabaseSeeder)
└── resources/views/cms/
    ├── parent.blade.php   (التخطيط الرئيسي)
    ├── dashboard.blade.php
    └── <15 مجلد كيان>/    (43 ملف Blade، انظر الجدول أعلاه)
```
