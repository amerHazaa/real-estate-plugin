<?php
// قوائم لوحة التحكم وإعدادات لوحة الإدارة

// تحميل مكتبة OpenStreetMap (Leaflet) لحقول الموقع الجغرافي
add_action('admin_enqueue_scripts', function($hook) {
    global $post_type;

    // فقط داخل شاشة إنشاء أو تحرير برج
    if ($post_type === 'gre_tower') {
        wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
        wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');
    }
});

// إعداد قوائم مخصصة للإضافة (سيتم استخدامها لاحقاً)
add_action('admin_menu', function() {
    // مستقبلاً يمكننا إضافة صفحات مخصصة هنا مثل:
    // add_submenu_page(...);
});
