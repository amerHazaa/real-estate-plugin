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
            'labels' => [
                'name'               => 'الشقق',
                'singular_name'      => 'شقة',
                'add_new'            => 'إضافة شقة جديدة',
                'add_new_item'       => 'إضافة شقة جديدة',
                'edit_item'          => 'تعديل شقة',
                'new_item'           => 'شقة جديدة',
                'view_item'          => 'عرض الشقة',
                'view_items'         => 'عرض الشقق',
                'search_items'       => 'البحث في الشقق',
                'not_found'          => 'لم يتم العثور على شقق',
                'not_found_in_trash' => 'لم يتم العثور على شقق في سلة المهملات',
                'parent_item_colon'  => 'الشقة الأب:',
                'all_items'          => 'كل الشقق',
                'archives'           => 'أرشيف الشقق',
                'attributes'         => 'خصائص الشقة',
                'insert_into_item'   => 'إدراج في الشقة',
                'uploaded_to_this_item' => 'تم الرفع إلى هذه الشقة',
                'featured_image'     => 'صورة الشقة البارزة',
                'set_featured_image' => 'تعيين الصورة البارزة للشقة',
                'remove_featured_image' => 'إزالة الصورة البارزة للشقة',
                'use_featured_image' => 'استخدام كصورة بارزة للشقة',
                'menu_name'          => 'الشقق',
                'filter_items_list' => 'فلترة قائمة الشقق',
                'items_list_navigation' => 'قائمة تصفح الشقق',
                'items_list'         => 'قائمة الشقق',
                'item_published'     => 'تم نشر الشقة',
                'item_published_privately' => 'تم نشر الشقة خاص',
                'item_reverted_to_draft' => 'تمت إعادة الشقة إلى مسودة',
                'item_scheduled'     => 'تم جدولة الشقة',
                'item_updated'       => 'تم تحديث الشقة',
            ],
            'public'             => true,
            'supports'           => ['title', 'thumbnail'],
            'menu_position'      => 7,
            'menu_icon'          => 'dashicons-admin-home',
            'has_archive'        => true,
            'rewrite'            => ['slug' => 'apartments'],
        ]);
    }
}
?>