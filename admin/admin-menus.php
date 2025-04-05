<?php
// قوائم لوحة التحكم وإعدادات لوحة الإدارة

// تحميل مكتبة OpenStreetMap (Leaflet)
function gre_enqueue_leaflet_scripts($hook) {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'gre_tower') {
        wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
        wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');
    }
}
add_action('admin_enqueue_scripts', 'gre_enqueue_leaflet_scripts');

// إعداد قوائم مخصصة للإضافة
function gre_add_admin_menus() {
    // مستقبلاً يمكننا إضافة صفحات فرعية مخصصة هنا
    // add_submenu_page(...);
}
add_action('admin_menu', 'gre_add_admin_menus');