<?php
// تمكين تسجيل الأخطاء في debug.log
ini_set('log_errors', 1);
ini_set('error_log', WP_CONTENT_DIR . '/debug.log');

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);

// وظيفة تسجيل الأخطاء المخصصة
function log_real_estate_error($message) {
    if (WP_DEBUG_LOG) {
        error_log('[RealEstatePlugin] ' . $message);
    }
}

// مثال على استخدام الدالة في مكان تسجيل البيانات
function get_apartments($tower_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'apartments';
    
    if (!is_numeric($tower_id)) {
        log_real_estate_error("تم تمرير معرف برج غير صالح: " . $tower_id);
        return [];
    }
    
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE tower_id = %d", $tower_id));
    
    if ($results === false) {
        log_real_estate_error("فشل في جلب الشقق من قاعدة البيانات لبرج: " . $tower_id);
    }
    
    return $results;
}
