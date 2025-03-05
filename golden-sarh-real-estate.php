<?php
/**
 * Plugin Name: Real Estate Towers Manager
 * Plugin URI: https://example.com
 * Description: إضافة ووردبريس لإدارة الأبراج السكنية والشقق داخل الموقع العقاري.
 * Version: 1.5.0
 * Author: Amer Al-Muhammadi
 * Author URI: https://example.com
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// استدعاء ملف التحليل (يحتوي على كود توضيحي فقط لمعالجة الصور)
require_once plugin_dir_path(__FILE__) . 'real-estate-dynamic-parser.php';

/**
 * دالة التفعيل لإنشاء الجداول المخصصة عند تفعيل الإضافة
 */
function real_estate_plugin_activate() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $t1 = $wpdb->prefix . 're_towers';
    $s1 = "CREATE TABLE IF NOT EXISTS $t1 (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        tower_name VARCHAR(255) NOT NULL,
        tower_location VARCHAR(255),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) ".$wpdb->get_charset_collate().\";";
    dbDelta($s1);

    $t2 = $wpdb->prefix . 're_apartments';
    $s2 = "CREATE TABLE IF NOT EXISTS $t2 (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        tower_id BIGINT(20) UNSIGNED NOT NULL,
        apartment_name VARCHAR(255) NOT NULL,
        dynamic_layout LONGTEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        FOREIGN KEY (tower_id) REFERENCES $t1(id)
    ) ".$wpdb->get_charset_collate().\";";
    dbDelta($s2);
}
register_activation_hook(__FILE__, 'real_estate_plugin_activate');

/**
 * الفئة الرئيسية للإضافة
 */
class RealEstateTowersManager {
    public function __construct() {
        // تسجيل أنواع المنشورات المخصصة
        add_action('init', [$this, 'register_custom_post_types']);

        // إضافة الميتا بوكس للتفاصيل
        add_action('add_meta_boxes', [$this, 'add_custom_meta_boxes']);

        // حفظ البيانات في قاعدة البيانات
        add_action('save_post', [$this, 'save_custom_meta_data']);

        // أكواد قصيرة
        add_shortcode('display_towers', [$this, 'display_towers_shortcode']);
        add_shortcode('search_apartments', [$this, 'search_apartments_shortcode']);
        add_shortcode('interactive_floorplan', [$this, 'interactive_floorplan_shortcode']);

        // تحميل الملفات المصاحبة (CSS/JS)
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
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
        echo '<label>الموقع على الخريطة:</label>
              <input type="text" name="tower_location" 
                     value="' . esc_attr($location) . '" style="width:100%;" />';
    }

    /**
     * ميتا بوكس الشقة
     */
    public function apartment_meta_box_callback($post) {
        $tower_id = get_post_meta($post->ID, '_apartment_tower', true);
        echo '<p><label>ينتمي إلى البرج:</label><br>
              <input type="text" name="apartment_tower" 
                     value="' . esc_attr($tower_id) . '" style="width:100%;" /></p>';

        // الحقول اليدوية
        $manual_data = get_post_meta($post->ID, '_apartment_manual_data', true);
        if (!is_array($manual_data)) {
            $manual_data = [];
        }
        $num_rooms     = isset($manual_data['num_rooms']) ? $manual_data['num_rooms'] : '';
        $num_bathrooms = isset($manual_data['num_bathrooms']) ? $manual_data['num_bathrooms'] : '';
        $kitchen_size  = isset($manual_data['kitchen_size']) ? $manual_data['kitchen_size'] : '';

        echo '<p><label>عدد الغرف:</label><br>
              <input type="number" name="manual_data[num_rooms]" 
                     value="' . esc_attr($num_rooms) . '" /></p>';

        echo '<p><label>عدد الحمامات:</label><br>
              <input type="number" name="manual_data[num_bathrooms]" 
                     value="' . esc_attr($num_bathrooms) . '" /></p>';

        echo '<p><label>حجم المطبخ (متر مربع):</label><br>
              <input type="text" name="manual_data[kitchen_size]" 
                     value="' . esc_attr($kitchen_size) . '" /></p>';

        // حقل لرفع صورة المخطط (تحليلها اختياري)
        echo '<p><label>رفع صورة المخطط (اختياري):</label><br>
              <input type="file" name="apartment_floorplan" accept="image/*" /></p>';
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

        // حفظ بيانات الشقة (البرج المرتبط)
        if (isset($_POST['apartment_tower'])) {
            update_post_meta($post_id, '_apartment_tower', sanitize_text_field($_POST['apartment_tower']));
        }

        // حفظ بيانات الشقة المُدخلة يدويًا في جدول re_apartments
        if (isset($_POST['manual_data'])) {
            $manual_data = array_map('sanitize_text_field', $_POST['manual_data']);
            update_post_meta($post_id, '_apartment_manual_data', $manual_data);

            global $wpdb;
            $table = $wpdb->prefix . 're_apartments';

            // نتحقق إن كان هناك سجل مطابق للـ post_id
            $existing = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $post_id));

            // بناء JSON يخزن المعلومات الأساسية لعرضها لاحقًا
            $arr = [
                'num_rooms'    => $manual_data['num_rooms'],
                'num_bathrooms'=> $manual_data['num_bathrooms'],
                'kitchen_size' => $manual_data['kitchen_size']
            ];
            $json = json_encode($arr, JSON_UNESCAPED_UNICODE);

            if ($existing) {
                // تحديث السجل
                $wpdb->update(
                    $table,
                    [
                        'apartment_name' => get_the_title($post_id),
                        'dynamic_layout' => $json
                    ],
                    ['id' => $post_id],
                    ['%s', '%s'],
                    ['%d']
                );
            } else {
                // إن لم يوجد سجل مسبقًا نُنشئه
                $tower_id = get_post_meta($post_id, '_apartment_tower', true);
                if (!$tower_id) {
                    $tower_id = 0;
                }
                $wpdb->insert(
                    $table,
                    [
                        'id'             => $post_id,
                        'tower_id'       => (int) $tower_id,
                        'apartment_name' => get_the_title($post_id),
                        'dynamic_layout' => $json
                    ],
                    ['%d', '%d', '%s', '%s']
                );
            }
        }

        // حفظ بيانات المخطط إن تم رفع صورة
        if (isset($_FILES['apartment_floorplan']) && !empty($_FILES['apartment_floorplan']['name'])) {
            $file = $_FILES['apartment_floorplan'];
            $upload_overrides = ['test_form' => false];
            $movefile = wp_handle_upload($file, $upload_overrides);

            if ($movefile && !isset($movefile['error'])) {
                $image_path = $movefile['file'];
                $tower_id   = get_post_meta($post_id, '_apartment_tower', true);
                if (!$tower_id) {
                    $tower_id = 0;
                }
                // استدعاء وظيفة تحليل الصورة وتخزين الناتج
                RealEstateDynamicParser::store_parsed_data($tower_id, get_the_title($post_id), $image_path);
            }
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
     * شورت كود للبحث عن الشقق
     */
    public function search_apartments_shortcode() {
        ob_start();
        ?>
        <form method="GET" action="">
            <input type="text" name="apartment_search" placeholder="ابحث عن شقة..." />
            <button type="submit">بحث</button>
        </form>
        <?php
        if (isset($_GET['apartment_search'])) {
            $s = sanitize_text_field($_GET['apartment_search']);
            $q = new WP_Query([
                'post_type' => 'apartment',
                's'         => $s
            ]);
            echo '<div class="search-results">';
            while ($q->have_posts()) {
                $q->the_post();
                echo '<div class="apartment-item">';
                echo '<h2>' . get_the_title() . '</h2>';
                echo '<a href="' . get_permalink() . '">عرض التفاصيل</a>';
                echo '</div>';
            }
            wp_reset_postdata();
            echo '</div>';
        }
        return ob_get_clean();
    }

    /**
     * شورت كود لعرض المخطط التفاعلي المأخوذ من قاعدة البيانات
     */
    public function interactive_floorplan_shortcode($atts) {
        $id = isset($atts['id']) ? intval($atts['id']) : 0;
        if (!$id) return '';

        global $wpdb;
        $table = $wpdb->prefix . 're_apartments';
        $data  = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
        if (!$data) return '';

        // استدعاء ملف الجافاسكربت التفاعلي
        wp_enqueue_script('interactive-plan', plugin_dir_url(__FILE__) . 'interactive-floorplan.js', [], null, true);

        // تمرير بيانات المخطط كـ data attribute
        $layout = esc_js($data->dynamic_layout);
        $html = "<div id='floorplan-$id' class='interactive-floorplan' data-layout='$layout' style='width:600px; height:400px; border:1px solid #ccc;'></div>";
        return $html;
    }

    /**
     * استدعاء ملفات التنسيق/السكربت
     */
    public function enqueue_scripts() {
        // ملف CSS لتنسيق العرض
        wp_enqueue_style('real_estate_style', plugin_dir_url(__FILE__) . 'styles.css');
    }
}

// تهيئة الفئة
new RealEstateTowersManager();
