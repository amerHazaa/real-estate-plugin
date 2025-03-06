<?php
/*
Plugin Name: Real Estate Manager
Description: إضافة مصممة لإدارة العقارات بما في ذلك الأبراج والشقق، وتسهيل إدارتها عبر واجهة مستخدم متطورة ومميزات متقدمة.
Version: 1.1 
Author: AmerHazaa
Text Domain: real-estate-manager
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// تعريف مسار الإضافة
define( 'REM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// تضمين الملفات الضرورية
require_once REM_PLUGIN_DIR . 'real-estate-admin.php';
require_once REM_PLUGIN_DIR . 'tower-management.php';
require_once REM_PLUGIN_DIR . 'apartment-management.php';
require_once REM_PLUGIN_DIR . 'class-real-estate-manager.php';
require_once REM_PLUGIN_DIR . 'class-tower.php';
require_once REM_PLUGIN_DIR . 'class-apartment.php';

/*===============================================
=         تسجيل الأنواع المخصصة للمحتوى        =
===============================================*/
function rem_register_custom_post_types() {
    // تسجيل نوع "البرج"
    $labels_tower = array(
        'name'                  => 'الأبراج',
        'singular_name'         => 'برج',
        'add_new'               => 'أضف برج',
        'add_new_item'          => 'أضف برج جديد',
        'edit_item'             => 'تعديل البرج',
        'new_item'              => 'برج جديد',
        'view_item'             => 'عرض البرج',
        'search_items'          => 'بحث في الأبراج',
        'not_found'             => 'لم يتم العثور على أبراج',
    );
    $args_tower = array(
        'labels'                => $labels_tower,
        'public'                => true,
        'has_archive'           => true,
        'rewrite'               => array('slug' => 'towers'),
        'supports'              => array('title', 'editor', 'thumbnail'),
    );
    register_post_type('tower', $args_tower);

    // تسجيل نوع "الشقة"
    $labels_apartment = array(
        'name'                  => 'الشقق',
        'singular_name'         => 'شقة',
        'add_new'               => 'أضف شقة',
        'add_new_item'          => 'أضف شقة جديدة',
        'edit_item'             => 'تعديل الشقة',
        'new_item'              => 'شقة جديدة',
        'view_item'             => 'عرض الشقة',
        'search_items'          => 'بحث في الشقق',
        'not_found'             => 'لم يتم العثور على شقق',
    );
    $args_apartment = array(
        'labels'                => $labels_apartment,
        'public'                => true,
        'has_archive'           => true,
        'rewrite'               => array('slug' => 'apartments'),
        'supports'              => array('title', 'editor', 'thumbnail'),
    );
    register_post_type('apartment', $args_apartment);
}
add_action('init', 'rem_register_custom_post_types');

/*===============================================
=         تسجيل شورت كود العرض الأمامي         =
===============================================*/
// عرض الأبراج على الواجهة الأمامية
function rem_display_towers() {
    $args = array(
        'post_type'      => 'tower',
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);
    $output = '<div class="towers-list">';
    while($query->have_posts()) : $query->the_post();
        $output .= '<div class="tower-item">';
        $output .= '<h2>' . get_the_title() . '</h2>';
        $output .= get_the_post_thumbnail(get_the_ID(), 'medium');
        $output .= '<p>' . get_the_excerpt() . '</p>';
        $output .= '</div>';
    endwhile;
    $output .= '</div>';
    wp_reset_postdata();
    return $output;
}
add_shortcode('rem_towers', 'rem_display_towers');

// عرض الشقق على الواجهة الأمامية
function rem_display_apartments() {
    $args = array(
        'post_type'      => 'apartment',
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);
    $output = '<div class="apartments-list">';
    while($query->have_posts()) : $query->the_post();
        $output .= '<div class="apartment-item">';
        $output .= '<h2>' . get_the_title() . '</h2>';
        $output .= get_the_post_thumbnail(get_the_ID(), 'medium');
        $output .= '<p>' . get_the_excerpt() . '</p>';
        $output .= '</div>';
    endwhile;
    $output .= '</div>';
    wp_reset_postdata();
    return $output;
}
add_shortcode('rem_apartments', 'rem_display_apartments');

/*===============================================
=      تحميل استايلات وسكربتات لوحة الإدارة       =
===============================================*/
function rem_enqueue_admin_assets($hook) {
    // تحميل الاستايلات والسكربتات في صفحات تحرير المنشورات فقط
    if ( $hook != 'post.php' && $hook != 'post-new.php' ) {
        return;
    }
    wp_enqueue_style('rem-admin-styles', plugin_dir_url(__FILE__) . 'admin-styles.css');
    wp_enqueue_script('rem-admin-scripts', plugin_dir_url(__FILE__) . 'admin-scripts.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'rem_enqueue_admin_assets');

/*===============================================
=           تحميل استايلات الواجهة الأمامية         =
===============================================*/
function rem_enqueue_frontend_assets() {
    wp_enqueue_style('rem-frontend-styles', plugin_dir_url(__FILE__) . 'styles.css');
}
add_action('wp_enqueue_scripts', 'rem_enqueue_frontend_assets');

/*===============================================
=       مثال على استخدام AJAX لفلترة الأبراج       =
===============================================*/
function rem_ajax_filter_towers() {
    $filter = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : '';
    $args = array(
        'post_type'      => 'tower',
        'posts_per_page' => -1,
    );
    if ( ! empty( $filter ) ) {
        $args['s'] = $filter;
    }
    $query = new WP_Query($args);
    $results = array();
    while ( $query->have_posts() ) {
        $query->the_post();
        $results[] = array(
            'title'     => get_the_title(),
            'permalink' => get_permalink(),
            'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'medium')
        );
    }
    wp_reset_postdata();
    wp_send_json_success($results);
}
add_action('wp_ajax_rem_filter_towers', 'rem_ajax_filter_towers');
add_action('wp_ajax_nopriv_rem_filter_towers', 'rem_ajax_filter_towers');
