<?php
/**
 * Plugin Name: Real Estate Manager
 * Plugin URI: https://example.com
 * Description: إضافة ووردبريس لإدارة الأبراج السكنية والشقق داخل الموقع العقاري باستخدام نمط Wizard تفاعلي.
 * Version: 1.0.0
 * Author: Amer Al-Muhammadi
 * Author URI: https://example.com
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// استدعاء ملفات الإضافة
require_once plugin_dir_path(__FILE__) . 'includes/class-real-estate-manager.php';

// تهيئة الإضافة
function real_estate_manager_init() {
    $realEstateManager = new Real_Estate_Manager();
}
add_action('plugins_loaded', 'real_estate_manager_init');