# 💡 *نظرة عامة على إضافة GRE Real Estate*

*الاسم الفني*: gre-real-estate  
*الوصف*:  
نظام متكامل مبني على WordPress لإدارة الأبراج السكنية (العمائر)، النماذج المعمارية، والشقق داخل الأبراج، مع دعم كامل للوراثة التلقائية للخصائص، التوليد الآلي للشقق، وواجهة عرض احترافية للزوار.

---

## 🧱 *مكونات الإضافة الأساسية*

### 1. ✅ *أنواع المنشورات المخصصة (Custom Post Types)*

| النوع        | التسمية | الوصف |
|--------------|---------|-------|
| gre_tower  | برج     | يمثل المبنى السكني (5–10 أدوار)، ويتضمن خصائص عامة للبرج مثل الموقع، عدد الأدوار، مواقف السيارات، إلخ. |
| gre_model  | نموذج   | يمثل تصميم الشقة المتكرر في نفس الموقع بكل دور، يتضمن عدد الغرف، الحمامات، المساحة، الصور، مستوى التشطيب... |
| gre_apartment | شقة | الشقة نفسها، وتتولد تلقائيًا عند إنشاء النماذج، مع معرف فريد وحالة مستقلة (جاهزة، قيد التشطيب، مباعة...). |

---

## 🔁 *مبدأ الوراثة*

- كل شقة ترث خصائصها من:
  - البرج الذي تتبع له (مثل الموقع، عدد الأدوار)
  - النموذج الذي تنتمي له (مثل عدد الغرف، المساحة، الصور)

- ولكن تمتلك خصائص مستقلة:
  - *معرف الشقة*: يتم توليده تلقائيًا بصيغة:  
    {اختصار البرج}-{اسم النموذج}-{رقم الدور}  
    مثال: TWR1-A1-3

  - *حالة الشقة*: (جاهزة، مباعة، تحت التشطيب... ويمكن تغييرها يدوياً)

---

## ⚙ *الوظائف الآلية*

- عند إنشاء برج وتحديد عدد الأدوار والنماذج:
  - يتم *توليد الشقق تلقائيًا* بعدد = (عدد الأدوار × عدد النماذج)
  - كل شقة يتم ربطها تلقائيًا بالنموذج والبرج
  - خصائص الشقق تتحدث تلقائيًا عند تعديل بيانات النموذج أو البرج

---

## 🧩 *الملفات الأساسية في الإضافة*

### 🔷 gre-real-estate.php
- نقطة دخول الإضافة
- تسجيل أنواع المحتوى
- تحميل ملفات includes/ و admin/

### 🔷 includes/
- يحتوي على الكلاسات المسؤولة عن تعريف الأنواع:
  - GRE_Tower, GRE_Model, GRE_Apartment
- دوال المساعدة مثل:
  - توليد المعرفات
  - التحقق من الوراثة
  - تنسيق البيانات للعرض

### 🔷 admin/
- شاشات تحرير البيانات الخاصة بكل نوع
- صناديق الميتا المخصصة (Meta Boxes)
- تحميل خرائط Leaflet بدون Google API
- تسجيل القوائم الجانبية في لوحة تحكم ووردبريس

### 🔷 public/
- ملفات العرض للزوار:
  - archive-gre_apartment.php, archive-gre_model.php
  - single-gre_apartment.php, single-gre_model.php, single-gre_tower.php
- ملفات التنسيق CSS
- دوال العرض مثل gre_render_entity_details() وغيرها

---

## 🌍 *الخرائط ودعم الموقع الجغرافي*

- تستخدم مكتبة *Leaflet* مفتوحة المصدر (بدون Google API Key)
- الموقع الجغرافي لكل برج يُحدد عبر خطوط الطول والعرض
- يمكن لاحقًا تفعيل Google Maps بسهولة من خلال الكود المعطل حاليًا

---

## 🎯 *واجهة العرض العامة (Front-End)*

- تم بناء قوالب مخصصة لعرض:
  - قائمة الأبراج
  - تفاصيل برج معين
  - قائمة النماذج
  - تفاصيل النموذج
  - قائمة الشقق (مع فلترة ذكية)
  - تفاصيل الشقة

- كل صفحة تُعرض باستخدام تصميم موحد يرث من القالب العام ويعتمد على تنسيقات CSS مخصصة داخل gre-entity-single.css

---

## 🛠 *حالة المشروع الحالية*

- تم تنفيذ جميع أنواع المحتوى وربطها ببعضها.
- تم تفعيل خاصية التوليد التلقائي للشقق.
- تم تجهيز صفحة الأرشيف لكل نوع + صفحات التفاصيل.
- يتم العمل حاليًا على:
  - *توفير واجهة ذكية للفلترة والبحث*
  - *توثيق كافة المتغيرات لاستخدامها في القوالب*
  - *تحسين واجهات الإدارة (لوحة التحكم)* لتكون أكثر احترافية وسلاسة.

---

## 🔐 *ملاحظات للمطور الجديد*

- الإضافة مكتوبة بـ *PHP + HTML + CSS + JS (بسيط)* ومتكاملة مع هيكل ووردبريس القياسي.
- تعتمد على CPTs وحقول مخصصة وليس على جداول مخصصة في قاعدة البيانات.
- التصميم قابل للتوسعة مستقبلًا ليشمل:
  - حجوزات الشقق
  - ربط مع WooCommerce
  - استعراض الثلاثي الأبعاد للمخططات

---

My Plugin File list:

\* gre-real-estate.php  
 \* admin/admin-menus.php  
 \* admin/apartment-meta-boxes.php  
 \* admin/model-meta-boxes.php  
 \* admin/tower-meta-boxes.php  
 \* includes/class-apartment.php  
 \* includes/class-init-data.php  
 \* includes/class-loader.php  
 \* includes/class-model.php  
 \* includes/class-tower.php  
 \* public/public-functions.php  
 \* public/assets/css/gre-entity-single.css  
 \* public/templates/archive-gre\_apartment.php  
 \* public/templates/archive-gre\_model.php  
 \* public/templates/archive-gre\_tower.php  
 \* public/templates/page-gre\_search.php  
 \* public/templates/single-gre\_apartment.php  
 \* public/templates/single-gre\_model.php  
 \* public/templates/single-gre\_tower.php

these is the content of each file :  

gre-real-estate.php this file contains:  
\<?php  
/\*\*  
 \* Plugin Name: GRE Real Estate  
 \* Description: إدارة الأبراج والنماذج والشقق السكنية.  
 \*/
// Autoload classes  
require\_once plugin\_dir\_path(\_\_FILE\_\_) . 'includes/class-loader.php';  
// Init Post Types  
new GRE\_Tower();  
new GRE\_Model();  
new GRE\_Apartment();  
// Admin menus  
require\_once plugin\_dir\_path(\_\_FILE\_\_) . 'admin/admin-menus.php';  
?\>  
   
admin/admin-menus.php this file contains:  
<?php  
// قوائم لوحة التحكم وإعدادات لوحة الإدارة
// تحميل مكتبة OpenStreetMap (Leaflet)  
function gre\_enqueue\_leaflet\_scripts($hook) {  
    $screen \= get\_current\_screen();  
    if ($screen && $screen-\>post\_type \=== 'gre\_tower') {  
        wp\_enqueue\_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');  
        wp\_enqueue\_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');  
    }  
}  
add\_action('admin\_enqueue\_scripts', 'gre\_enqueue\_leaflet\_scripts');
// إعداد قوائم مخصصة للإضافة  
function gre\_add\_admin\_menus() {  
    // مستقبلاً يمكننا إضافة صفحات فرعية مخصصة هنا  
    // add\_submenu\_page(...);  
}  
add\_action('admin\_menu', 'gre\_add\_admin\_menus'); ?\>






admin/apartment-meta-boxes.php this file contains: 

<?php  
add\_action('add\_meta\_boxes', 'gre\_add\_apartment\_meta\_boxes');  
add\_action('save\_post', 'gre\_save\_apartment\_meta');

// إزالة محرر النصوص من نوع الشقة  
add\_action('init', function () {  
    remove\_post\_type\_support('gre\_apartment', 'editor');  
}, 100);

function gre\_add\_apartment\_meta\_boxes() {  
    add\_meta\_box('gre\_apartment\_details', 'تفاصيل الشقة', 'gre\_render\_apartment\_meta\_box', 'gre\_apartment', 'normal', 'high');  
}

function gre\_get\_apartment\_meta($post\_id, $key, $default \= '') {  
    return get\_post\_meta($post\_id, "\_gre\_apartment\_$key", true) ?: $default;  
}

function gre\_render\_apartment\_meta\_box($post) {  
    wp\_nonce\_field('gre\_save\_apartment\_meta', 'gre\_apartment\_meta\_nonce');

    $meta\_keys \= \[  
        'apartment\_code' \=\> '',  
        'apartment\_number' \=\> '',  
        'floor\_number' \=\> '',  
        'model\_id' \=\> '',  
        'tower\_id' \=\> '',  
        'status' \=\> '',  
        'custom\_price\_usd' \=\> '',  
        'custom\_finishing\_level' \=\> '',  
        'custom\_finishing\_type' \=\> '',  
        'custom\_images' \=\> ''  
    \];

    foreach ($meta\_keys as $key \=\> $default) {  
        $meta\_keys\[$key\] \= gre\_get\_apartment\_meta($post-\>ID, $key, $default);  
    }

    echo '\<style\>  
        .gre-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }  
        .gre-full { grid-column: span 2; }  
        label { font-weight: bold; display: block; margin-bottom: 5px; }  
        input, select, textarea { width: 100%; }  
    \</style\>';

    echo '\<div class="gre-grid"\>';

    // عرض الحقول readonly  
    $readonly\_fields \= \[  
        'apartment\_code' \=\> 'معرف الشقة',  
        'apartment\_number' \=\> 'رقم الشقة',  
        'floor\_number' \=\> 'الدور'  
    \];  
    foreach ($readonly\_fields as $key \=\> $label) {  
        echo "\<div\>\<label\>$label\</label\>\<input type='text' name='gre\_apartment\_$key' value='" . esc\_attr($meta\_keys\[$key\]) . "' readonly /\>\</div\>";  
    }

    // عرض معلومات النموذج والبرج  
    gre\_render\_model\_tower\_info($meta\_keys\['model\_id'\]);

    // حالة الشقة  
    $status\_options \= \[  
        'available' \=\> 'متاحة',  
        'sold' \=\> 'مباعة',  
        'under\_preparation' \=\> 'قيد التجهيز',  
        'for\_finishing' \=\> 'تحتاج تشطيب'  
    \];  
    echo '\<div\>\<label\>حالة الشقة\</label\>\<select name="gre\_apartment\_status"\>';  
    foreach ($status\_options as $value \=\> $label) {  
        echo "\<option value='$value'" . selected($meta\_keys\['status'\], $value, false) . "\>$label\</option\>";  
    }  
    echo '\</select\>\</div\>';

    // السعر المخصص  
    echo '\<div\>\<label\>سعر مخصص (اختياري)\</label\>\<input type="text" name="gre\_apartment\_custom\_price\_usd" value="' . esc\_attr($meta\_keys\['custom\_price\_usd'\]) . '" /\>\</div\>';

    // مستوى ونوع التشطيب  
    $select\_fields \= \[  
        'custom\_finishing\_level' \=\> \[  
            '' \=\> 'وراثة من النموذج',  
            'ready' \=\> 'مشطب',  
            'semi' \=\> 'نصف تشطيب',  
            'none' \=\> 'بدون تشطيب'  
        \],  
        'custom\_finishing\_type' \=\> \[  
            '' \=\> 'وراثة من النموذج',  
            'luxury' \=\> 'فاخر',  
            'deluxe' \=\> 'ديلوكس',  
            'normal' \=\> 'عادي'  
        \]  
    \];  
    foreach ($select\_fields as $key \=\> $options) {  
        $label \= $key \=== 'custom\_finishing\_level' ? 'مستوى التشطيب (اختياري)' : 'نوع التشطيب (اختياري)';  
        echo "\<div\>\<label\>$label\</label\>\<select name='gre\_apartment\_$key'\>";  
        foreach ($options as $value \=\> $label\_option) {  
            echo "\<option value='$value'" . selected($meta\_keys\[$key\], $value, false) . "\>$label\_option\</option\>";  
        }  
        echo '\</select\>\</div\>';  
    }

    // الصور المخصصة  
    echo '\<div class="gre-full"\>\<label\>صور مخصصة للشقة (روابط أو IDs)\</label\>\<textarea name="gre\_apartment\_custom\_images"\>' . esc\_textarea($meta\_keys\['custom\_images'\]) . '\</textarea\>\</div\>';

    echo '\</div\>'; // نهاية .gre-grid  
}

function gre\_render\_model\_tower\_info($model\_id) {  
    $model\_title \= $model\_id ? get\_the\_title($model\_id) : '—';  
    $tower\_id \= $model\_id ? get\_post\_meta($model\_id, '\_gre\_model\_tower\_id', true) : '';  
    $tower\_title \= $tower\_id ? get\_the\_title($tower\_id) : '—';

    echo '\<div\>\<label\>النموذج التابع\</label\>\<input type="text" value="' . esc\_attr($model\_title) . '" readonly /\>\</div\>';  
    echo '\<div\>\<label\>البرج التابع\</label\>\<input type="text" value="' . esc\_attr($tower\_title) . '" readonly /\>\</div\>';  
}

function gre\_save\_apartment\_meta($post\_id) {  
    if (  
        \!isset($\_POST\['gre\_apartment\_meta\_nonce'\]) ||  
        \!wp\_verify\_nonce($\_POST\['gre\_apartment\_meta\_nonce'\], 'gre\_save\_apartment\_meta') ||  
        defined('DOING\_AUTOSAVE') && DOING\_AUTOSAVE ||  
        \!current\_user\_can('edit\_post', $post\_id)  
    ) return;

    $fields \= \[  
        'apartment\_code','apartment\_number','floor\_number','model\_id','tower\_id','status',  
        'custom\_price\_usd','custom\_finishing\_level','custom\_finishing\_type','custom\_images'  
    \];

    foreach ($fields as $field) {  
        $value \= sanitize\_text\_field($\_POST\["gre\_apartment\_$field"\] ?? '');  
        update\_post\_meta($post\_id, "\_gre\_apartment\_$field", $value);  
    }  
}  
?\>

   
admin/model-meta-boxes.php this file contains:  

<?php  
add\_action('add\_meta\_boxes', 'gre\_add\_model\_meta\_boxes');  
add\_action('save\_post', 'gre\_save\_model\_meta', 10, 2);  
add\_filter('wp\_insert\_post\_data', 'gre\_set\_model\_post\_title', 10, 2);

add\_action('wp\_ajax\_gre\_generate\_model\_short\_name', function () {  
    $tower\_id \= intval($\_GET\['tower\_id'\] ?? 0);  
    $short\_name \= gre\_generate\_model\_short\_name($tower\_id);  
    if ($short\_name) {  
        wp\_send\_json\_success(\['short\_name' \=\> $short\_name\]);  
    } else {  
        wp\_send\_json\_error(\['message' \=\> 'Failed to generate short name'\]);  
    }  
});

function gre\_generate\_model\_short\_name(int $tower\_id): ?string {  
    if (\!$tower\_id) return null;

    $tower\_short \= get\_post\_meta($tower\_id, '\_gre\_tower\_short\_name', true);  
    if (\!$tower\_short) return null;

    $model\_count \= count(get\_posts(\[  
        'post\_type' \=\> 'gre\_model',  
        'post\_status' \=\> 'any',  
        'meta\_query' \=\> \[\['key' \=\> '\_gre\_model\_tower\_id', 'value' \=\> $tower\_id\]\],  
    \])) \+ 1;

    return "{$tower\_short}-M{$model\_count}";  
}

function gre\_set\_model\_post\_title(array $data, array $postarr): array {  
    if ($data\['post\_type'\] \=== 'gre\_model' && (empty(trim($data\['post\_title'\])) || $data\['post\_title'\] \=== 'إضافة عنوان')) {  
        $tower\_id \= $\_POST\['gre\_model\_tower\_id'\] ?? get\_post\_meta($postarr\['ID'\] ?? 0, '\_gre\_model\_tower\_id', true);  
        if ($tower\_id) {  
            $tower\_title \= get\_the\_title($tower\_id);  
            $model\_count \= count(get\_posts(\[  
                'post\_type' \=\> 'gre\_model',  
                'post\_status' \=\> 'any',  
                'meta\_query' \=\> \[\['key' \=\> '\_gre\_model\_tower\_id', 'value' \=\> $tower\_id\]\],  
            \])) \+ 1;  
            $data\['post\_title'\] \= "نموذج {$model\_count} \- {$tower\_title}";  
        }  
    }  
    return $data;  
}
function gre\_add\_model\_meta\_boxes() {  
    add\_meta\_box('gre\_model\_details', 'تفاصيل النموذج', 'gre\_render\_model\_meta\_box', 'gre\_model', 'normal', 'high');  
}

function gre\_render\_model\_meta\_box(WP\_Post $post): void {  
    wp\_nonce\_field('gre\_save\_model\_meta', 'gre\_model\_meta\_nonce');

    $fields \= \[  
        'tower\_id' \=\> '', 'code' \=\> '', 'description' \=\> '', 'area' \=\> '',  
        'rooms\_count' \=\> 0, 'bathrooms\_count' \=\> 0, 'kitchens\_count' \=\> 0,  
        'finishing\_level' \=\> '', 'finishing\_type' \=\> '', 'default\_status' \=\> '',  
        'availability\_date' \=\> '', 'price\_usd' \=\> '',  
        'has\_living\_room' \=\> '', 'has\_majlis' \=\> '', 'has\_balcony' \=\> '', 'has\_storage' \=\> '',  
        'has\_equipped\_kitchen' \=\> '', 'has\_ac' \=\> '', 'has\_fire\_safety' \=\> '',  
        'has\_cctv' \=\> '', 'has\_water\_meter' \=\> '', 'has\_electricity\_meter' \=\> '',  
        'has\_internet' \=\> '', 'has\_wc\_western' \=\> '', 'has\_jacuzzi\_sauna' \=\> ''  
    \];

    foreach ($fields as $key \=\> $default) {  
        $fields\[$key\] \= get\_post\_meta($post-\>ID, "\_gre\_model\_$key", true) ?: $default;  
    }

    if (get\_current\_screen()-\>action \=== 'add' && empty($fields\['code'\])) {  
        $towers \= get\_posts(\['post\_type' \=\> 'gre\_tower', 'posts\_per\_page' \=\> 1, 'orderby' \=\> 'ID', 'order' \=\> 'DESC'\]);  
        if (\!empty($towers)) {  
            $fields\['code'\] \= gre\_generate\_model\_short\_name($towers\[0\]-\>ID);  
            $fields\['tower\_id'\] \= $towers\[0\]-\>ID;  
        }  
    }

    echo '\<style\>.gre-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}.gre-full{grid-column:span 3}label{font-weight:bold;display:block;margin-bottom:5px}input,select,textarea{width:100%}\</style\>';  
    echo '\<div class="gre-grid"\>';  
    echo '\<div\>\<label\>البرج التابع\</label\>\<select name="gre\_model\_tower\_id" id="gre\_model\_tower\_id"\>';  
    $towers \= get\_posts(\['post\_type' \=\> 'gre\_tower', 'numberposts' \=\> \-1, 'orderby' \=\> 'date', 'order' \=\> 'DESC'\]);  
    foreach ($towers as $tower) {  
        $selected \= selected($fields\['tower\_id'\], $tower-\>ID, false);  
        echo "\<option value='{$tower-\>ID}' $selected\>{$tower-\>post\_title}\</option\>";  
    }  
    echo '\</select\>\</div\>';  
    echo '\<div\>\<label\>الرمز المختصر\</label\>\<input type="text" name="gre\_model\_code" id="gre\_model\_code" value="' . esc\_attr($fields\['code'\]) . '" /\>\</div\>';  
    echo '\<div\>\<label\>المساحة الإجمالية\</label\>\<input type="text" name="gre\_model\_area" value="' . esc\_attr($fields\['area'\]) . '" /\>\</div\>';  
    echo '\<div\>\<label\>عدد الغرف\</label\>\<input type="number" name="gre\_model\_rooms\_count" value="' . esc\_attr($fields\['rooms\_count'\]) . '" /\>\</div\>';  
    echo '\<div\>\<label\>عدد الحمّامات\</label\>\<input type="number" name="gre\_model\_bathrooms\_count" value="' . esc\_attr($fields\['bathrooms\_count'\]) . '" /\>\</div\>';  
    echo '\<div\>\<label\>عدد المطابخ\</label\>\<input type="number" name="gre\_model\_kitchens\_count" value="' . esc\_attr($fields\['kitchens\_count'\]) . '" /\>\</div\>';  
    echo '\<div\>\<label\>مستوى التشطيب\</label\>\<select name="gre\_model\_finishing\_level"\>  
        \<option value="ready"' . selected($fields\['finishing\_level'\], 'ready', false) . '\>مشطب\</option\>  
        \<option value="semi"' . selected($fields\['finishing\_level'\], 'semi', false) . '\>نصف تشطيب\</option\>  
        \<option value="none"' . selected($fields\['finishing\_level'\], 'none', false) . '\>بدون تشطيب\</option\>  
    \</select\>\</div\>';  
    echo '\<div\>\<label\>نوع التشطيب\</label\>\<select name="gre\_model\_finishing\_type"\>  
        \<option value="luxury"' . selected($fields\['finishing\_type'\], 'luxury', false) . '\>فاخر\</option\>  
        \<option value="deluxe"' . selected($fields\['finishing\_type'\], 'deluxe', false) . '\>ديلوكس\</option\>  
        \<option value="normal"' . selected($fields\['finishing\_type'\], 'normal', false) . '\>عادي\</option\>  
    \</select\>\</div\>';  
    echo '\<div\>\<label\>حالة الشقق الافتراضية\</label\>\<select name="gre\_model\_default\_status"\>  
        \<option value="available"' . selected($fields\['default\_status'\], 'available', false) . '\>متاحة\</option\>  
        \<option value="sold"' . selected($fields\['default\_status'\], 'sold', false) . '\>مباعة\</option\>  
        \<option value="under\_preparation"' . selected($fields\['default\_status'\], 'under\_preparation', false) . '\>قيد التجهيز\</option\>  
        \<option value="for\_finishing"' . selected($fields\['default\_status'\], 'for\_finishing', false) . '\>تحتاج تشطيب\</option\>  
    \</select\>\</div\>';  
    echo '\<div\>\<label\>تاريخ التوافر\</label\>\<input type="date" name="gre\_model\_availability\_date" value="' . esc\_attr($fields\['availability\_date'\]) . '" /\>\</div\>';  
    echo '\<div\>\<label\>السعر (اختياري بالدولار)\</label\>\<input type="text" name="gre\_model\_price\_usd" value="' . esc\_attr($fields\['price\_usd'\]) . '" /\>\</div\>';

    $checkboxes \= \[  
        'has\_living\_room' \=\> 'صالة جلوس', 'has\_majlis' \=\> 'مجلس منفصل', 'has\_balcony' \=\> 'شرفة (بلكونة)',  
        'has\_storage' \=\> 'مخزن', 'has\_equipped\_kitchen' \=\> 'مطبخ مجهّز', 'has\_ac' \=\> 'تدفئة وتبريد',  
        'has\_fire\_safety' \=\> 'أنظمة حماية', 'has\_cctv' \=\> 'كاميرات مراقبة', 'has\_water\_meter' \=\> 'عداد مياه مستقل',  
        'has\_electricity\_meter' \=\> 'عداد كهرباء مستقل', 'has\_internet' \=\> 'اتصال إنترنت',  
        'has\_wc\_western' \=\> 'حمام أفرنجي', 'has\_jacuzzi\_sauna' \=\> 'جاكوزي / ساونا'  
    \];  
    foreach ($checkboxes as $key \=\> $label) {  
        $checked \= checked($fields\[$key\], '1', false);  
        echo "\<div\>\<label\>\<input type='checkbox' name='gre\_model\_$key' value='1' $checked /\> $label\</label\>\</div\>";  
    }  
    echo '\<div class="gre-full"\>\<label\>وصف عام للنموذج\</label\>\<textarea name="gre\_model\_description"\>' . esc\_textarea($fields\['description'\]) . '\</textarea\>\</div\>';  
    echo '\</div\>';

    echo '\<script\>  
    document.addEventListener("DOMContentLoaded", function() {  
        const towerSelect \= document.getElementById("gre\_model\_tower\_id");  
        const shortNameField \= document.getElementById("gre\_model\_code");  
        const titleField \= document.getElementById("title");  
        let titleUpdated \= false;  
        let initialTitle \= titleField.value.trim();

        if (towerSelect && shortNameField && titleField) {  
            towerSelect.addEventListener("change", function() {  
                updateShortName(this.value);  
            });  
            if (towerSelect.value && (initialTitle \=== "" || initialTitle \=== "إضافة عنوان")) {  
                updateShortName(towerSelect.value);  
            }  
        }

        async function updateShortName(towerId) {  
            if (\!towerId) return;

            try {  
                const res \= await fetch(ajaxurl \+ "?action=gre\_generate\_model\_short\_name\&tower\_id=" \+ towerId);  
                const data \= await res.json();

                if (data.success && data.short\_name) {  
                    shortNameField.value \= data.short\_name;  
                    if (\!titleUpdated && (titleField.value.trim() \=== "" || titleField.value.trim() \=== "إضافة عنوان")) {  
                        titleField.value \= data.short\_name;  
                        titleUpdated \= true;  
                    }  
                } else {  
                    console.error("AJAX call failed or returned no short name:", data);  
                }  
            } catch (error) {  
                console.error("Error fetching short name:", error);  
            }  
        }  
    });  
    \</script\>';  
}

function gre\_save\_model\_meta($post\_id, $post) {  
	if (\!isset($\_POST\['gre\_model\_meta\_nonce'\]) || \!wp\_verify\_nonce($\_POST\['gre\_model\_meta\_nonce'\], 'gre\_save\_model\_meta')) return;  
	if (defined('DOING\_AUTOSAVE') && DOING\_AUTOSAVE) return;  
	if (\!current\_user\_can('edit\_post', $post\_id)) return;  
   
	$fields \= \[  
        'tower\_id','code','description','area','rooms\_count','bathrooms\_count','kitchens\_count',  
        'finishing\_level','finishing\_type','default\_status','availability\_date','price\_usd',  
        'has\_living\_room','has\_majlis','has\_balcony','has\_storage','has\_equipped\_kitchen',  
        'has\_ac','has\_fire\_safety','has\_cctv','has\_water\_meter','has\_electricity\_meter',  
        'has\_internet','has\_wc\_western','has\_jacuzzi\_sauna'  
	\];  
	$meta \= \[\];  
	foreach ($fields as $field) {  
    	$value \= isset($\_POST\["gre\_model\_$field"\]) ? sanitize\_text\_field($\_POST\["gre\_model\_$field"\]) : '';  
    	update\_post\_meta($post\_id, "\_gre\_model\_$field", $value);  
    	$meta\[$field\] \= $value;  
	}  
   
	$generated\_code \= gre\_generate\_model\_short\_name($meta\['tower\_id'\]);  
	if ($generated\_code && $generated\_code \!== $meta\['code'\]) {  
    	update\_post\_meta($post\_id, '\_gre\_model\_code', $generated\_code);  
    	$meta\['code'\] \= $generated\_code;  
	}  
   
	if (get\_post\_meta($post\_id, '\_gre\_apartment\_generated', true) \!== '1' && \!empty($meta\['tower\_id'\]) && $meta\['code'\]) {  
    	$tower\_id \= $meta\['tower\_id'\];  
    	$model\_code \= $meta\['code'\];  
    	$floors \= (int) get\_post\_meta($tower\_id, '\_gre\_tower\_floors', true);  
    	$status \= $meta\['default\_status'\];  
   
    	for ($i \= 1; $i \<= $floors; $i++) {  
        	$code \= "{$model\_code}-{$i}";  
        	$existing \= get\_posts(\[  
            	'post\_type' \=\> 'gre\_apartment',  
            	'meta\_key' \=\> '\_gre\_apartment\_apartment\_code',  
            	'meta\_value' \=\> $code,  
            	'post\_status' \=\> 'any',  
            	'numberposts' \=\> 1  
        	\]);  
        	if (empty($existing)) {  
            	wp\_insert\_post(\[  
                	'post\_type' \=\> 'gre\_apartment',  
                	'post\_status' \=\> 'publish',  
                	'post\_title' \=\> "شقة الدور {$i}",  
                	'meta\_input' \=\> \[  
                        '\_gre\_apartment\_apartment\_code' \=\> $code,  
                        '\_gre\_apartment\_apartment\_number' \=\> $i,  
                        '\_gre\_apartment\_floor\_number' \=\> $i,  
                        '\_gre\_apartment\_model\_id' \=\> $post\_id,  
                        '\_gre\_apartment\_tower\_id' \=\> $tower\_id,  
                    	'\_gre\_apartment\_status' \=\> $status,  
                        '\_gre\_apartment\_custom\_price\_usd' \=\> $meta\['price\_usd'\],  
                        '\_gre\_apartment\_custom\_finishing\_level' \=\> $meta\['finishing\_level'\],  
                        '\_gre\_apartment\_custom\_finishing\_type' \=\> $meta\['finishing\_type'\]  
                	\]  
            	\]);  
        	}  
    	}  
    	update\_post\_meta($post\_id, '\_gre\_apartment\_generated', '1');  
	}  
}  
   
add\_action('before\_delete\_post', function ($post\_id) {  
	$post \= get\_post($post\_id);  
	if ($post && $post-\>post\_type \=== 'gre\_model') {  
    	$apartments \= get\_posts(\[  
        	'post\_type' \=\> 'gre\_apartment',  
        	'posts\_per\_page' \=\> \-1,  
        	'meta\_query' \=\> \[\['key' \=\> '\_gre\_apartment\_model\_id', 'value' \=\> $post\_id\]\],  
    	\]);  
    	foreach ($apartments as $apt) {  
        	wp\_delete\_post($apt-\>ID, true);  
    	}  
	}  
});  
?\>  
   
   
   
   
admin/tower-meta-boxes.php this file contains:  

<?php  
add\_action('add\_meta\_boxes', 'gre\_add\_tower\_meta\_boxes');  
add\_action('save\_post', 'gre\_save\_tower\_meta');  
add\_filter('enter\_title\_here', 'gre\_change\_title\_placeholder');  
   
define('GRE\_DEFAULT\_LAT', 15.3694);  
define('GRE\_DEFAULT\_LNG', 44.1910);  
   
function gre\_change\_title\_placeholder($title) {  
	$screen \= get\_current\_screen();  
	if ($screen-\>post\_type \=== 'gre\_tower') return 'اسم البرج';  
	return $title;  
}    
function gre\_add\_tower\_meta\_boxes() {  
	add\_meta\_box('gre\_tower\_details', 'تفاصيل البرج', 'gre\_render\_tower\_meta\_box', 'gre\_tower', 'normal', 'high');  
}     
function gre\_get\_tower\_meta($post\_id, $key, $default \= '') {  
	return get\_post\_meta($post\_id, "\_gre\_tower\_$key", true) ?: $default;  
}  
   
function gre\_render\_tower\_meta\_box($post) {  
	wp\_nonce\_field('gre\_save\_tower\_meta', 'gre\_tower\_meta\_nonce');  
   
	$fields \= \[  
    	'short\_name' \=\> '', 'floors' \=\> '', 'location\_desc' \=\> '', 'location\_lat' \=\> '',  
    	'location\_lng' \=\> '', 'city' \=\> 'أمانة العاصمة', 'district' \=\> '', 'has\_parking' \=\> '',  
    	'total\_units' \=\> '', 'available\_units' \=\> '', 'building\_type' \=\> '', 'build\_year' \=\> '',  
    	'status' \=\> '', 'elevators' \=\> '', 'has\_generator' \=\> '', 'has\_shops' \=\> '',  
    	'general\_description' \=\> ''  
	\];  
   
	foreach ($fields as $key \=\> $default) {  
    	$fields\[$key\] \= gre\_get\_tower\_meta($post-\>ID, $key, $default);  
	}  
   
	echo '\<style\>  
    	.gre-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }  
    	.gre-half { grid-column: span 2; }  
    	.gre-full { grid-column: span 4; }  
    	.gre-map-wrap { display: flex; gap: 20px; margin-top: 20px; }  
    	.gre-map { width: 50%; height: 400px; }  
    	.gre-map-fields { width: 50%; display: flex; flex-direction: column; gap: 10px; }  
    	input\[type="number"\] { max-width: 100%; }  
    	select, input\[type="text"\], textarea { width: 100%; }  
	\</style\>';  
   
	echo '\<div class="gre-grid"\>';  
	echo '\<div\>\<label\>الاسم المختصر (بالإنجليزية)\</label\>\<input type="text" name="gre\_tower\_short\_name" value="' . esc\_attr($fields\['short\_name'\]) . '" style="width: 100px;" /\>\</div\>';  
	echo '\<div\>\<label\>عدد الأدوار\</label\>\<input type="number" name="gre\_tower\_floors" value="' . esc\_attr($fields\['floors'\]) . '" /\>\</div\>';  
	echo '\<div\>\<label\>عدد المصاعد\</label\>\<input type="number" name="gre\_tower\_elevators" value="' . esc\_attr($fields\['elevators'\]) . '" /\>\</div\>';  
	echo '\<div\>\<label\>سنة البناء\</label\>\<input type="number" name="gre\_tower\_build\_year" value="' . esc\_attr($fields\['build\_year'\]) . '" /\>\</div\>';  
   
	$checkboxes \= \[  
    	'has\_generator' \=\> 'مولد كهربائي',  
    	'has\_parking' \=\> 'موقف سيارات',  
    	'has\_shops' \=\> 'محلات تجارية'  
	\];  
	foreach ($checkboxes as $key \=\> $label) {  
    	echo "\<div\>\<label\>\<input type='checkbox' name='gre\_tower\_$key' value='1'" . checked($fields\[$key\], '1', false) . "\> $label\</label\>\</div\>";  
	}  
   
	echo '\<div\>\<label\>نوع المبنى\</label\>\<select name="gre\_tower\_building\_type"\>  
    	\<option value="tower"' . selected($fields\['building\_type'\], 'tower', false) . '\>برج بعدة نماذج وشقق\</option\>  
    	\<option value="villa"' . selected($fields\['building\_type'\], 'villa', false) . '\>فلة بنموذج وحيد\</option\>  
	\</select\>\</div\>';  
	echo '\</div\>';  
   
	gre\_render\_location\_fields($fields);  
   
	echo '\<div class="gre-grid"\>';  
	echo '\<div class="gre-full"\>\<label\>الوصف المكاني\</label\>\<textarea name="gre\_tower\_location\_desc"\>' . esc\_textarea($fields\['location\_desc'\]) . '\</textarea\>\</div\>';  
	echo '\<div class="gre-full"\>\<label\>الوصف العام\</label\>\<textarea name="gre\_tower\_general\_description"\>' . esc\_textarea($fields\['general\_description'\]) . '\</textarea\>\</div\>';  
	echo '\</div\>';  
   
	echo '\<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"\>\</script\>';  
	echo '\<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/\>';  
	echo '\<script\>  
    	const GRE\_DEFAULT\_LAT \= ' . GRE\_DEFAULT\_LAT . ';  
    	const GRE\_DEFAULT\_LNG \= ' . GRE\_DEFAULT\_LNG . ';  
   
        document.addEventListener("DOMContentLoaded", function() {  
        	var latInput \= document.getElementById("gre\_tower\_location\_lat");  
        	var lngInput \= document.getElementById("gre\_tower\_location\_lng");  
        	var lat \= parseFloat(latInput.value) || GRE\_DEFAULT\_LAT;  
        	var lng \= parseFloat(lngInput.value) || GRE\_DEFAULT\_LNG;  
        	var map \= L.map("map").setView(\[lat, lng\], 13);  
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);  
        	var marker \= L.marker(\[lat, lng\], {draggable: true}).addTo(map);  
        	marker.on("dragend", function(e) {  
            	var position \= marker.getLatLng();  
            	latInput.value \= position.lat.toFixed(6);  
            	lngInput.value \= position.lng.toFixed(6);  
        	});  
    	});  
	\</script\>';  
}  
function gre\_render\_location\_fields($fields) {  
	echo '\<div class="gre-map-wrap"\>';  
	echo '\<div id="map" class="gre-map"\>\</div\>';  
	echo '\<div class="gre-map-fields"\>';  
	echo '\<label\>اسم المدينة / المنطقة\</label\>\<select name="gre\_tower\_city"\>  
    	\<option value="أمانة العاصمة"' . selected($fields\['city'\], 'أمانة العاصمة', false) . '\>أمانة العاصمة\</option\>  
    	\<option value="عدن"' . selected($fields\['city'\], 'عدن', false) . '\>عدن\</option\>  
    	\<option value="تعز"' . selected($fields\['city'\], 'تعز', false) . '\>تعز\</option\>  
    	\<option value="الحديدة"' . selected($fields\['city'\], 'الحديدة', false) . '\>الحديدة\</option\>  
    	\<option value="حضرموت"' . selected($fields\['city'\], 'حضرموت', false) . '\>حضرموت\</option\>  
    	\<option value="إب"' . selected($fields\['city'\], 'إب', false) . '\>إب\</option\>  
    	\<option value="ذمار"' . selected($fields\['city'\], 'ذمار', false) . '\>ذمار\</option\>  
    	\<option value="عمران"' . selected($fields\['city'\], 'عمران', false) . '\>عمران\</option\>  
    	\<option value="صنعاء"' . selected($fields\['city'\], 'صنعاء', false) . '\>صنعاء\</option\>  
    	\<option value="مأرب"' . selected($fields\['city'\], 'مأرب', false) . '\>مأرب\</option\>  
	\</select\>';  
	echo '\<label\>اسم المديرية\</label\>\<input type="text" name="gre\_tower\_district" value="' . esc\_attr($fields\['district'\]) . '" /\>';  
	echo '\<label\>خط العرض\</label\>\<input type="text" name="gre\_tower\_location\_lat" id="gre\_tower\_location\_lat" value="' . esc\_attr($fields\['location\_lat'\]) . '" /\>';  
	echo '\<label\>خط الطول\</label\>\<input type="text" name="gre\_tower\_location\_lng" id="gre\_tower\_location\_lng" value="' . esc\_attr($fields\['location\_lng'\]) . '" /\>';  
	echo '\<label\>عدد الشقق الإجمالي\</label\>\<input type="number" name="gre\_tower\_total\_units" value="' . esc\_attr($fields\['total\_units'\]) . '" readonly /\>';  
	echo '\<label\>عدد الشقق المتوفرة\</label\>\<input type="number" name="gre\_tower\_available\_units" value="' . esc\_attr($fields\['available\_units'\]) . '" readonly /\>';  
	echo '\</div\>\</div\>';  
}  
   
function gre\_save\_tower\_field($post\_id, $key) {  
	$val \= sanitize\_text\_field($\_POST\["gre\_tower\_$key"\] ?? '');  
	update\_post\_meta($post\_id, "\_gre\_tower\_$key", $val);  
}  
   
function gre\_save\_tower\_meta($post\_id) {  
	if (  
    	\!isset($\_POST\['gre\_tower\_meta\_nonce'\]) ||  
        \!wp\_verify\_nonce($\_POST\['gre\_tower\_meta\_nonce'\], 'gre\_save\_tower\_meta') ||  
    	(defined('DOING\_AUTOSAVE') && DOING\_AUTOSAVE) ||  
    	\!current\_user\_can('edit\_post', $post\_id)  
	) return;  
   
	$fields \= \['short\_name', 'floors', 'location\_desc', 'location\_lat', 'location\_lng', 'city', 'district', 'has\_parking',  
           	'total\_units', 'available\_units', 'building\_type', 'build\_year', 'status', 'elevators', 'has\_generator',  
           	'has\_shops', 'general\_description'\];   
	foreach ($fields as $field) {  
    	gre\_save\_tower\_field($post\_id, $field);  
	}  
}  
?\>  
   
   
\* includes/class-apartment.php this file contains:  
\<?php  
class GRE\_Apartment {  
	public function \_\_construct() {  
    	add\_action('init', \[$this, 'register\_post\_type'\]);  
    	// هذا السطر كان ناقص ويجب إضافته لتحميل حقول الشقة  
    	require\_once plugin\_dir\_path(\_\_FILE\_\_) . '/../admin/apartment-meta-boxes.php';  
	}  
   
	public function register\_post\_type() {  
    	register\_post\_type('gre\_apartment', \[  
        	'label' \=\> 'الشقق',  
        	'labels' \=\> \[  
            	'name'           	\=\> 'الشقق',  
            	'singular\_name'  	\=\> 'شقة',  
            	'add\_new'        	\=\> 'إضافة شقة جديدة',  
            	'add\_new\_item'   	\=\> 'إضافة شقة جديدة',  
            	'edit\_item'      	\=\> 'تعديل شقة',  
            	'new\_item'       	\=\> 'شقة جديدة',  
            	'view\_item'      	\=\> 'عرض الشقة',  
            	'view\_items'     	\=\> 'عرض الشقق',  
            	'search\_items'   	\=\> 'البحث في الشقق',  
            	'not\_found'      	\=\> 'لم يتم العثور على شقق',  
            	'not\_found\_in\_trash' \=\> 'لم يتم العثور على شقق في سلة المهملات',  
            	'parent\_item\_colon'  \=\> 'الشقة الأب:',  
            	'all\_items'      	\=\> 'كل الشقق',  
            	'archives'       	\=\> 'أرشيف الشقق',  
            	'attributes'     	\=\> 'خصائص الشقة',  
            	'insert\_into\_item'   \=\> 'إدراج في الشقة',  
            	'uploaded\_to\_this\_item' \=\> 'تم الرفع إلى هذه الشقة',  
            	'featured\_image' 	\=\> 'صورة الشقة البارزة',  
            	'set\_featured\_image' \=\> 'تعيين الصورة البارزة للشقة',  
            	'remove\_featured\_image' \=\> 'إزالة الصورة البارزة للشقة',  
            	'use\_featured\_image' \=\> 'استخدام كصورة بارزة للشقة',  
            	'menu\_name'      	\=\> 'الشقق',  
            	'filter\_items\_list' \=\> 'فلترة قائمة الشقق',  
            	'items\_list\_navigation' \=\> 'قائمة تصفح الشقق',  
            	'items\_list'     	\=\> 'قائمة الشقق',  
            	'item\_published' 	\=\> 'تم نشر الشقة',  
            	'item\_published\_privately' \=\> 'تم نشر الشقة خاص',  
            	'item\_reverted\_to\_draft' \=\> 'تمت إعادة الشقة إلى مسودة',  
            	'item\_scheduled' 	\=\> 'تم جدولة الشقة',  
            	'item\_updated'   	\=\> 'تم تحديث الشقة',  
        	\],  
        	'public'         	\=\> true,  
        	'supports'       	\=\> \['title', 'thumbnail'\],  
        	'menu\_position'  	\=\> 7,  
        	'menu\_icon'      	\=\> 'dashicons-admin-home',  
        	'has\_archive'    	\=\> true,  
        	'rewrite'        	\=\> \['slug' \=\> 'apartments'\],  
    	\]);  
	}  
}  
?\>  
   
   
 \* includes/class-init-data.php this file contains:  
\<?php  
// كود لاحق لإنشاء بيانات تجريبية تلقائيًا  
?\>  
 \* includes/class-loader.php this file contains:  
\<?php  
spl\_autoload\_register(function ($class) {  
	if (strpos($class, 'GRE\_') \=== 0\) {  
    	$file \= plugin\_dir\_path(\_\_FILE\_\_) . 'class-' . strtolower(str\_replace('GRE\_', '', $class)) . '.php';  
    	if (file\_exists($file)) {  
        	require\_once $file;  
    	}  
	}  
});  
?\>  
 

 \* includes/class-model.php this file contains:  
\<?php  
class GRE\_Model {  
	public function \_\_construct() {  
    	add\_action('init', \[$this, 'register\_post\_type'\]);  
    	require\_once plugin\_dir\_path(\_\_FILE\_\_) . '/../admin/model-meta-boxes.php';  
	}  
   
	public function register\_post\_type() {  
    	register\_post\_type('gre\_model', \[  
        	'label' \=\> 'النماذج',  
        	'labels' \=\> \[  
            	'name'           	\=\> 'النماذج',  
            	'singular\_name'  	\=\> 'نموذج',  
            	'add\_new'        	\=\> 'إضافة نموذج جديد',  
            	'add\_new\_item'   	\=\> 'إضافة نموذج جديد',  
            	'edit\_item'      	\=\> 'تعديل نموذج',  
            	'new\_item'       	\=\> 'نموذج جديد',  
            	'view\_item'      	\=\> 'عرض النموذج',  
            	'view\_items'     	\=\> 'عرض النماذج',  
            	'search\_items'   	\=\> 'البحث في النماذج',  
            	'not\_found'      	\=\> 'لم يتم العثور على نماذج',  
            	'not\_found\_in\_trash' \=\> 'لم يتم العثور على نماذج في سلة المهملات',  
            	'parent\_item\_colon'  \=\> 'النموذج الأب:',  
            	'all\_items'      	\=\> 'كل النماذج',  
            	'archives'       	\=\> 'أرشيف النماذج',  
            	'attributes'     	\=\> 'خصائص النموذج',  
            	'insert\_into\_item'   \=\> 'إدراج في النموذج',  
            	'uploaded\_to\_this\_item' \=\> 'تم الرفع إلى هذا النموذج',  
            	'featured\_image' 	\=\> 'صورة النموذج البارزة',  
            	'set\_featured\_image' \=\> 'تعيين الصورة البارزة للنموذج',  
            	'remove\_featured\_image' \=\> 'إزالة الصورة البارزة للنموذج',  
            	'use\_featured\_image' \=\> 'استخدام كصورة بارزة للنموذج',  
            	'menu\_name'      	\=\> 'النماذج',  
            	'filter\_items\_list' \=\> 'فلترة قائمة النماذج',  
            	'items\_list\_navigation' \=\> 'قائمة تصفح النماذج',  
            	'items\_list'     	\=\> 'قائمة النماذج',  
            	'item\_published' 	\=\> 'تم نشر النموذج',  
            	'item\_published\_privately' \=\> 'تم نشر النموذج خاص',  
            	'item\_reverted\_to\_draft' \=\> 'تمت إعادة النموذج إلى مسودة',  
            	'item\_scheduled' 	\=\> 'تم جدولة النموذج',  
            	'item\_updated'   	\=\> 'تم تحديث النموذج',  
        	\],  
        	'public'         	\=\> true,  
        	'supports'       	\=\> \['title', 'thumbnail'\],  
        	'menu\_position'  	\=\> 6,  
        	'menu\_icon'      	\=\> 'dashicons-layout',  
        	'has\_archive'    	\=\> false,  
        	'rewrite'        	\=\> \['slug' \=\> 'models'\],  
        	'show\_in\_rest'   	\=\> false,  
    	\]);  
	}  
}  
?\>  
   
   
   
 \* includes/class-tower.php this file contains:  
\<?php  
class GRE\_Tower {  
	public function \_\_construct() {  
    	add\_action('init', \[$this, 'register\_post\_type'\]);  
    	add\_action('edit\_form\_after\_title', \[$this, 'add\_media\_button\_above\_fields'\]);  
    	require\_once plugin\_dir\_path(\_\_FILE\_\_) . '/../admin/tower-meta-boxes.php';  
	}  
   
	public function register\_post\_type() {  
    	register\_post\_type('gre\_tower', \[  
        	'label' \=\> 'الأبراج',  
        	'labels' \=\> \[  
            	'name'           	\=\> 'الأبراج',  
            	'singular\_name'  	\=\> 'برج',  
            	'add\_new'        	\=\> 'إضافة برج جديد',  
            	'add\_new\_item'   	\=\> 'إضافة برج جديد',  
            	'edit\_item'      	\=\> 'تعديل برج',  
            	'new\_item'       	\=\> 'برج جديد',  
            	'view\_item'      	\=\> 'عرض البرج',  
            	'view\_items'     	\=\> 'عرض الأبراج',  
            	'search\_items'   	\=\> 'البحث في الأبراج',  
            	'not\_found'      	\=\> 'لم يتم العثور على أبراج',  
            	'not\_found\_in\_trash' \=\> 'لم يتم العثور على أبراج في سلة المهملات',  
            	'parent\_item\_colon'  \=\> 'البرج الأب:',  
            	'all\_items'      	\=\> 'كل الأبراج',  
            	'archives'       	\=\> 'أرشيف الأبراج',  
            	'attributes'     	\=\> 'خصائص البرج',  
            	'insert\_into\_item'   \=\> 'إدراج في البرج',  
            	'uploaded\_to\_this\_item' \=\> 'تم الرفع إلى هذا البرج',  
            	'featured\_image' 	\=\> 'صورة البرج البارزة',  
            	'set\_featured\_image' \=\> 'تعيين الصورة البارزة للبرج',  
            	'remove\_featured\_image' \=\> 'إزالة الصورة البارزة للبرج',  
            	'use\_featured\_image' \=\> 'استخدام كصورة بارزة للبرج',  
            	'menu\_name'      	\=\> 'الأبراج',  
            	'filter\_items\_list' \=\> 'فلترة قائمة الأبراج',  
            	'items\_list\_navigation' \=\> 'قائمة تصفح الأبراج',  
            	'items\_list'     	\=\> 'قائمة الأبراج',  
            	'item\_published' 	\=\> 'تم نشر البرج',  
            	'item\_published\_privately' \=\> 'تم نشر البرج خاص',  
            	'item\_reverted\_to\_draft' \=\> 'تمت إعادة البرج إلى مسودة',  
            	'item\_scheduled' 	\=\> 'تم جدولة البرج',  
            	'item\_updated'   	\=\> 'تم تحديث البرج',  
        	\],  
        	'public'         	\=\> true,  
        	'supports'       	\=\> \['title', 'thumbnail'\],  
        	'menu\_position'  	\=\> 5,  
        	'menu\_icon'      	\=\> 'dashicons-building',  
        	'has\_archive'    	\=\> true,  
        	'rewrite'        	\=\> \['slug' \=\> 'towers'\],  
    	\]);  
	}  
   
	public function add\_media\_button\_above\_fields($post) {  
    	if ($post-\>post\_type \=== 'gre\_tower') {  
        	echo '\<div style="margin: 10px 0;"\>';  
        	do\_action('media\_buttons', $post-\>ID);  
        	echo '\</div\>';  
    	}  
	}  
}  
?\>  
   
   
   
\* public/public-functions.php this file contains:  
\<?php  
/\*\*  
 \* دوال الواجهة الأمامية العامة للإضافة.  
 \*/  
   
/\*\*  
 \* تسجيل وتضمين أنماط وخطوط الإضافة.  
 \*/  
function gre\_enqueue\_styles() {  
	// تضمين Leaflet CSS و JS  
	wp\_enqueue\_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');  
	wp\_enqueue\_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', \[\], null, true);  
   
	// تضمين ملف أنماط الكيان  
	wp\_enqueue\_style('gre-entity-styles', plugin\_dir\_url(\_\_FILE\_\_) . 'assets/css/gre-entity-single.css', \[\], '1.0.0');  
}  
add\_action('wp\_enqueue\_scripts', 'gre\_enqueue\_styles');  
   
/\*\*  
 \* دالة مساعدة لتوليد استعلام meta\_query ديناميكي.  
 \*  
 \* @param array $filters مصفوفة الفلاتر.  
 \* @param string $entity\_type نوع الكيان (apartment, model, tower).  
 \* @return array استعلام meta\_query.  
 \*/  
function gre\_build\_meta\_query($filters, $entity\_type) {  
	$meta\_query \= \[\];  
   
	foreach ($filters as $key \=\> $filter) {  
    	$meta\_key \= "\_gre\_{$entity\_type}\_{$key}";  
    	$value \= $filter\['value'\];  
    	$compare \= $filter\['compare'\] ?? '=';  
    	$type \= $filter\['type'\] ?? 'CHAR';  
   
    	if (empty($value) && $type \!== 'ARRAY') continue;  
   
    	if (is\_array($value)) {  
        	$meta\_query\[\] \= \[  
            	'key' \=\> $meta\_key,  
            	'value' \=\> $value,  
            	'compare' \=\> $compare,  
            	'type' \=\> $type,  
        	\];  
    	} else {  
        	$meta\_query\[\] \= \[  
            	'key' \=\> $meta\_key,  
            	'value' \=\> sanitize\_text\_field($value),  
            	'compare' \=\> $compare,  
            	'type' \=\> $type,  
        	\];  
    	}  
	}  
   
	return $meta\_query;  
}  
   
/\*\*  
 \* دالة مساعدة لاسترداد معرف النموذج من عنوانه.  
 \*  
 \* @param string $title عنوان النموذج.  
 \* @return int معرف النموذج.  
 \*/  
function gre\_get\_model\_id\_by\_title($title) {  
	$model \= get\_page\_by\_title($title, OBJECT, 'gre\_model');  
	return $model ? $model-\>ID : 0;  
}  
   
/\*\*  
 \* تعرض تفاصيل كيان محدد (نموذج، شقة، برج).  
 \*  
 \* @param int $post\_id معرف المنشور.  
 \* @param string $type نوع الكيان: model | apartment | tower.  
 \*/  
function gre\_render\_entity\_details($post\_id, $type \= 'model') {  
	$fields\_map \= \[\];  
   
	if ($type \=== 'model') {  
    	$fields\_map \= \[  
        	'\_gre\_model\_code'    	\=\> \['label' \=\> '\<span class="dashicons dashicons-tag"\>\</span\> كود النموذج', 'type' \=\> 'text'\],  
        	'\_gre\_model\_area'    	\=\> \['label' \=\> '\<span class="dashicons dashicons-editor-expand"\>\</span\> المساحة الإجمالية (م²)', 'type' \=\> 'text'\],  
        	'\_gre\_model\_rooms\_count'   \=\> \['label' \=\> '\<span class="dashicons dashicons-admin-home"\>\</span\> عدد الغرف', 'type' \=\> 'number'\],  
        	'\_gre\_model\_bathrooms\_count' \=\> \['label' \=\> '\<span class="dashicons dashicons-bathroom"\>\</span\> عدد الحمامات', 'type' \=\> 'number'\],  
        	'\_gre\_model\_finishing\_type' \=\> \['label' \=\> '\<span class="dashicons dashicons-admin-appearance"\>\</span\> نوع التشطيب', 'type' \=\> 'text'\],  
        	'\_gre\_model\_finishing\_level' \=\> \['label' \=\> '\<span class="dashicons dashicons-editor-ul"\>\</span\> مستوى التشطيب', 'type' \=\> 'text'\],  
    	\];  
	} elseif ($type \=== 'apartment') {  
    	$fields\_map \= \[  
        	'\_gre\_apartment\_apartment\_number' \=\> \['label' \=\> '\<span class="dashicons dashicons-building"\>\</span\> رقم الشقة', 'type' \=\> 'number'\],  
        	'\_gre\_apartment\_status'    	\=\> \['label' \=\> '\<span class="dashicons dashicons-info-outline"\>\</span\> الحالة', 'type' \=\> 'status'\],  
        	'\_gre\_apartment\_floor\_number'   \=\> \['label' \=\> '\<span class="dashicons dashicons-editor-textcolor"\>\</span\> الدور', 'type' \=\> 'number'\],  
        	'\_gre\_apartment\_custom\_price\_usd' \=\> \['label' \=\> '\<span class="dashicons dashicons-cart"\>\</span\> السعر', 'type' \=\> 'price'\],  
    	\];  
	} elseif ($type \=== 'tower') {  
    	$fields\_map \= \[  
        	'\_gre\_tower\_short\_name'  	\=\> \['label' \=\> '\<span class="dashicons dashicons-tag"\>\</span\> الاسم المختصر', 'type' \=\> 'text'\],  
        	'\_gre\_tower\_floors'      	\=\> \['label' \=\> '\<span class="dashicons dashicons-editor-ol"\>\</span\> عدد الأدوار', 'type' \=\> 'number'\],  
        	'\_gre\_tower\_city'        	\=\> \['label' \=\> '\<span class="dashicons dashicons-location-alt"\>\</span\> المدينة', 'type' \=\> 'text'\],  
        	'\_gre\_tower\_district'    	\=\> \['label' \=\> '\<span class="dashicons dashicons-admin-site"\>\</span\> المديرية', 'type' \=\> 'text'\],  
        	'\_gre\_tower\_build\_year'  	\=\> \['label' \=\> '\<span class="dashicons dashicons-clock"\>\</span\> سنة البناء', 'type' \=\> 'number'\],  
        	'\_gre\_tower\_building\_type'   \=\> \['label' \=\> '\<span class="dashicons dashicons-admin-multisite"\>\</span\> نوع المبنى', 'type' \=\> 'text'\],  
        	'\_gre\_tower\_status'      	\=\> \['label' \=\> '\<span class="dashicons dashicons-info-outline"\>\</span\> الحالة', 'type' \=\> 'status'\],  
        	'\_gre\_tower\_total\_units' 	\=\> \['label' \=\> '\<span class="dashicons dashicons-editor-kitchensink"\>\</span\> عدد الشقق الإجمالي', 'type' \=\> 'number'\],  
        	'\_gre\_tower\_available\_units' \=\> \['label' \=\> '\<span class="dashicons dashicons-editor-ul"\>\</span\> عدد الشقق المتوفرة', 'type' \=\> 'number'\],  
    	\];  
	}  
   
	if (empty($fields\_map)) return;  
   
	echo '\<ul class="gre-entity-details-list"\>';  
	foreach ($fields\_map as $meta\_key \=\> $field) {  
    	$value \= get\_post\_meta($post\_id, $meta\_key, true);  
    	if (\!empty($value)) {  
        	$display\_value \= esc\_html($value);  
        	if ($field\['type'\] \=== 'status' && $meta\_key \=== '\_gre\_apartment\_status') {  
            	$display\_value \= esc\_html(gre\_get\_apartment\_status\_label($value));  
        	}  
        	echo '\<li\>\<span class="detail-label"\>' . $field\['label'\] . ':\</span\> ' . $display\_value . '\</li\>';  
    	}  
	}  
	echo '\</ul\>';  
}  
   
/\*\*  
 \* إرجاع تسمية الحالة المقابلة لقيمة الحالة.  
 \*  
 \* @param string $status قيمة الحالة.  
 \* @return string تسمية الحالة.  
 \*/  
function gre\_get\_apartment\_status\_label($status) {  
	$status\_labels \= \[  
    	'available' \=\> 'متاحة',  
    	'sold' \=\> 'مباعة',  
    	'under\_preparation' \=\> 'قيد التجهيز',  
    	'for\_finishing' \=\> 'تحتاج تشطيب',  
	\];  
   
	return $status\_labels\[$status\] ?? 'غير محدد';  
}  
   
/\*\*  
 \* تعرض خريطة موقع البرج.  
 \*  
 \* @param int $post\_id معرف البرج.  
 \*/  
function gre\_render\_tower\_location\_map($post\_id) {  
	$lat \= get\_post\_meta($post\_id, '\_gre\_tower\_location\_lat', true);  
	$lng \= get\_post\_meta($post\_id, '\_gre\_tower\_location\_lng', true);  
	$location\_desc \= get\_post\_meta($post\_id, '\_gre\_tower\_location\_desc', true);  
   
	if ($lat && $lng) {  
    	echo '\<div class="map-container"\>';  
    	echo '\<iframe src="https://www.google.com/maps?q=' . esc\_attr($lat) . ',' . esc\_attr($lng) . '\&z=17\&output=embed" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"\>\</iframe\>';  
    	echo '\</div\>';  
	}  
   
	if ($location\_desc) {  
    	echo '\<div class="location-description"\>';  
    	echo '\<p\>' . esc\_html($location\_desc) . '\</p\>';  
    	echo '\</div\>';  
	}  
}  
?\>  
   
   
\* public/assets/css/gre-entity-single.css contains :  
/\* \==========================================================================  
أنماط صفحات الكيانات (الأبراج، النماذج، الشقق) \- gre-entity-single.css  
\========================================================================== \*/  
   
/\* أنماط عامة للصفحة \*/  
.site-main {  
padding-top: 20px;  
padding-bottom: 40px;  
}  
   
.gre-entity-single {  
background-color: \#fff;  
border: 1px solid \#eee;  
border-radius: 5px;  
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);  
padding: 20px;  
margin-bottom: 30px;  
}  
   
.entry-header {  
text-align: center;  
margin-bottom: 20px;  
}  
   
.entry-title {  
margin-bottom: 10px;  
border-bottom: 2px solid \#ccc;  
padding-bottom: 10px;  
}  
   
/\* صورة الكيان \*/  
.gre-entity-thumb {  
max-width: 100%;  
height: auto;  
border-radius: 5px;  
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);  
margin-top: 15px;  
}  
   
/\* قسم التفاصيل \*/  
.gre-entity-details {  
background-color: \#f7f9fa;  
padding: 20px;  
border-radius: 8px;  
margin-bottom: 20px;  
box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);  
}  
   
.gre-entity-details-list {  
list-style: none;  
padding: 0;  
margin: 0;  
display: grid;  
grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));  
gap: 10px;  
}  
   
.gre-entity-details-list li {  
background-color: \#fff;  
padding: 10px;  
border-radius: 6px;  
border: 1px solid \#e0e0e0;  
font-size: 0.9em;  
}  
   
.detail-label {  
font-weight: bold;  
display: block;  
margin-bottom: 5px;  
}  
   
.gre-entity-details-list .dashicons {  
font-size: 14px;  
width: 14px;  
height: 14px;  
line-height: 1;  
vertical-align: middle;  
margin-right: 5px;  
}  
   
/\* الوصف العام \*/  
.gre-entity-description {  
background-color: \#f0f8ff;  
padding: 15px;  
border-radius: 6px;  
margin-bottom: 20px;  
font-style: italic;  
font-size: 0.95em;  
line-height: 1.5;  
color: \#555;  
}  
   
/\* الخريطة \*/  
.gre-entity-map {  
margin-bottom: 30px;  
}  
   
.gre-entity-map \#map {  
height: 350px;  
border-radius: 6px;  
box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);  
}  
   
/\* أنماط إضافية لصور الشقة \*/  
.gre-entity-images {  
margin-bottom: 25px;  
}  
   
.gre-entity-images h2 {  
font-size: 1.3em;  
margin-bottom: 10px;  
border-bottom: 2px solid \#ddd;  
padding-bottom: 8px;  
text-align: center;  
}  
   
.gre-entity-images-gallery {  
display: flex;  
flex-wrap: wrap;  
justify-content: center;  
gap: 8px;  
}  
   
.gre-entity-images-gallery img {  
width: 100%;  
max-width: 120px;  
height: auto;  
object-fit: cover;  
border-radius: 5px;  
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);  
}  
   
/\* أنماط رابط النموذج المرتبط \*/  
.gre-entity-model-link {  
margin-top: 15px;  
text-align: center;  
}  
   
.gre-entity-model-link a {  
display: inline-block;  
border-radius: 5px;  
transition: background-color 0.3s ease;  
font-weight: bold;  
font-size: 1em;  
padding: 8px 16px;  
}  
   
.gre-entity-model-link a:hover {  
background-color: \#2E64FE;  
}  
   
/\* أنماط زر الحجز \*/  
.gre-entity-booking {  
text-align: center;  
margin-top: 15px;  
}  
   
.booking-btn {  
background-color: \#28a745;  
color: \#fff;  
padding: 10px 20px;  
border-radius: 6px;  
text-decoration: none;  
font-weight: bold;  
font-size: 1em;  
transition: background-color 0.3s ease;  
}  
   
/\* أنماط أرشيف الشقق \*/  
.gre-apartment-archive .apartment-card-container,  
.gre-model-archive .model-card-container {  
display: grid;  
grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));  
gap: 15px;  
margin-top: 25px;  
}  
   
.gre-apartment-archive .apartment-card,  
.gre-model-archive .model-card {  
background: \#fff;  
border: 1px solid \#ddd;  
border-radius: 6px;  
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);  
overflow: hidden;  
transition: transform 0.2s ease;  
}  
   
.gre-apartment-archive .apartment-card:hover,  
.gre-model-archive .model-card:hover {  
transform: translateY(-3px);  
}  
   
.gre-apartment-archive .apartment-thumb,  
.gre-model-archive .model-thumb {  
width: 100%;  
height: 150px;  
object-fit: cover;  
}  
   
.gre-apartment-archive .apartment-info,  
.gre-model-archive .model-info {  
padding: 10px;  
text-align: center;  
}  
   
.gre-apartment-archive .apartment-title,  
.gre-model-archive .model-title {  
font-size: 1.1em;  
margin-bottom: 8px;  
}  
   
.gre-apartment-archive .apartment-title a,  
.gre-model-archive .model-title a {  
color: \#333;  
text-decoration: none;  
}  
   
.gre-apartment-archive .apartment-title a:hover,  
.gre-model-archive .model-title a:hover {  
color: \#007bff;  
}  
   
.gre-apartment-archive .apartment-meta,  
.gre-model-archive .model-meta {  
font-size: 0.9em;  
color: \#777;  
margin-bottom: 4px;  
}  
   
.gre-apartment-archive .apartment-price,  
.gre-model-archive .model-price {  
font-weight: bold;  
color: \#28a745;  
margin-bottom: 8px;  
}  
   
.gre-apartment-archive .details-button,  
.gre-model-archive .details-button {  
display: inline-block;  
background-color: \#0073aa;  
color: \#fff;  
padding: 6px 12px;  
border-radius: 5px;  
text-decoration: none;  
transition: background-color 0.3s ease;  
font-size: 0.9em;  
}  
   
.gre-apartment-archive .details-button:hover,  
.gre-model-archive .details-button:hover {  
background-color: \#005f8d;  
}  
   
/\* أنماط نموذج الفلترة \*/  
.gre-entity-filter {  
margin-bottom: 25px;  
}  
   
.gre-entity-filter-form {  
background: \#f9f9f9;  
padding: 15px;  
margin-bottom: 25px;  
border-radius: 6px;  
box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);  
}  
   
.gre-entity-filter-form .filter-row {  
display: flex;  
flex-wrap: wrap;  
gap: 10px;  
align-items: flex-end;  
}  
   
.gre-entity-filter-form .filter-item {  
flex: 1 1 150px;  
}  
   
.gre-entity-filter-form label {  
display: block;  
margin-bottom: 3px;  
font-weight: bold;  
color: \#444;  
font-size: 0.9em;  
}  
   
.gre-entity-filter-form select,  
.gre-entity-filter-form input {  
width: 100%;  
padding: 4px 8px;  
border: 1px solid \#ccc;  
border-radius: 4px;  
font-size: 0.9em;  
}  
   
.gre-entity-filter-form button,  
.gre-entity-filter-form .reset-button {  
padding: 8px 16px;  
background: \#0073aa;  
color: white;  
border: none;  
border-radius: 5px;  
font-weight: bold;  
cursor: pointer;  
transition: background-color 0.2s;  
text-decoration: none;  
display: inline-block;  
font-size: 0.9em;  
}  
   
.gre-entity-filter-form button:hover,  
.gre-entity-filter-form .reset-button:hover {  
background: \#005f8d;  
}  
   
.gre-entity-filter-form .filter-submit {  
display: flex;  
gap: 8px;  
}  
   
/\* أنماط أرشيف الأبراج \*/  
.gre-tower-archive .entity-card-container {  
display: grid;  
grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));  
gap: 15px;  
margin-top: 25px;  
}  
   
.gre-tower-archive .entity-card {  
background: \#fff;  
border: 1px solid \#ddd;  
border-radius: 6px;  
box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);  
overflow: hidden;  
transition: transform 0.2s ease;  
}  
   
.gre-tower-archive .entity-card:hover {  
transform: translateY(-3px);  
}  
   
.gre-tower-archive .entity-thumb {  
width: 100%;  
height: 150px;  
object-fit: cover;  
}  
   
.gre-tower-archive .entity-info {  
padding: 15px;  
text-align: center;  
}  
   
.gre-tower-archive .entity-title {  
font-size: 1.1em;  
margin-bottom: 8px;  
color: \#333;  
}  
   
.gre-tower-archive .entity-title a {  
color: \#333;  
text-decoration: none;  
}  
   
.gre-tower-archive .entity-title a:hover {  
color: \#007bff;  
}  
   
.gre-tower-archive .entity-meta {  
font-size: 0.9em;  
color: \#777;  
margin-bottom: 4px;  
}  
   
.gre-tower-archive .details-button {  
display: inline-block;  
background-color: \#0073aa;  
color: \#fff;  
padding: 8px 16px;  
border-radius: 5px;  
text-decoration: none;  
transition: background-color 0.3s ease;  
font-size: 0.9em;  
}  
   
.gre-tower-archive .details-button:hover {  
background-color: \#005f8d;  
}  
   
.gre-entity-archive .page-title {  
text-align: center;  
font-size: 1.8em;  
margin-bottom: 20px;  
color: \#333;  
}  
//End of css  
   
   
   
\* public/templates/archive-gre\_apartment.php this file contains:  
\<?php  
/\*\*  
 \* قالب أرشيف الشقق \- archive-gre\_apartment.php  
 \*/  
get\_header();  
?\>  
   
\<main id="primary" class="site-main gre-entity-archive"\>  
	\<section class="gre-entity-filter"\>  
    	\<h1 class="page-title"\>قائمة الشقق السكنية\</h1\>  
    	\<form method="GET" class="gre-entity-filter-form"\>  
        	\<div class="filter-row"\>  
            	\<div class="filter-item"\>  
                	\<label for="status"\>الحالة:\</label\>  
                	\<select name="status" id="status"\>  
                    	\<option value=""\>الكل\</option\>  
                    	\<option value="available" \<?php echo selected($\_GET\['status'\] ?? '', 'available'); ?\>\>متاحة\</option\>  
                    	\<option value="sold" \<?php echo selected($\_GET\['status'\] ?? '', 'sold'); ?\>\>مباعة\</option\>  
                    	\<option value="under\_preparation" \<?php echo selected($\_GET\['status'\] ?? '', 'under\_preparation'); ?\>\>قيد التجهيز\</option\>  
                    	\<option value="for\_finishing" \<?php echo selected($\_GET\['status'\] ?? '', 'for\_finishing'); ?\>\>تحتاج تشطيب\</option\>  
                	\</select\>  
            	\</div\>  
   
            	\<div class="filter-item"\>  
                	\<label for="floor"\>الدور:\</label\>  
                	\<select name="floor" id="floor"\>  
                    	\<option value=""\>الكل\</option\>  
                    	\<?php  
                    	// تحسين استعلام الأدوار  
                    	global $wpdb;  
                    	$query \= "  
                        	SELECT DISTINCT meta\_value  
                        	FROM {$wpdb-\>postmeta}  
                        	WHERE meta\_key \= '\_gre\_apartment\_floor\_number'  
                        	ORDER BY CAST(meta\_value AS UNSIGNED)  
                    	";  
                    	$distinct\_floors \= $wpdb-\>get\_col($query);  
   
                    	if (\!empty($distinct\_floors)) {  
                        	foreach ($distinct\_floors as $floor\_num) {  
                            	if (\!is\_numeric($floor\_num)) continue;  
                            	$selected \= ($\_GET\['floor'\] ?? '') \== $floor\_num ? 'selected' : '';  
                            	echo "\<option value='" . esc\_attr($floor\_num) . "' $selected\>" . esc\_html($floor\_num) . "\</option\>";  
                        	}  
                    	}  
                    	?\>  
                	\</select\>  
            	\</div\>  
   
            	\<div class="filter-item"\>  
                	\<label for="price\_min"\>السعر من:\</label\>  
                	\<input type="number" name="price\_min" id="price\_min" value="\<?php echo esc\_attr($\_GET\['price\_min'\] ?? '') ?\>" placeholder="10000"\>  
            	\</div\>  
   
            	\<div class="filter-item"\>  
                	\<label for="price\_max"\>إلى:\</label\>  
                	\<input type="number" name="price\_max" id="price\_max" value="\<?php echo esc\_attr($\_GET\['price\_max'\] ?? '') ?\>" placeholder="50000"\>  
            	\</div\>  
   
            	\<div class="filter-item"\>  
                	\<label for="search\_query"\>بحث برقم الشقة أو النموذج:\</label\>  
                	\<input type="text" name="search\_query" id="search\_query" value="\<?php echo esc\_attr(sanitize\_text\_field($\_GET\['search\_query'\] ?? '')) ?\>" placeholder="مثلاً: A5-T3 أو M1"\>  
            	\</div\>  
   
            	\<div class="filter-item filter-submit"\>  
                	\<button type="submit"\>بحث\</button\>  
                	\<a href="\<?php echo esc\_url(get\_post\_type\_archive\_link('gre\_apartment')); ?\>" class="reset-button"\>إعادة تعيين\</a\>  
            	\</div\>  
        	\</div\>  
    	\</form\>  
	\</section\>  
   
	\<section class="gre-entity-grid gre-apartment-archive"\>  
    	\<?php  
    	$paged \= get\_query\_var('paged') ?: 1;  
    	$args \= \[  
        	'post\_type' \=\> 'gre\_apartment',  
        	'posts\_per\_page' \=\> 12,  
        	'paged' \=\> $paged,  
        	'meta\_query' \=\> \[\],  
    	\];  
   
    	$filters \= \[  
        	'status' \=\> \['value' \=\> sanitize\_text\_field($\_GET\['status'\] ?? '')\],  
        	'floor\_number' \=\> \['value' \=\> intval($\_GET\['floor'\] ?? 0), 'type' \=\> 'NUMERIC'\],  
        	'custom\_price\_usd' \=\> \['value' \=\> \[intval($\_GET\['price\_min'\] ?? 0), intval($\_GET\['price\_max'\] ?? 0)\], 'type' \=\> 'NUMERIC', 'compare' \=\> 'BETWEEN'\],  
    	\];  
    	$args\['meta\_query'\] \= gre\_build\_meta\_query($filters, 'apartment');  
   
    	// بحث نصي برقم الشقة أو اسم النموذج  
    	$search\_query \= sanitize\_text\_field($\_GET\['search\_query'\] ?? '');  
    	if (\!empty($search\_query)) {  
        	$args\['meta\_query'\]\['relation'\] \= 'OR';  
        	$args\['meta\_query'\]\[\] \= \[  
            	'key' \=\> '\_gre\_apartment\_apartment\_number',  
            	'value' \=\> sanitize\_text\_field($search\_query),  
            	'compare' \=\> 'LIKE',  
        	\];  
   
        	$model\_id \= gre\_get\_model\_id\_by\_title(sanitize\_text\_field($search\_query));  
        	if ($model\_id) {  
            	$args\['meta\_query'\]\[\] \= \[  
                	'key' \=\> '\_gre\_apartment\_model\_id',  
                	'value' \=\> $model\_id,  
                	'compare' \=\> '=',  
            	\];  
        	}  
    	}  
   
    	$apartments\_query \= new WP\_Query($args);  
   
    	if ($apartments\_query-\>have\_posts()) :  
        	echo '\<div class="apartment-card-container"\>';  
        	while ($apartments\_query-\>have\_posts()) : $apartments\_query-\>the\_post();  
            	$post\_id \= get\_the\_ID();  
            	$image \= get\_the\_post\_thumbnail\_url($post\_id, 'medium') ?: plugin\_dir\_url(\_\_FILE\_\_) . '../assets/img/default-apartment.jpg';  
            	$status \= get\_post\_meta($post\_id, '\_gre\_apartment\_status', true);  
            	$price \= get\_post\_meta($post\_id, '\_gre\_apartment\_custom\_price\_usd', true);  
            	$floor \= get\_post\_meta($post\_id, '\_gre\_apartment\_floor\_number', true);  
            	$apartment\_number \= get\_post\_meta($post\_id, '\_gre\_apartment\_apartment\_number', true);  
            	?\>  
            	\<div class="apartment-card"\>  
                	\<img src="\<?php echo esc\_url($image); ?\>" alt="\<?php echo esc\_attr(get\_the\_title()); ?\>" class="apartment-thumb"\>  
                	\<div class="apartment-info"\>  
                    	\<h2 class="apartment-title"\>\<a href="\<?php the\_permalink(); ?\>"\>\<?php the\_title(); ?\>\</a\>\</h2\>  
                    	\<p class="apartment-meta"\>رقم الشقة: \<?php echo esc\_html($apartment\_number); ?\>\</p\>  
                    	\<p class="apartment-meta"\>الدور: \<?php echo esc\_html($floor); ?\>\</p\>  
                    	\<p class="apartment-meta"\>الحالة: \<?php echo esc\_html(gre\_get\_apartment\_status\_label($status)); ?\>\</p\>  
                    	\<?php if ($price): ?\>  
                        	\<p class="apartment-price"\>السعر: \<?php echo esc\_html($price); ?\> $\</p\>  
                    	\<?php endif; ?\>  
                    	\<a href="\<?php the\_permalink(); ?\>" class="details-button"\>عرض التفاصيل\</a\>  
                	\</div\>  
            	\</div\>  
            	\<?php  
        	endwhile;  
        	echo '\</div\>';  
        	the\_posts\_pagination(\[  
            	'prev\_text' \=\> \_\_('← السابق', 'textdomain'),  
            	'next\_text' \=\> \_\_('التالي →', 'textdomain'),  
        	\]);  
    	else :  
        	echo '\<p\>لا توجد شقق متاحة حالياً.\</p\>';  
    	endif;  
   
    	wp\_reset\_postdata();  
    	?\>  
	\</section\>  
\</main\>  
   
\<?php get\_footer(); ?\>  
   
   
   
   
\* public/templates/archive-gre\_model.php this file contains:  
\<?php  
/\*\*  
 \* قالب أرشيف النماذج \- archive-gre\_model.php  
 \*/  
get\_header();  
?\>  
   
\<main id="primary" class="site-main gre-entity-archive"\>  
	\<section class="gre-entity-filter"\>  
    	\<h1 class="page-title"\>قائمة النماذج السكنية\</h1\>  
    	\<form method="GET" class="gre-entity-filter-form"\>  
        	\<div class="filter-row"\>  
   
            	\<div class="filter-item"\>  
                	\<label for="search\_query"\>بحث بالاسم أو الكود:\</label\>  
                	\<input type="text" name="search\_query" id="search\_query" value="\<?php echo esc\_attr($\_GET\['search\_query'\] ?? '') ?\>" placeholder="مثلاً: M1 أو اسم النموذج"\>  
            	\</div\>  
   
            	\<div class="filter-item"\>  
                	\<label for="rooms"\>عدد الغرف:\</label\>  
                	\<select name="rooms" id="rooms"\>  
                    	\<option value=""\>الكل\</option\>  
                    	\<?php for ($i \= 1; $i \<= 6; $i++): ?\>  
                        	\<option value="\<?php echo $i; ?\>" \<?php echo selected($\_GET\['rooms'\] ?? '', $i); ?\>\>\<?php echo $i; ?\> غرف\</option\>  
                    	\<?php endfor; ?\>  
                	\</select\>  
            	\</div\>  
   
            	\<div class="filter-item"\>  
                	\<label for="bathrooms"\>عدد الحمامات:\</label\>  
                	\<select name="bathrooms" id="bathrooms"\>  
                    	\<option value=""\>الكل\</option\>  
                    	\<?php for ($i \= 1; $i \<= 5; $i++): ?\>  
                        	\<option value="\<?php echo $i; ?\>" \<?php echo selected($\_GET\['bathrooms'\] ?? '', $i); ?\>\>\<?php echo $i; ?\> حمام\</option\>  
                    	\<?php endfor; ?\>  
                	\</select\>  
            	\</div\>  
   
            	\<div class="filter-item"\>  
                	\<label for="finishing\_type"\>نوع التشطيب:\</label\>  
                	\<input type="text" name="finishing\_type" id="finishing\_type" value="\<?php echo esc\_attr($\_GET\['finishing\_type'\] ?? '') ?\>" placeholder="مثلاً: سوبر لوكس"\>  
            	\</div\>  
   
            	\<div class="filter-item"\>  
                	\<label for="price\_min"\>السعر من:\</label\>  
                	\<input type="number" name="price\_min" id="price\_min" value="\<?php echo esc\_attr($\_GET\['price\_min'\] ?? '') ?\>" placeholder="10000"\>  
            	\</div\>  
   
            	\<div class="filter-item"\>  
                	\<label for="price\_max"\>إلى:\</label\>  
                	\<input type="number" name="price\_max" id="price\_max" value="\<?php echo esc\_attr($\_GET\['price\_max'\] ?? '') ?\>" placeholder="50000"\>  
            	\</div\>  
   
            	\<div class="filter-item filter-submit"\>  
                	\<button type="submit"\>بحث\</button\>  
                	\<a href="\<?php echo esc\_url(get\_post\_type\_archive\_link('gre\_model')); ?\>" class="reset-button"\>إعادة تعيين\</a\>  
            	\</div\>  
        	\</div\>  
    	\</form\>  
	\</section\>  
   
	\<section class="gre-entity-grid gre-model-archive"\> \<?php  
    	$paged \= get\_query\_var('paged') ?: 1;  
    	$args \= \[  
        	'post\_type' \=\> 'gre\_model',  
        	'posts\_per\_page' \=\> 12,  
        	'paged' \=\> $paged,  
        	'meta\_query' \=\> \[\],  
        	's' \=\> sanitize\_text\_field($\_GET\['search\_query'\] ?? ''),  
    	\];  
   
    	$filters \= \[  
        	'rooms\_count' \=\> \['value' \=\> intval($\_GET\['rooms'\] ?? 0), 'type' \=\> 'NUMERIC'\],  
        	'bathrooms\_count' \=\> \['value' \=\> intval($\_GET\['bathrooms'\] ?? 0), 'type' \=\> 'NUMERIC'\],  
        	'finishing\_type' \=\> \['value' \=\> sanitize\_text\_field($\_GET\['finishing\_type'\] ?? ''), 'compare' \=\> 'LIKE'\],  
        	'price\_usd' \=\> \['value' \=\> \[intval($\_GET\['price\_min'\] ?? 0), intval($\_GET\['price\_max'\] ?? 0)\], 'type' \=\> 'NUMERIC', 'compare' \=\> 'BETWEEN'\],  
    	\];  
   
    	$args\['meta\_query'\] \= gre\_build\_meta\_query($filters, 'model');  
   
    	$models\_query \= new WP\_Query($args);  
   
    	if ($models\_query-\>have\_posts()) :  
        	echo '\<div class="entity-card-container"\>';  
        	while ($models\_query-\>have\_posts()) : $models\_query-\>the\_post();  
            	$post\_id \= get\_the\_ID();  
            	$image \= get\_the\_post\_thumbnail\_url($post\_id, 'medium') ?: plugin\_dir\_url(\_\_FILE\_\_) . '../assets/img/default-model.jpg';  
            	$area \= get\_post\_meta($post\_id, '\_gre\_model\_area', true);  
            	$rooms \= get\_post\_meta($post\_id, '\_gre\_model\_rooms\_count', true);  
            	$baths \= get\_post\_meta($post\_id, '\_gre\_model\_bathrooms\_count', true);  
            	$price \= get\_post\_meta($post\_id, '\_gre\_model\_price\_usd', true);  
        	?\>  
            	\<div class="entity-card"\>  
                	\<img src="\<?php echo esc\_url($image); ?\>" alt="\<?php the\_title\_attribute(); ?\>" class="entity-thumb"\>  
                	\<div class="entity-info"\>  
                    	\<h2 class="entity-title"\>\<a href="\<?php the\_permalink(); ?\>"\>\<?php the\_title(); ?\>\</a\>\</h2\>  
                    	\<p class="entity-meta"\>المساحة: \<?php echo esc\_html($area); ?\> م²\</p\>  
                    	\<p class="entity-meta"\>الغرف: \<?php echo esc\_html($rooms); ?\> | الحمامات: \<?php echo esc\_html($baths); ?\>\</p\>  
                    	\<?php if ($price): ?\>  
                        	\<p class="entity-price"\>السعر: \<?php echo esc\_html($price); ?\> $\</p\>  
                    	\<?php endif; ?\>  
                    	\<a href="\<?php the\_permalink(); ?\>" class="details-button"\>عرض التفاصيل\</a\>  
                	\</div\>  
            	\</div\>  
        	\<?php  
        	endwhile;  
        	echo '\</div\>';  
        	wp\_reset\_postdata();  
        	the\_posts\_pagination(\[  
            	'prev\_text' \=\> \_\_('← السابق', 'textdomain'),  
            	'next\_text' \=\> \_\_('التالي →', 'textdomain'),  
        	\]);  
    	else :  
        	echo '\<p\>لا توجد نماذج مطابقة حالياً.\</p\>';  
    	endif;  
   
    	wp\_reset\_postdata();  
    	?\>  
	\</section\>  
\</main\>  
\<?php get\_footer(); ?\>  
   
   
 

\* public/templates/archive-gre\_tower.php this file contains:  
\<?php  
/\*\*  
 \* قالب أرشيف الأبراج \- archive-gre\_tower.php  
 \*/  
   
get\_header();  
?\>  
\<main id="primary" class="site-main gre-entity-archive"\>  
	\<section class="gre-entity-filter"\>  
    	\<h1 class="page-title"\>قائمة الأبراج السكنية\</h1\>  
    	\<form method="GET" class="gre-entity-filter-form"\>  
        	\<div class="filter-row"\>  
   
            	\<div class="filter-item"\>  
                	\<label for="search\_query"\>بحث بالاسم:\</label\>  
                	\<input type="text" name="search\_query" id="search\_query" value="\<?php echo esc\_attr($\_GET\['search\_query'\] ?? '') ?\>" placeholder="اسم البرج أو وصف"\>  
            	\</div\>  
   
            	\<div class="filter-item"\>  
                	\<label for="city"\>المدينة:\</label\>  
                	\<select name="city" id="city"\>  
                    	\<option value=""\>الكل\</option\>  
                    	\<option value="أمانة العاصمة" \<?php echo selected($\_GET\['city'\] ?? '', 'أمانة العاصمة'); ?\>\>أمانة العاصمة\</option\>  
                    	\<option value="عدن" \<?php echo selected($\_GET\['city'\] ?? '', 'عدن'); ?\>\>عدن\</option\>  
                    	\<option value="تعز" \<?php echo selected($\_GET\['city'\] ?? '', 'تعز'); ?\>\>تعز\</option\>  
                    	\<option value="الحديدة" \<?php echo selected($\_GET\['city'\] ?? '', 'الحديدة'); ?\>\>الحديدة\</option\>  
                    	\<option value="حضرموت" \<?php echo selected($\_GET\['city'\] ?? '', 'حضرموت'); ?\>\>حضرموت\</option\>  
                    	\<option value="إب" \<?php echo selected($\_GET\['city'\] ?? '', 'إب'); ?\>\>إب\</option\>  
                    	\<option value="ذمار" \<?php echo selected($\_GET\['city'\] ?? '', 'ذمار'); ?\>\>ذمار\</option\>  
                    	\<option value="عمران" \<?php echo selected($\_GET\['city'\] ?? '', 'عمران'); ?\>\>عمران\</option\>  
                    	\<option value="صنعاء" \<?php echo selected($\_GET\['city'\] ?? '', 'صنعاء'); ?\>\>صنعاء\</option\>  
                    	\<option value="مأرب" \<?php echo selected($\_GET\['city'\] ?? '', 'مأرب'); ?\>\>مأرب\</option\>  
                	\</select\>  
            	\</div\>  
   
            	\<div class="filter-item"\>  
                	\<label for="district"\>المديرية:\</label\>  
                	\<input type="text" name="district" id="district" value="\<?php echo esc\_attr($\_GET\['district'\] ?? '') ?\>" placeholder="مثلاً: التحرير"\>  
            	\</div\>  
   
            	\<div class="filter-item filter-submit"\>  
                	\<button type="submit"\>بحث\</button\>  
                	\<a href="\<?php echo esc\_url(get\_post\_type\_archive\_link('gre\_tower')); ?\>" class="reset-button"\>إعادة تعيين\</a\>  
            	\</div\>  
        	\</div\>  
    	\</form\>  
	\</section\>  
   
	\<section class="gre-entity-grid gre-tower-archive"\>  
    	\<?php  
    	$paged \= get\_query\_var('paged') ?: 1;  
    	$args \= \[  
        	'post\_type' \=\> 'gre\_tower',  
        	'posts\_per\_page' \=\> 12,  
        	'paged' \=\> $paged,  
        	'meta\_query' \=\> \[\],  
        	's' \=\> sanitize\_text\_field($\_GET\['search\_query'\] ?? ''),  
    	\];  
   
    	$filters \= \[  
        	'city' \=\> \['value' \=\> sanitize\_text\_field($\_GET\['city'\] ?? ''), 'compare' \=\> 'LIKE'\],  
        	'district' \=\> \['value' \=\> sanitize\_text\_field($\_GET\['district'\] ?? ''), 'compare' \=\> 'LIKE'\],  
    	\];  
   
    	$args\['meta\_query'\] \= gre\_build\_meta\_query($filters, 'tower');  
   
    	$towers\_query \= new WP\_Query($args);  
   
    	if ($towers\_query-\>have\_posts()) :  
        	echo '\<div class="entity-card-container"\>';  
        	while ($towers\_query-\>have\_posts()) : $towers\_query-\>the\_post();  
            	$post\_id \= get\_the\_ID();  
            	$image \= get\_the\_post\_thumbnail\_url($post\_id, 'medium') ?: plugin\_dir\_url(\_\_FILE\_\_) . '../assets/img/default-tower.jpg';  
            	$short\_name \= get\_post\_meta($post\_id, '\_gre\_tower\_short\_name', true);  
            	$city \= get\_post\_meta($post\_id, '\_gre\_tower\_city', true);  
            	$district \= get\_post\_meta($post\_id, '\_gre\_tower\_district', true);  
            	$floors \= get\_post\_meta($post\_id, '\_gre\_tower\_floors', true);  
            	$total\_units \= get\_post\_meta($post\_id, '\_gre\_tower\_total\_units', true);  
   
            	// Combine city and district for display  
            	$location \= \!empty($city) ? esc\_html($city) : '';  
            	if (\!empty($district)) {  
                	$location .= \!empty($location) ? ' \- ' . esc\_html($district) : esc\_html($district);  
            	}  
    	?\>  
            	\<div class="entity-card"\>  
                	\<img src="\<?php echo esc\_url($image); ?\>" alt="\<?php the\_title\_attribute(); ?\>" class="entity-thumb"\>  
                	\<div class="entity-info"\>  
                    	\<h2 class="entity-title"\>\<a href="\<?php the\_permalink(); ?\>"\>\<?php the\_title(); ?\>\</a\>\</h2\>  
                    	\<?php if ($short\_name): ?\>  
                        	\<p class="entity-meta"\>الاسم المختصر: \<?php echo esc\_html($short\_name); ?\>\</p\>  
                    	\<?php endif; ?\>  
   
                    	\<?php if ($location): ?\>  
                        	\<p class="entity-meta"\>الموقع: \<?php echo esc\_html($location); ?\>\</p\>  
                    	\<?php endif; ?\>  
   
                    	\<?php if ($floors): ?\>  
                        	\<p class="entity-meta"\>عدد الأدوار: \<?php echo esc\_html($floors); ?\>\</p\>  
                    	\<?php endif; ?\>  
   
                    	\<?php if ($total\_units): ?\>  
                        	\<p class="entity-meta"\>عدد الشقق الإجمالي: \<?php echo esc\_html($total\_units); ?\>\</p\>  
                    	\<?php endif; ?\>  
                    	\<a href="\<?php the\_permalink(); ?\>" class="details-button"\>عرض التفاصيل\</a\>  
                	\</div\>  
            	\</div\>  
    	\<?php  
        	endwhile;  
        	echo '\</div\>';  
        	wp\_reset\_postdata();  
        	the\_posts\_pagination(\[  
            	'prev\_text' \=\> \_\_('← السابق', 'textdomain'),  
            	'next\_text' \=\> \_\_('التالي →', 'textdomain'),  
        	\]);  
    	else :  
        	echo '\<p\>لا توجد أبراج مطابقة حالياً.\</p\>';  
    	endif;  
    	?\>  
	\</section\>  
\</main\>  
\<?php get\_footer(); ?\>  
   
\* public/templates/page-gre\_search.php this file contains:  
\<?php  
/\*\*  
 \* قالب صفحة البحث الموحد \- page-gre\_search.php  
 \*/  
get\_header();  
   
$q \= sanitize\_text\_field($\_GET\['q'\] ?? '');  
$type \= $\_GET\['type'\] ?? '';  
$city \= sanitize\_text\_field($\_GET\['city'\] ?? '');  
$price\_min \= intval($\_GET\['price\_min'\] ?? 0);  
$price\_max \= intval($\_GET\['price\_max'\] ?? 0);  
   
$post\_type \= $type ?: \['gre\_tower', 'gre\_model', 'gre\_apartment'\];  
   
$args \= \[  
	'post\_type' \=\> $post\_type,  
	'posts\_per\_page' \=\> 12,  
	'paged' \=\> get\_query\_var('paged') ?: 1,  
	's' \=\> $q,  
	'meta\_query' \=\> \[\],  
\];  
   
// فلترة المدينة  
if ($city) {  
	$args\['meta\_query'\]\[\] \= \[  
    	'key' \=\> $type \=== 'gre\_model' ? '\_gre\_model\_city' : '\_gre\_tower\_city',  
    	'value' \=\> $city,  
    	'compare' \=\> 'LIKE'  
	\];  
}  
// فلترة السعر للنماذج أو الشقق  
if ($price\_min && $price\_max) {  
	$args\['meta\_query'\]\[\] \= \[  
    	'key' \=\> $type \=== 'gre\_model' ? '\_gre\_model\_price\_usd' : '\_gre\_apartment\_custom\_price\_usd',  
    	'value' \=\> \[$price\_min, $price\_max\],  
    	'type' \=\> 'NUMERIC',  
    	'compare' \=\> 'BETWEEN'  
	\];  
}  
   
$query \= new WP\_Query($args);  
?\>   
\<main id="primary" class="site-main gre-entity-archive"\>  
	\<section class="gre-entity-filter"\>  
    	\<h1 class="page-title"\>البحث الموحد\</h1\>  
    	\<form method="get" class="gre-entity-filter-form"\>  
        	\<div class="filter-row"\>  
            	\<input type="text" name="q" placeholder="ابحث بالاسم أو الكود..." value="\<?php echo esc\_attr($q); ?\>"\>  
            	\<select name="type"\>  
                	\<option value=""\>الكل\</option\>  
                	\<option value="gre\_tower" \<?php selected($type, 'gre\_tower'); ?\>\>أبراج\</option\>  
                	\<option value="gre\_model" \<?php selected($type, 'gre\_model'); ?\>\>نماذج\</option\>  
                	\<option value="gre\_apartment" \<?php selected($type, 'gre\_apartment'); ?\>\>شقق\</option\>  
            	\</select\>  
            	\<select name="city"\>  
                	\<option value=""\>المدينة\</option\>  
                	\<option value="صنعاء" \<?php selected($city, 'صنعاء'); ?\>\>صنعاء\</option\>  
                	\<option value="عدن" \<?php selected($city, 'عدن'); ?\>\>عدن\</option\>  
            	\</select\>  
            	\<input type="number" name="price\_min" placeholder="السعر من" value="\<?php echo esc\_attr($price\_min); ?\>"\>  
            	\<input type="number" name="price\_max" placeholder="إلى" value="\<?php echo esc\_attr($price\_max); ?\>"\>  
            	\<button type="submit"\>بحث\</button\>  
        	\</div\>  
    	\</form\>  
	\</section\>  
   
	\<section class="gre-entity-grid"\>  
    	\<?php  
    	if ($query-\>have\_posts()) :  
        	echo '\<div class="entity-card-container"\>';  
        	while ($query-\>have\_posts()) : $query-\>the\_post();  
            	$post\_type \= get\_post\_type();  
            	$post\_id \= get\_the\_ID();  
            	$image \= get\_the\_post\_thumbnail\_url($post\_id, 'medium') ?: plugin\_dir\_url(\_\_FILE\_\_) . '../assets/img/default.png';  
    	?\>  
            	\<div class="entity-card"\>  
                	\<img src="\<?php echo esc\_url($image); ?\>" alt="\<?php the\_title\_attribute(); ?\>" class="entity-thumb"\>  
                	\<div class="entity-info"\>  
                    	\<h2 class="entity-title"\>\<a href="\<?php the\_permalink(); ?\>"\>\<?php the\_title(); ?\>\</a\>\</h2\>  
                    	\<p class="entity-meta"\>النوع: \<?php echo ($post\_type \== 'gre\_tower' ? 'برج' : ($post\_type \== 'gre\_model' ? 'نموذج' : 'شقة')); ?\>\</p\>  
                    	\<a href="\<?php the\_permalink(); ?\>" class="details-button"\>عرض التفاصيل\</a\>  
                	\</div\>  
            	\</div\>  
    	\<?php  
        	endwhile;  
        	echo '\</div\>';  
        	the\_posts\_pagination(\[  
            	'prev\_text' \=\> \_\_('← السابق'),  
            	'next\_text' \=\> \_\_('التالي →'),  
        	\]);  
    	else :  
        	echo '\<p\>لا توجد نتائج مطابقة حالياً.\</p\>';  
    	endif;  
    	wp\_reset\_postdata();  
    	?\>  
	\</section\>  
\</main\>  
\<?php get\_footer(); ?\>  
   
   
 

 \* public/templates/single-gre\_apartment.php this file contains:  
\<?php  
/\*\*  
 \* قالب عرض تفاصيل الشقة \- single-gre\_apartment.php  
 \*/  
   
get\_header();  
   
if (have\_posts()) :  
	while (have\_posts()) : the\_post();  
    	$post\_id \= get\_the\_ID();  
   
    	// صورة الشقة (إذا كانت موجودة)  
    	$image\_url \= get\_the\_post\_thumbnail\_url($post\_id, 'large');  
    	$model\_id \= get\_post\_meta($post\_id, '\_gre\_apartment\_model\_id', true);  
    	$status \= get\_post\_meta($post\_id, '\_gre\_apartment\_status', true);  
    	$custom\_price\_usd \= get\_post\_meta($post\_id, '\_gre\_apartment\_custom\_price\_usd', true);  
    	$custom\_images \= get\_post\_meta($post\_id, '\_gre\_apartment\_custom\_images', true);  
?\>  
    	\<main id="primary" class="site-main"\>  
        	\<article id="post-\<?php the\_ID(); ?\>" \<?php post\_class('gre-entity-single'); ?\>\>  
            	\<header class="entry-header"\>  
                	\<h1 class="entry-title"\>\<?php the\_title(); ?\>\</h1\>  
                	\<?php if ($image\_url) : ?\>  
                    	\<img class="gre-entity-thumb" src="\<?php echo esc\_url($image\_url); ?\>" alt="\<?php the\_title\_attribute(); ?\>"\>  
                	\<?php endif; ?\>  
            	\</header\>  
   
            	\<section class="gre-entity-details"\>  
                	\<h2\>تفاصيل الشقة\</h2\>  
                	\<?php gre\_render\_entity\_details($post\_id, 'apartment'); ?\>  
            	\</section\>  
   
            	\<?php if ($status) : ?\>  
                	\<section class="gre-entity-status"\>  
                    	\<h2\>الحالة\</h2\>  
                    	\<p\>\<?php echo esc\_html(gre\_get\_apartment\_status\_label($status)); ?\>\</p\>  
                	\</section\>  
            	\<?php endif; ?\>  
   
            	\<?php if ($custom\_price\_usd) : ?\>  
                	\<section class="gre-entity-price"\>  
                    	\<h2\>السعر المخصص\</h2\>  
                    	\<p\>\<?php echo esc\_html($custom\_price\_usd); ?\> دولار أمريكي\</p\>  
                	\</section\>  
            	\<?php endif; ?\>  
   
            	\<?php if ($custom\_images) : ?\>  
                	\<section class="gre-entity-images"\>  
                    	\<h2\>صور إضافية\</h2\>  
                    	\<div class="gre-entity-images-gallery"\>  
                        	\<?php  
                        	$image\_ids \= explode(',', $custom\_images);  
                        	foreach ($image\_ids as $image\_id) {  
                            	$image\_url \= wp\_get\_attachment\_url(trim($image\_id));  
                            	if ($image\_url) {  
                                	echo '\<img src="' . esc\_url($image\_url) . '" alt="صورة شقة" loading="lazy"\>';  
                            	}  
                        	}  
                        	?\>  
                    	\</div\>  
                	\</section\>  
            	\<?php endif; ?\>  
   
            	\<?php if ($status \=== 'available') : ?\>  
                	\<section class="gre-entity-booking"\>  
                    	\<h2\>حجز الشقة\</h2\>  
                    	\<a href="https://wa.me/967XXXXXXXXX?text=مرحباً، أرغب بحجز الشقة رقم \<?php echo get\_post\_meta($post\_id, '\_gre\_apartment\_apartment\_number', true); ?\>" class="booking-btn" target="\_blank"\>احجز الآن عبر واتساب\</a\>  
                	\</section\>  
            	\<?php endif; ?\>  
   
            	\<?php if ($model\_id) : ?\>  
                	\<section class="gre-entity-model-link"\>  
                    	\<h2\>النموذج المرتبط\</h2\>  
                    	\<a href="\<?php echo esc\_url(get\_permalink($model\_id)); ?\>"\>عرض تفاصيل النموذج\</a\>  
                	\</section\>  
            	\<?php endif; ?\>  
        	\</article\>  
    	\</main\>  
\<?php  
	endwhile;  
endif;  
   
get\_sidebar();  
get\_footer();  
?\>  
   
   
   
 

 \* public/templates/single-gre\_model.php this file contains:  
\<?php  
/\*\*  
 \* قالب عرض تفاصيل النموذج \- single-gre\_model.php  
 \*/  
   
get\_header();  
if (have\_posts()) :  
	while (have\_posts()) : the\_post();  
    	$post\_id \= get\_the\_ID();  
    	// بيانات مخصصة  
    	$image\_url \= get\_the\_post\_thumbnail\_url($post\_id, 'large');  
    	$description \= get\_post\_meta($post\_id, '\_gre\_model\_description', true);  
    	$price\_usd \= get\_post\_meta($post\_id, '\_gre\_model\_price\_usd', true);  
?\>  
    	\<main id="primary" class="site-main"\>  
        	\<article id="post-\<?php the\_ID(); ?\>" \<?php post\_class('gre-entity-single'); ?\>\>  
            	\<header class="entry-header"\>  
                	\<h1 class="entry-title"\>\<?php the\_title(); ?\>\</h1\>  
                	\<?php if ($image\_url) : ?\>  
                    	\<img class="gre-entity-thumb" src="\<?php echo esc\_url($image\_url); ?\>" alt="\<?php the\_title\_attribute(); ?\>"\>  
                	\<?php endif; ?\>  
            	\</header\>  
   
            	\<section class="gre-entity-details"\>  
                	\<h2\>تفاصيل النموذج\</h2\>  
                	\<?php gre\_render\_entity\_details($post\_id, 'model'); ?\>  
            	\</section\>  
   
            	\<?php if ($description) : ?\>  
                	\<section class="gre-entity-description"\>  
                    	\<h2\>الوصف\</h2\>  
                    	\<p\>\<?php echo esc\_html($description); ?\>\</p\>  
                	\</section\>  
            	\<?php endif; ?\>  
   
            	\<?php if ($price\_usd) : ?\>  
                	\<section class="gre-entity-price"\>  
                    	\<h2\>السعر\</h2\>  
                    	\<p\>\<?php echo esc\_html($price\_usd); ?\> دولار أمريكي\</p\>  
                	\</section\>  
            	\<?php endif; ?\>  
   
            	\<section class="gre-related-apartments"\>  
                	\<h2\>الشقق التابعة لهذا النموذج\</h2\>  
                	\<ul class="apartment-list"\>  
                    	\<?php  
                    	$args \= \[  
                        	'post\_type' \=\> 'gre\_apartment',  
                        	'meta\_query' \=\> \[  
                            	\[  
                                	'key' \=\> '\_gre\_apartment\_model\_id',  
                                	'value' \=\> $post\_id,  
                                	'compare' \=\> '=',  
                            	\],  
                        	\],  
                        	'posts\_per\_page' \=\> \-1,  
                    	\];  
                    	$apartments \= get\_posts($args);  
                    	foreach ($apartments as $apt) {  
                        	echo '\<li\>\<a href="' . get\_permalink($apt-\>ID) . '"\>' . esc\_html(get\_the\_title($apt-\>ID)) . '\</a\>\</li\>';  
                    	}  
                    	?\>  
                	\</ul\>  
            	\</section\>  
        	\</article\>  
    	\</main\>  
\<?php  
	endwhile;  
endif;  
 get\_sidebar();  
get\_footer();  
?\>  
   
 

 \* public/templates/single-gre\_tower.php this file contains:  
\<?php  
/\*\*  
 \* قالب عرض تفاصيل البرج \- single-gre\_tower.php  
 \*/  
   
get\_header(); // ترويسة القالب  
   
if (have\_posts()) :  
	while (have\_posts()) : the\_post();  
    	$post\_id \= get\_the\_ID();  
     	// بيانات مخصصة  
    	$short\_name \= get\_post\_meta($post\_id, '\_gre\_tower\_short\_name', true);  
    	$floors \= get\_post\_meta($post\_id, '\_gre\_tower\_floors', true);  
    	$city \= get\_post\_meta($post\_id, '\_gre\_tower\_city', true);  
    	$district \= get\_post\_meta($post\_id, '\_gre\_tower\_district', true);  
    	$lat \= get\_post\_meta($post\_id, '\_gre\_tower\_location\_lat', true);  
    	$lng \= get\_post\_meta($post\_id, '\_gre\_tower\_location\_lng', true);  
    	$desc \= get\_post\_meta($post\_id, '\_gre\_tower\_general\_description', true);  
    	$image\_url \= get\_the\_post\_thumbnail\_url($post\_id, 'large');  
?\>  
    	\<main id="primary" class="site-main"\>  
        	\<article id="post-\<?php the\_ID(); ?\>" \<?php post\_class('gre-entity-single'); ?\>\>  
            	\<header class="entry-header tower-header"\>  
                	\<h1 class="tower-title"\>\<?php the\_title(); ?\>\</h1\>  
                	\<div class="breadcrumb"\>  
                    	\<?php if (function\_exists('yoast\_breadcrumb')) {  
                        	yoast\_breadcrumb();  
                    	} ?\>  
                	\</div\>  
                	\<?php if ($image\_url) : ?\>  
                    	\<img src="\<?php echo esc\_url($image\_url); ?\>" alt="\<?php the\_title\_attribute(); ?\>" class="tower-image"\>  
                	\<?php endif; ?\>  
            	\</header\>  
   
            	\<section class="tower-info"\>  
                	\<h2\>معلومات أساسية\</h2\>  
                	\<div class="info-grid"\>  
                    	\<div class="info-item"\>\<i class="dashicons dashicons-tag"\>\</i\> \<span\>الاسم المختصر:\</span\> \<?php echo esc\_html($short\_name); ?\>\</div\>  
                    	\<div class="info-item"\>\<i class="dashicons dashicons-editor-ol"\>\</i\> \<span\>عدد الأدوار:\</span\> \<?php echo esc\_html($floors); ?\>\</div\>  
                    	\<div class="info-item"\>\<i class="dashicons dashicons-location-alt"\>\</i\> \<span\>الموقع:\</span\> \<?php echo esc\_html($city . ' \- ' . $district); ?\>\</div\>  
                    	\<?php if ($lat && $lng): ?\>  
                        	\<div class="info-item"\>\<i class="dashicons dashicons-admin-map"\>\</i\> \<span\>الإحداثيات:\</span\> \<?php echo esc\_html($lat . ', ' . $lng); ?\>\</div\>  
                    	\<?php endif; ?\>  
                	\</div\>  
            	\</section\>  
   
            	\<?php if ($desc) : ?\>  
                	\<section class="tower-description"\>  
                    	\<h2\>الوصف العام\</h2\>  
                    	\<p\>\<?php echo esc\_html($desc); ?\>\</p\>  
                	\</section\>  
            	\<?php endif; ?\>  
   
            	\<?php if ($lat && $lng) : ?\>  
                	\<section class="tower-map"\>  
                    	\<h2\>الموقع على الخريطة\</h2\>  
                    	\<div id="map" class="map"\>\</div\>  
                    	\<script\>  
                            document.addEventListener("DOMContentLoaded", function () {  
                            	var map \= L.map('map').setView(\[\<?php echo esc\_js($lat); ?\>, \<?php echo esc\_js($lng); ?\>\], 16);  
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);  
                                L.marker(\[\<?php echo esc\_js($lat); ?\>, \<?php echo esc\_js($lng); ?\>\]).addTo(map);  
                        	});  
                    	\</script\>  
                	\</section\>  
            	\<?php endif; ?\>  
   
            	\<section class="tower-models"\>  
                	\<h2\>النماذج التابعة\</h2\>  
                	\<div class="model-list"\>  
                    	\<?php  
                    	$models \= get\_posts(\['post\_type' \=\> 'gre\_model', 'meta\_query' \=\> \[\['key' \=\> '\_gre\_model\_tower\_id', 'value' \=\> $post\_id\]\]\]);  
                    	if ($models) :  
                        	foreach ($models as $model) :  
                    	?\>  
                            	\<div class="model-item"\>  
                                	\<a href="\<?php echo esc\_url(get\_permalink($model-\>ID)); ?\>"\>\<?php echo esc\_html(get\_the\_title($model-\>ID)); ?\>\</a\>  
                            	\</div\>  
                    	\<?php  
                        	endforeach;  
                    	else :  
                        	echo '\<p\>لا توجد نماذج تابعة لهذا البرج.\</p\>';  
                    	endif;  
                    	?\>  
                	\</div\>  
            	\</section\>  
   
            	\<section class="tower-booking"\>  
                	\<a href="\#" class="booking-button"\>تواصل معنا للحجز\</a\>  
            	\</section\>  
        	\</article\>  
    	\</main\>  
<?php  
	endwhile;  
endif;  
   
get\_sidebar(); // الشريط الجانبي (إذا كان موجودًا)  
get\_footer(); // تذييل القالب  
?\>  
   
   
   
   
   
