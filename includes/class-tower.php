<?php
class GRE_Tower {
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('edit_form_after_title', [$this, 'add_media_button_above_fields']);
        require_once plugin_dir_path(__FILE__) . '/../admin/tower-meta-boxes.php';
    }

    public function register_post_type() {
        register_post_type('gre_tower', [
            'label' => 'الأبراج',
            'labels' => [
                'name'               => 'الأبراج',
                'singular_name'      => 'برج',
                'add_new'            => 'إضافة برج جديد',
                'add_new_item'       => 'إضافة برج جديد',
                'edit_item'          => 'تعديل برج',
                'new_item'           => 'برج جديد',
                'view_item'          => 'عرض البرج',
                'view_items'         => 'عرض الأبراج',
                'search_items'       => 'البحث في الأبراج',
                'not_found'          => 'لم يتم العثور على أبراج',
                'not_found_in_trash' => 'لم يتم العثور على أبراج في سلة المهملات',
                'parent_item_colon'  => 'البرج الأب:',
                'all_items'          => 'كل الأبراج',
                'archives'           => 'أرشيف الأبراج',
                'attributes'         => 'خصائص البرج',
                'insert_into_item'   => 'إدراج في البرج',
                'uploaded_to_this_item' => 'تم الرفع إلى هذا البرج',
                'featured_image'     => 'صورة البرج البارزة',
                'set_featured_image' => 'تعيين الصورة البارزة للبرج',
                'remove_featured_image' => 'إزالة الصورة البارزة للبرج',
                'use_featured_image' => 'استخدام كصورة بارزة للبرج',
                'menu_name'          => 'الأبراج',
                'filter_items_list' => 'فلترة قائمة الأبراج',
                'items_list_navigation' => 'قائمة تصفح الأبراج',
                'items_list'         => 'قائمة الأبراج',
                'item_published'     => 'تم نشر البرج',
                'item_published_privately' => 'تم نشر البرج خاص',
                'item_reverted_to_draft' => 'تمت إعادة البرج إلى مسودة',
                'item_scheduled'     => 'تم جدولة البرج',
                'item_updated'       => 'تم تحديث البرج',
            ],
            'public'             => true,
            'supports'           => ['title', 'thumbnail'],
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-building',
            'has_archive'        => true,
            'rewrite'            => ['slug' => 'towers'],
        ]);
    }

    public function add_media_button_above_fields($post) {
        if ($post->post_type === 'gre_tower') {
            echo '<div style="margin: 10px 0;">';
            do_action('media_buttons', $post->ID);
            echo '</div>';
        }
    }
}
?>