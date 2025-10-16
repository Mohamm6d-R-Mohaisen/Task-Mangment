# موديول السلايدر (Slider Module)

## نظرة عامة
تم إنشاء موديول السلايدر بالكامل يتبع نفس النمط المستخدم في المشروع. يتضمن الموديول جميع المكونات المطلوبة لإدارة السلايدرات.

## المكونات المنشأة

### 1. المودل (Model)
- **الملف**: `app/Models/Slider.php`
- **الميزات**:
  - استخدام `SaveImageTrait` للتعامل مع الصور
  - حقول قابلة للتعديل: `title`, `sub_title_desc`, `image`, `status`
  - Scope للاستعلام عن السلايدرات المفعلة
  - Accessor للحصول على رابط الصورة

### 2. الهجرة (Migration)
- **الملف**: `database/migrations/2025_01_27_000000_create_sliders_table.php`
- **الأعمدة**:
  - `id` - المعرف الفريد
  - `title` - عنوان السلايدر
  - `sub_title_desc` - وصف السلايدر
  - `image` - مسار الصورة
  - `status` - حالة التفعيل
  - `timestamps` - أوقات الإنشاء والتحديث

### 3. الكنترولر (Controller)
- **الملف**: `app/Http/Controllers/Admin/SliderController.php`
- **الوظائف**:
  - `index()` - عرض قائمة السلايدرات
  - `getData()` - الحصول على بيانات DataTables
  - `create()` - عرض نموذج الإضافة
  - `store()` - حفظ السلايدر الجديد
  - `edit()` - عرض نموذج التعديل
  - `update()` - تحديث السلايدر
  - `destroy()` - حذف السلايدر
  - `toggleStatus()` - تبديل حالة التفعيل

### 4. Resource
- **الملف**: `app/Http/Resources/Admin/SliderResource.php`
- **الاستخدام**: لتحويل البيانات في DataTables

### 5. Request Classes
- **StoreSliderRequest**: للتحقق من صحة بيانات الإضافة
- **UpdateSliderRequest**: للتحقق من صحة بيانات التحديث

### 6. Factory & Seeder
- **SliderFactory**: لإنشاء بيانات تجريبية
- **SliderSeeder**: لزرع البيانات التجريبية

### 7. Routes
تم إضافة المسارات التالية في `routes/web.php`:
```php
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('sliders', \App\Http\Controllers\Admin\SliderController::class);
    Route::post('sliders/{slider}/toggle-status', [\App\Http\Controllers\Admin\SliderController::class, 'toggleStatus'])->name('sliders.toggle-status');
    Route::get('sliders-data', [\App\Http\Controllers\Admin\SliderController::class, 'getData'])->name('sliders.data');
});
```

### 8. Views
- **index.blade.php**: صفحة عرض قائمة السلايدرات
- **create.blade.php**: صفحة إضافة سلايدر جديد
- **edit.blade.php**: صفحة تعديل السلايدر
- **sub/operations.blade.php**: أزرار العمليات
- **sub/status.blade.php**: عرض حالة التفعيل
- **sub/image.blade.php**: عرض الصورة المصغرة

### 9. API Resources
- **SliderResource**: للاستخدام في API
- **Api/SliderController**: للوصول عبر API

## كيفية الاستخدام

### 1. تشغيل الهجرة
```bash
php artisan migrate
```

### 2. إنشاء بيانات تجريبية (اختياري)
```bash
php artisan db:seed --class=SliderSeeder
```

### 3. الوصول للوحة الإدارة
- انتقل إلى `/admin/sliders` لإدارة السلايدرات
- يمكنك إضافة، تعديل، حذف، وتفعيل/إلغاء تفعيل السلايدرات

### 4. الوصول عبر API
```bash
GET /api/sliders     # قائمة السلايدرات المفعلة
GET /api/sliders/{id} # سلايدر محدد
```

## الميزات

1. **إدارة كاملة**: إضافة، تعديل، حذف، تفعيل/إلغاء تفعيل
2. **معالجة الصور**: رفع، حذف، معاينة الصور
3. **DataTables**: عرض البيانات مع البحث والترتيب
4. **التحقق من صحة البيانات**: رسائل خطأ باللغة العربية
5. **API جاهز**: للاستخدام في التطبيقات الخارجية
6. **واجهة مستخدم عربية**: تصميم متجاوب ومريح

## ملاحظات مهمة

1. تأكد من وجود مجلد `public/uploads/store/sliders` لحفظ الصور
2. تأكد من تثبيت حزمة DataTables إذا لم تكن مثبتة
3. تأكد من وجود middleware `role:admin` للوصول للوحة الإدارة
4. يمكن تخصيص الرسائل والتصميم حسب احتياجات المشروع 