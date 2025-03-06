<?php
// tower-management.php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * إضافة صفحة إدارة الأبراج داخل لوحة تحكم وردبريس.
 */
function rem_add_tower_management_menu() {
    add_menu_page(
        'إدارة الأبراج السكنية',
        'إدارة الأبراج',
        'manage_options',
        'manage-towers',
        'rem_display_tower_management',
        'dashicons-building',
        20
    );
}
add_action( 'admin_menu', 'rem_add_tower_management_menu' );

/**
 * عرض صفحة إدارة الأبراج السكنية.
 */
function rem_display_tower_management() {
    $towers = get_posts(array('post_type' => 'tower', 'numberposts' => -1));
    ?>
    <div class="wrap">
        <h1>إدارة الأبراج السكنية</h1>
        <a href="<?php echo admin_url('admin.php?page=add-new-tower'); ?>" class="button-primary">إضافة برج جديد</a>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>اسم البرج</th>
                    <th>الموقع</th>
                    <th>عدد الأدوار</th>
                    <th>عدد الشقق</th>
                    <th>المساحة</th>
                    <th>سنة الإنشاء</th>
                    <th>حالة البرج</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($towers as $tower) : ?>
                <tr>
                    <td><?php echo get_the_title($tower->ID); ?></td>
                    <td><?php echo get_post_meta($tower->ID, 'tower_location', true); ?></td>
                    <td><?php echo get_post_meta($tower->ID, 'tower_floors', true); ?></td>
                    <td><?php echo get_post_meta($tower->ID, 'tower_apartments_per_floor', true); ?></td>
                    <td><?php echo get_post_meta($tower->ID, 'tower_total_area', true); ?> م²</td>
                    <td><?php echo get_post_meta($tower->ID, 'tower_completion_year', true); ?></td>
                    <td><?php echo get_post_meta($tower->ID, 'tower_status', true); ?></td>
                    <td>
                        <a href="<?php echo get_edit_post_link($tower->ID); ?>" class="button">تعديل</a>
                        <a href="<?php echo get_delete_post_link($tower->ID); ?>" class="button button-danger">حذف</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * إضافة صفحة لإنشاء برج جديد تحتوي على كافة البيانات في شاشة واحدة.
 */
function rem_add_tower_creation_page() {
    add_submenu_page(
        null, 
        'إضافة برج جديد',
        'إضافة برج جديد',
        'manage_options',
        'add-new-tower',
        'rem_display_tower_creation_form'
    );
}
add_action( 'admin_menu', 'rem_add_tower_creation_page' );

/**
 * عرض نموذج إدخال برج جديد في صفحة واحدة.
 */
function rem_display_tower_creation_form() {
    ?>
    <div class="wrap">
        <h1>إضافة برج جديد</h1>
        <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
            <?php wp_nonce_field('rem_tower_creation_action', 'rem_tower_creation_nonce'); ?>
            <input type="hidden" name="action" value="rem_handle_tower_creation">

            <label>اسم البرج:</label>
            <input type="text" name="tower_name" required>

            <label>الموقع:</label>
            <input type="text" name="tower_location" required>

            <label>عدد الأدوار:</label>
            <input type="number" name="tower_floors" required>

            <label>عدد الشقق في كل دور:</label>
            <input type="number" name="apartments_per_floor" required>

            <label>المساحة الإجمالية (م²):</label>
            <input type="text" name="tower_area" required>

            <label>سنة الإنشاء:</label>
            <input type="text" name="tower_year" required>

            <label>حالة البرج:</label>
            <select name="tower_status" required>
                <option value="جاهز للسكن">جاهز للسكن</option>
                <option value="قيد الإنشاء">قيد الإنشاء</option>
                <option value="بيع على الخارطة">بيع على الخارطة</option>
            </select>

            <label>وصف تفصيلي:</label>
            <textarea name="tower_description" required></textarea>

            <button type="submit" class="button-primary">حفظ البرج</button>
        </form>
    </div>
    <?php
}

/**
 * معالجة بيانات البرج عند الإرسال.
 */
function rem_handle_tower_creation() {
    if ( ! isset($_POST['rem_tower_creation_nonce']) || ! wp_verify_nonce($_POST['rem_tower_creation_nonce'], 'rem_tower_creation_action') ) {
        wp_die('فشل التحقق من الأمان');
    }

    $post_id = wp_insert_post(array(
        'post_type'    => 'tower',
        'post_title'   => sanitize_text_field($_POST['tower_name']),
        'post_status'  => 'publish',
    ));

    if ( $post_id ) {
        update_post_meta($post_id, 'tower_location', sanitize_text_field($_POST['tower_location']));
        update_post_meta($post_id, 'tower_floors', intval($_POST['tower_floors']));
        update_post_meta($post_id, 'tower_apartments_per_floor', intval($_POST['apartments_per_floor']));
        update_post_meta($post_id, 'tower_total_area', sanitize_text_field($_POST['tower_area']));
        update_post_meta($post_id, 'tower_completion_year', sanitize_text_field($_POST['tower_year']));
        update_post_meta($post_id, 'tower_status', sanitize_text_field($_POST['tower_status']));
        update_post_meta($post_id, 'tower_description', sanitize_textarea_field($_POST['tower_description']));
        
        wp_redirect(admin_url('edit.php?post_type=tower'));
        exit;
    } else {
        wp_die('حدث خطأ أثناء حفظ البرج.');
    }
}
add_action('admin_post_rem_handle_tower_creation', 'rem_handle_tower_creation');
