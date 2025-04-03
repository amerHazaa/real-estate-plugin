<?php
add_action('add_meta_boxes', 'gre_add_apartment_meta_boxes');
add_action('save_post', 'gre_save_apartment_meta');

add_action('init', function() {
    remove_post_type_support('gre_apartment', 'editor');
}, 100);

function gre_add_apartment_meta_boxes() {
    add_meta_box('gre_apartment_details', 'تفاصيل الشقة', 'gre_render_apartment_meta_box', 'gre_apartment', 'normal', 'high');
}

function gre_render_apartment_meta_box($post) {
    wp_nonce_field('gre_save_apartment_meta', 'gre_apartment_meta_nonce');

    $fields = [
        'apartment_code' => '',
        'apartment_number' => '',
        'floor_number' => '',
        'model_id' => '',
        'tower_id' => '',
        'status' => '',
        'custom_price_usd' => '',
        'custom_finishing_level' => '',
        'custom_finishing_type' => '',
        'custom_images' => ''
    ];

    foreach ($fields as $key => $default) {
        $fields[$key] = get_post_meta($post->ID, "_gre_apartment_$key", true) ?: $default;
    }

    // عرض اسم النموذج واسم البرج
    $model_id = $fields['model_id'];
    $model_title = $model_id ? get_the_title($model_id) : '—';
    $tower_id = $model_id ? get_post_meta($model_id, '_gre_model_tower_id', true) : '';
    $tower_title = $tower_id ? get_the_title($tower_id) : '—';

    echo '<style>
        .gre-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .gre-full { grid-column: span 3; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; }
    </style>';

    echo '<div class="gre-grid">';

    echo '<div><label>معرف الشقة</label><input type="text" name="gre_apartment_apartment_code" value="' . esc_attr($fields['apartment_code']) . '" readonly /></div>';
    echo '<div><label>رقم الشقة</label><input type="text" name="gre_apartment_apartment_number" value="' . esc_attr($fields['apartment_number']) . '" /></div>';
    echo '<div><label>الدور</label><input type="text" name="gre_apartment_floor_number" value="' . esc_attr($fields['floor_number']) . '" readonly /></div>';

    echo '<div><label>النموذج التابع</label><input type="text" value="' . esc_attr($model_title) . '" readonly /></div>';
    echo '<div><label>البرج التابع</label><input type="text" value="' . esc_attr($tower_title) . '" readonly /></div>';

    echo '<div><label>حالة الشقة</label><select name="gre_apartment_status">
        <option value="available"' . selected($fields['status'], 'available', false) . '>متاحة</option>
        <option value="sold"' . selected($fields['status'], 'sold', false) . '>مباعة</option>
        <option value="under_preparation"' . selected($fields['status'], 'under_preparation', false) . '>قيد التجهيز</option>
        <option value="for_finishing"' . selected($fields['status'], 'for_finishing', false) . '>تحتاج تشطيب</option>
    </select></div>';

    echo '<div><label>سعر مخصص (اختياري)</label><input type="text" name="gre_apartment_custom_price_usd" value="' . esc_attr($fields['custom_price_usd']) . '" /></div>';

    echo '<div><label>مستوى التشطيب (اختياري)</label><select name="gre_apartment_custom_finishing_level">
        <option value="">وراثة من النموذج</option>
        <option value="ready"' . selected($fields['custom_finishing_level'], 'ready', false) . '>مشطب</option>
        <option value="semi"' . selected($fields['custom_finishing_level'], 'semi', false) . '>نصف تشطيب</option>
        <option value="none"' . selected($fields['custom_finishing_level'], 'none', false) . '>بدون تشطيب</option>
    </select></div>';

    echo '<div><label>نوع التشطيب (اختياري)</label><select name="gre_apartment_custom_finishing_type">
        <option value="">وراثة من النموذج</option>
        <option value="luxury"' . selected($fields['custom_finishing_type'], 'luxury', false) . '>فاخر</option>
        <option value="deluxe"' . selected($fields['custom_finishing_type'], 'deluxe', false) . '>ديلوكس</option>
        <option value="normal"' . selected($fields['custom_finishing_type'], 'normal', false) . '>عادي</option>
    </select></div>';

    echo '<div class="gre-full"><label>صور مخصصة للشقة (روابط/IDs)</label><textarea name="gre_apartment_custom_images">' . esc_textarea($fields['custom_images']) . '</textarea></div>';

    echo '</div>';
}

function gre_save_apartment_meta($post_id) {
    if (!isset($_POST['gre_apartment_meta_nonce']) || !wp_verify_nonce($_POST['gre_apartment_meta_nonce'], 'gre_save_apartment_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        'apartment_code','apartment_number','floor_number','model_id','tower_id','status',
        'custom_price_usd','custom_finishing_level','custom_finishing_type','custom_images'
    ];

    foreach ($fields as $field) {
        $value = isset($_POST["gre_apartment_$field"]) ? sanitize_text_field($_POST["gre_apartment_$field"]) : '';
        update_post_meta($post_id, "_gre_apartment_$field", $value);
    }
}
