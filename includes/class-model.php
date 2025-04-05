<?php
class GRE_Model {
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        require_once plugin_dir_path(__FILE__) . '/../admin/model-meta-boxes.php';
    }

    public function register_post_type() {
        register_post_type('gre_model', [
            'label' => 'النماذج',
            'labels' => [
                'name'               => 'النماذج',
                'singular_name'      => 'نموذج',
                'add_new'            => 'إضافة نموذج جديد',
                'add_new_item'       => 'إضافة نموذج جديد',
                'edit_item'          => 'تعديل نموذج',
                'new_item'           => 'نموذج جديد',
                'view_item'          => 'عرض النموذج',
                'view_items'         => 'عرض النماذج',
                'search_items'       => 'البحث في النماذج',
                'not_found'          => 'لم يتم العثور على نماذج',
                'not_found_in_trash' => 'لم يتم العثور على نماذج في سلة المهملات',
                'parent_item_colon'  => 'النموذج الأب:',
                'all_items'          => 'كل النماذج',
                'archives'           => 'أرشيف النماذج',
                'attributes'         => 'خصائص النموذج',
                'insert_into_item'   => 'إدراج في النموذج',
                'uploaded_to_this_item' => 'تم الرفع إلى هذا النموذج',
                'featured_image'     => 'صورة النموذج البارزة',
                'set_featured_image' => 'تعيين الصورة البارزة للنموذج',
                'remove_featured_image' => 'إزالة الصورة البارزة للنموذج',
                'use_featured_image' => 'استخدام كصورة بارزة للنموذج',
                'menu_name'          => 'النماذج',
                'filter_items_list' => 'فلترة قائمة النماذج',
                'items_list_navigation' => 'قائمة تصفح النماذج',
                'items_list'         => 'قائمة النماذج',
                'item_published'     => 'تم نشر النموذج',
                'item_published_privately' => 'تم نشر النموذج خاص',
                'item_reverted_to_draft' => 'تمت إعادة النموذج إلى مسودة',
                'item_scheduled'     => 'تم جدولة النموذج',
                'item_updated'       => 'تم تحديث النموذج',
            ],
            'public'             => true,
            'supports'           => ['title', 'thumbnail'],
            'menu_position'      => 6,
            'menu_icon'          => 'dashicons-layout',
            'has_archive'        => false,
            'rewrite'            => ['slug' => 'models'],
            'show_in_rest'       => false,
        ]);
    }
}
?>