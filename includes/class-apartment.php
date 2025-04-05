<?php
class GRE_Apartment {
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        // هذا السطر كان ناقص ويجب إضافته لتحميل حقول الشقة
        require_once plugin_dir_path(__FILE__) . '/../admin/apartment-meta-boxes.php';
    }

    public function register_post_type() {
        register_post_type('gre_apartment', [
            'label' => 'الشقق',
            'public' => true,
            'supports' => ['title', 'thumbnail'], // إبقاء فقط العنوان والصورة، وإزالة المحرر
            'menu_position' => 7,
            'menu_icon' => 'dashicons-admin-home',
            'has_archive' => true,
            'rewrite' => ['slug' => 'apartments'],
        ]);
    }
}
