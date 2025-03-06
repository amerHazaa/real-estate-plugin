<?php
// apartment-management.php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * إضافة صفحة إدارة الشقق داخل لوحة تحكم وردبريس.
 */
function rem_add_apartment_management_menu() {
    add_menu_page(
        'إدارة الشقق السكنية',
        'إدارة الشقق',
        'manage_options',
        'manage-apartments',
        'rem_display_apartment_management',
        'dashicons-admin-home',
        21
    );
}
add_action( 'admin_menu', 'rem_add_apartment_management_menu' );

/**
 * عرض صفحة إدارة الشقق السكنية.
 */
function rem_display_apartment_management() {
    $apartments = get_posts(array('post_type' => 'apartment', 'numberposts' => -1));
    ?>
    <div class="wrap">
        <h1>إدارة الشقق السكنية</h1>
        <a href="<?php echo admin_url('admin.php?page=add-new-apartment'); ?>" class="button-primary">إضافة شقة جديدة</a>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>اسم الشقة</th>
                    <th>المساحة</th>
                    <th>عدد الغرف</th>
                    <th>عدد الحمامات</th>
                    <th>التشطيب</th>
                    <th>السعر</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($apartments as $apartment) : ?>
                <tr>
                    <td><?php echo get_the_title($apartment->ID); ?></td>
                    <td><?php echo get_post_meta($apartment->ID, 'apartment_total_area', true); ?> م²</td>
                    <td><?php echo get_post_meta($apartment->ID, 'apartment_rooms_count', true); ?></td>
                    <td><?php echo get_post_meta($apartment->ID, 'apartment_bathrooms', true); ?></td>
                    <td><?php echo get_post_meta($apartment->ID, 'apartment_finishing', true); ?></td>
                    <td><?php echo get_post_meta($apartment->ID, 'apartment_price', true); ?> $</td>
                    <td>
                        <a href="<?php echo get_edit_post_link($apartment->ID); ?>" class="button">تعديل</a>
                        <a href="<?php echo get_delete_post_link($apartment->ID); ?>" class="button button-danger">حذف</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * إضافة صفحة لإنشاء شقة جديدة تحتوي على كافة البيانات في شاشة واحدة.
 */
function rem_add_apartment_creation_page() {
    add_submenu_page(
        null, 
        'إضافة شقة جديدة',
        'إضافة شقة جديدة',
        'manage_options',
        'add-new-apartment',
        'rem_display_apartment_creation_form'
    );
}
add_action( 'admin_menu', 'rem_add_apartment_creation_page' );

/**
 * عرض نموذج إدخال شقة جديدة في صفحة واحدة.
 */
function rem_display_apartment_creation_form() {
    ?>
    <div class="wrap">
        <h1>إضافة شقة جديدة</h1>
        <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
            <?php wp_nonce_field('rem_apartment_creation_action', 'rem_apartment_creation_nonce'); ?>
            <input type="hidden" name="action" value="rem_handle_apartment_creation">

            <label>اسم الشقة:</label>
            <input type="text" name="apartment_name" required>

            <label>المساحة الإجمالية (م²):</label>
            <input type="number" name="apartment_area" required>

            <label>عدد الغرف:</label>
            <input type="number" name="apartment_rooms" required>

            <label>عدد الحمامات:</label>
            <input type="number" name="bathrooms" required>

            <label>نوع التشطيب:</label>
            <select name="apartment_finishing" required>
                <option value="عادي">عادي</option>
                <option value="فاخر">فاخر</option>
            </select>

            <label>السعر:</label>
            <input type="number" name="apartment_price" required>

            <label>وصف تفصيلي:</label>
            <textarea name="apartment_description" required></textarea>

            <button type="submit" class="button-primary">حفظ الشقة</button>
        </form>
    </div>
    <?php
}

/**
 * معالجة بيانات الشقة عند الإرسال.
 */
function rem_handle_apartment_creation() {
    if ( ! isset($_POST['rem_apartment_creation_nonce']) || ! wp_verify_nonce($_POST['rem_apartment_creation_nonce'], 'rem_apartment_creation_action') ) {
        wp_die('فشل التحقق من الأمان');
    }

    $post_id = wp_insert_post(array(
        'post_type'    => 'apartment',
        'post_title'   => sanitize_text_field($_POST['apartment_name']),
        'post_status'  => 'publish',
    ));

    if ( $post_id ) {
        update_post_meta($post_id, 'apartment_total_area', sanitize_text_field($_POST['apartment_area']));
        update_post_meta($post_id, 'apartment_rooms_count', intval($_POST['apartment_rooms']));
        update_post_meta($post_id, 'apartment_bathrooms', intval($_POST['bathrooms']));
        update_post_meta($post_id, 'apartment_finishing', sanitize_text_field($_POST['apartment_finishing']));
        update_post_meta($post_id, 'apartment_price', sanitize_text_field($_POST['apartment_price']));
        update_post_meta($post_id, 'apartment_description', sanitize_textarea_field($_POST['apartment_description']));

        wp_redirect(admin_url('edit.php?post_type=apartment'));
        exit;
    } else {
        wp_die('حدث خطأ أثناء حفظ الشقة.');
    }
}
add_action('admin_post_rem_handle_apartment_creation', 'rem_handle_apartment_creation');
