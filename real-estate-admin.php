<?php
// ملف real-estate-admin.php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// إضافة Meta Boxes مخصصة للأبراج والشقق
function rem_add_custom_meta_boxes() {
    add_meta_box(
        'rem_tower_details',
        'تفاصيل البرج',
        'rem_tower_details_callback',
        'tower',
        'normal',
        'high'
    );

    add_meta_box(
        'rem_apartment_details',
        'تفاصيل الشقة',
        'rem_apartment_details_callback',
        'apartment',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'rem_add_custom_meta_boxes');

function rem_tower_details_callback($post) {
    // استرجاع البيانات المحفوظة مسبقاً
    $tower_location = get_post_meta($post->ID, 'tower_location', true);
    $tower_units = get_post_meta($post->ID, 'tower_units', true);
    ?>
    <p>
        <label for="tower_location">الموقع:</label>
        <input type="text" name="tower_location" id="tower_location" value="<?php echo esc_attr($tower_location); ?>" style="width:100%;" />
    </p>
    <p>
        <label for="tower_units">عدد الوحدات:</label>
        <input type="number" name="tower_units" id="tower_units" value="<?php echo esc_attr($tower_units); ?>" style="width:100%;" />
    </p>
    <?php
}

function rem_apartment_details_callback($post) {
    // استرجاع البيانات المحفوظة مسبقاً
    $apartment_area = get_post_meta($post->ID, 'apartment_area', true);
    $apartment_price = get_post_meta($post->ID, 'apartment_price', true);
    ?>
    <p>
        <label for="apartment_area">المساحة (م²):</label>
        <input type="text" name="apartment_area" id="apartment_area" value="<?php echo esc_attr($apartment_area); ?>" style="width:100%;" />
    </p>
    <p>
        <label for="apartment_price">السعر:</label>
        <input type="text" name="apartment_price" id="apartment_price" value="<?php echo esc_attr($apartment_price); ?>" style="width:100%;" />
    </p>
    <?php
}

// حفظ بيانات الـ Meta Boxes
function rem_save_custom_meta_data($post_id) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    
    if ( isset($_POST['tower_location']) ) {
        update_post_meta($post_id, 'tower_location', sanitize_text_field($_POST['tower_location']));
    }
    if ( isset($_POST['tower_units']) ) {
        update_post_meta($post_id, 'tower_units', intval($_POST['tower_units']));
    }
    if ( isset($_POST['apartment_area']) ) {
        update_post_meta($post_id, 'apartment_area', sanitize_text_field($_POST['apartment_area']));
    }
    if ( isset($_POST['apartment_price']) ) {
        update_post_meta($post_id, 'apartment_price', sanitize_text_field($_POST['apartment_price']));
    }
}
add_action('save_post', 'rem_save_custom_meta_data');
