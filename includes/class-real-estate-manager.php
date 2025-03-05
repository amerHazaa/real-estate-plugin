<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Real_Estate_Manager {

    public function __construct() {
        // تسجيل أنواع المنشورات المخصصة
        add_action('init', [$this, 'register_custom_post_types']);

        // إضافة الميتا بوكس للتفاصيل
        add_action('add_meta_boxes', [$this, 'add_custom_meta_boxes']);

        // حفظ البيانات في قاعدة البيانات
        add_action('save_post', [$this, 'save_custom_meta_data']);

        // أكواد قصيرة
        add_shortcode('display_towers', [$this, 'display_towers_shortcode']);
        add_shortcode('display_apartments', [$this, 'display_apartments_shortcode']);

        // تحميل الملفات المصاحبة (CSS/JS)
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_public_scripts']);

        // إضافة صفحات مخصصة للإدارة
        add_action('admin_menu', [$this, 'add_admin_pages']);
    }

    /**
     * تسجيل أنواع المنشورات المخصصة (الأبراج - الشقق)
     */
    public function register_custom_post_types() {
        register_post_type('tower', [
            'labels' => [
                'name'          => 'الأبراج السكنية',
                'singular_name' => 'برج سكني'
            ],
            'public'       => true,
            'menu_icon'    => 'dashicons-building',
            'supports'     => ['title', 'editor', 'thumbnail'],
        ]);

        register_post_type('apartment', [
            'labels' => [
                'name'          => 'الشقق',
                'singular_name' => 'شقة'
            ],
            'public'       => true,
            'menu_icon'    => 'dashicons-admin-home',
            'supports'     => ['title', 'editor', 'thumbnail'],
            'taxonomies'   => ['category'],
        ]);
    }

    /**
     * إضافة صفحات مخصصة للإدارة
     */
    public function add_admin_pages() {
        add_menu_page('إدارة الأبراج', 'إدارة الأبراج', 'manage_options', 'real-estate-towers', [$this, 'render_tower_wizard_page'], 'dashicons-building', 6);
        add_submenu_page('real-estate-towers', 'إضافة برج جديد', 'إضافة برج جديد', 'manage_options', 'add-new-tower', [$this, 'render_tower_wizard_page']);
        add_submenu_page('real-estate-towers', 'إضافة شقة جديدة', 'إضافة شقة جديدة', 'manage_options', 'add-new-apartment', [$this, 'render_apartment_wizard_page']);
    }

    /**
     * عرض صفحة إضافة برج جديد بنمط Wizard
     */
    public function render_tower_wizard_page() {
        include plugin_dir_path(__FILE__) . '../admin/tower-wizard.php';
    }

    /**
     * عرض صفحة إضافة شقة جديدة بنمط Wizard
     */
    public function render_apartment_wizard_page() {
        include plugin_dir_path(__FILE__) . '../admin/apartment-wizard.php';
    }

    /**
     * إضافة الميتا بوكس لكل من البرج والشقة
     */
    public function add_custom_meta_boxes() {
        add_meta_box('tower_meta', 'تفاصيل البرج', [$this, 'tower_meta_box_callback'], 'tower', 'normal', 'high');
        add_meta_box('apartment_meta', 'تفاصيل الشقة', [$this, 'apartment_meta_box_callback'], 'apartment', 'normal', 'high');
    }

    /**
     * ميتا بوكس البرج
     */
    public function tower_meta_box_callback($post) {
        $location = get_post_meta($post->ID, '_tower_location', true);
        $google_map = get_post_meta($post->ID, '_tower_google_map', true);
        $features = get_post_meta($post->ID, '_tower_features', true);

        echo '<label>الموقع الوصفي:</label>
              <input type="text" name="tower_location" 
                     value="' . esc_attr($location) . '" style="width:100%;" />';
        echo '<label>موقع البرج على خريطة جوجل:</label>
              <input type="text" name="tower_google_map" 
                     value="' . esc_attr($google_map) . '" style="width:100%;" />';
        echo '<label>مزايا البرج:</label>
              <textarea name="tower_features" style="width:100%;">' . esc_textarea($features) . '</textarea>';
    }

    /**
     * ميتا بوكس الشقة
     */
    public function apartment_meta_box_callback($post) {
        $tower_id = get_post_meta($post->ID, '_apartment_tower', true);
        echo '<p><label>ينتمي إلى البرج:</label><br>
              <input type="number" name="apartment_tower" 
                     value="' . esc_attr($tower_id) . '" style="width:100%;" /></p>';

        // الحقول اليدوية
        $manual_data = get_post_meta($post->ID, '_apartment_manual_data', true);
        if (!is_array($manual_data)) {
            $manual_data = [];
        }
        $num_rooms     = isset($manual_data['num_rooms']) ? $manual_data['num_rooms'] : '';
        $num_bathrooms = isset($manual_data['num_bathrooms']) ? $manual_data['num_bathrooms'] : '';
        $kitchen_size  = isset($manual_data['kitchen_size']) ? $manual_data['kitchen_size'] : '';
        $balcony_size  = isset($manual_data['balcony_size']) ? $manual_data['balcony_size'] : '';
        $floor_number  = isset($manual_data['floor_number']) ? $manual_data['floor_number'] : '';
        $price         = isset($manual_data['price']) ? $manual_data['price'] : '';

        echo '<p><label>عدد الغرف:</label><br>
              <input type="number" name="manual_data[num_rooms]" 
                     value="' . esc_attr($num_rooms) . '" /></p>';

        echo '<p><label>عدد الحمامات:</label><br>
              <input type="number" name="manual_data[num_bathrooms]" 
                     value="' . esc_attr($num_bathrooms) . '" /></p>';

        echo '<p><label>حجم المطبخ (متر مربع):</label><br>
              <input type="text" name="manual_data[kitchen_size]" 
                     value="' . esc_attr($kitchen_size) . '" /></p>';

        echo '<p><label>حجم الشرفة (متر مربع):</label><br>
              <input type="text" name="manual_data[balcony_size]" 
                     value="' . esc_attr($balcony_size) . '" /></p>';

        echo '<p><label>رقم الطابق:</label><br>
              <input type="number" name="manual_data[floor_number]" 
                     value="' . esc_attr($floor_number) . '" /></p>';

        echo '<p><label>السعر:</label><br>
              <input type="text" name="manual_data[price]" 
                     value="' . esc_attr($price) . '" /></p>';
    }

    /**
     * حفظ بيانات المدخلات سواء اليدوية أو المخطط
     */
    public function save_custom_meta_data($post_id) {
        // تأكد من عدم تنفيذ الحفظ على auto-draft أو revision
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }

        // حفظ بيانات البرج
        if (isset($_POST['tower_location'])) {
            update_post_meta($post_id, '_tower_location', sanitize_text_field($_POST['tower_location']));
        }
        if (isset($_POST['tower_google_map'])) {
            update_post_meta($post_id, '_tower_google_map', sanitize_text_field($_POST['tower_google_map']));
        }
        if (isset($_POST['tower_features'])) {
            update_post_meta($post_id, '_tower_features', sanitize_textarea_field($_POST['tower_features']));
        }

        // حفظ بيانات الشقة (البرج المرتبط)
        if (isset($_POST['apartment_tower'])) {
            update_post_meta($post_id, '_apartment_tower', sanitize_text_field($_POST['apartment_tower']));
        }

        // حفظ بيانات الشقة المُدخلة يدويًا
        if (isset($_POST['manual_data'])) {
            $manual_data = array_map('sanitize_text_field', $_POST['manual_data']);
            update_post_meta($post_id, '_apartment_manual_data', $manual_data);
        }
    }

    /**
     * شورت كود لعرض قائمة الأبراج
     */
    public function display_towers_shortcode() {
        $q = new WP_Query(['post_type' => 'tower', 'posts_per_page' => -1]);
        $o = '<div class="towers-list">';
        while ($q->have_posts()) {
            $q->the_post();
            $o .= '<div class="tower-item">';
            $o .= '<h2>' . get_the_title() . '</h2>';
            $o .= '<p>' . get_the_excerpt() . '</p>';
            $o .= '<a href="' . get_permalink() . '">عرض التفاصيل</a>';
            $o .= '</div>';
        }
        wp_reset_postdata();
        $o .= '</div>';
        return $o;
    }

    /**
     * شورت كود لعرض قائمة الشقق
     */
    public function display_apartments_shortcode() {
        $q = new WP_Query(['post_type' => 'apartment', 'posts_per_page' => -1]);
        $o = '<div class="apartments-list">';
        while ($q->have_posts()) {
            $q->the_post();
            $o .= '<div class="apartment-item">';
            $o .= '<h2>' . get_the_title() . '</h2>';
            $o .= '<p>' . get_the_excerpt() . '</p>';
            $o .= '<a href="' . get_permalink() . '">عرض التفاصيل</a>';
            $o .= '</div>';
        }
        wp_reset_postdata();
        $o .= '</div>';
        return $o;
    }

    /**
     * استدعاء ملفات التنسيق/السكربت للإدارة
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook == 'toplevel_page_real-estate-towers' || $hook == 'real-estate-towers_page_add-new-tower' || $hook == 'real-estate-towers_page_add-new-apartment') {
            wp_enqueue_style('real_estate_admin_style', plugin_dir_url(__FILE__) . '../assets/admin-styles.css');
            wp_enqueue_script('real_estate_admin_script', plugin_dir_url(__FILE__) . '../assets/admin-scripts.js', ['jquery'], null, true);
        }
    }

    /**
     * استدعاء ملفات التنسيق/السكربت للواجهة الأمامية
     */
    public function enqueue_public_scripts() {
        wp_enqueue_style('real_estate_style', plugin_dir_url(__FILE__) . '../assets/styles.css');
    }
}