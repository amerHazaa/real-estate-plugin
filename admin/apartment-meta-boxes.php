<?php
add_action('add_meta_boxes', 'gre_add_apartment_meta_boxes');
add_action('save_post', 'gre_save_apartment_meta');

// إزالة محرر النصوص من نوع الشقة
add_action('init', function () {
    remove_post_type_support('gre_apartment', 'editor');
}, 100);

function gre_add_apartment_meta_boxes() {
    add_meta_box('gre_apartment_details', 'تفاصيل الشقة', 'gre_render_apartment_meta_box', 'gre_apartment', 'normal', 'high');
}

function gre_get_apartment_meta($post_id, $key, $default = '') {
    return get_post_meta($post_id, "_gre_apartment_$key", true) ?: $default;
}

function gre_render_apartment_meta_box($post) {
    wp_nonce_field('gre_save_apartment_meta', 'gre_apartment_meta_nonce');

    $meta_keys = [
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

    foreach ($meta_keys as $key => $default) {
        $meta_keys[$key] = gre_get_apartment_meta($post->ID, $key, $default);
    }

    echo '<style>
        .gre-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .gre-full { grid-column: span 2; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; }
    </style>';

    echo '<div class="gre-grid">';

    // عرض الحقول readonly
    $readonly_fields = [
        'apartment_code' => 'معرف الشقة',
        'apartment_number' => 'رقم الشقة',
        'floor_number' => 'الدور'
    ];
    foreach ($readonly_fields as $key => $label) {
        echo "<div><label>$label</label><input type='text' name='gre_apartment_$key' value='" . esc_attr($meta_keys[$key]) . "' readonly /></div>";
    }

    // عرض معلومات النموذج والبرج
    gre_render_model_tower_info($meta_keys['model_id']);

    // حالة الشقة
    $status_options = [
        'available' => 'متاحة',
        'sold' => 'مباعة',
        'under_preparation' => 'قيد التجهيز',
        'for_finishing' => 'تحتاج تشطيب'
    ];
    echo '<div><label>حالة الشقة</label><select name="gre_apartment_status">';
    foreach ($status_options as $value => $label) {
        echo "<option value='$value'" . selected($meta_keys['status'], $value, false) . ">$label</option>";
    }
    echo '</select></div>';

    // السعر المخصص
    echo '<div><label>سعر مخصص (اختياري)</label><input type="text" name="gre_apartment_custom_price_usd" value="' . esc_attr($meta_keys['custom_price_usd']) . '" /></div>';

    // مستوى ونوع التشطيب
    $select_fields = [
        'custom_finishing_level' => [
            '' => 'وراثة من النموذج',
            'ready' => 'مشطب',
            'semi' => 'نصف تشطيب',
            'none' => 'بدون تشطيب'
        ],
        'custom_finishing_type' => [
            '' => 'وراثة من النموذج',
            'luxury' => 'فاخر',
            'deluxe' => 'ديلوكس',
            'normal' => 'عادي'
        ]
    ];
    foreach ($select_fields as $key => $options) {
        $label = $key === 'custom_finishing_level' ? 'مستوى التشطيب (اختياري)' : 'نوع التشطيب (اختياري)';
        echo "<div><label>$label</label><select name='gre_apartment_$key'>";
        foreach ($options as $value => $label_option) {
            echo "<option value='$value'" . selected($meta_keys[$key], $value, false) . ">$label_option</option>";
        }
        echo '</select></div>';
    }

    // الصور المخصصة
    echo '<div class="gre-full"><label>صور مخصصة للشقة (روابط أو IDs)</label><textarea name="gre_apartment_custom_images">' . esc_textarea($meta_keys['custom_images']) . '</textarea></div>';

    echo '</div>'; // نهاية .gre-grid
}

function gre_render_model_tower_info($model_id) {
    $model_title = $model_id ? get_the_title($model_id) : '—';
    $tower_id = $model_id ? get_post_meta($model_id, '_gre_model_tower_id', true) : '';
    $tower_title = $tower_id ? get_the_title($tower_id) : '—';

    echo '<div><label>النموذج التابع</label><input type="text" value="' . esc_attr($model_title) . '" readonly /></div>';
    echo '<div><label>البرج التابع</label><input type="text" value="' . esc_attr($tower_title) . '" readonly /></div>';
}

function gre_save_apartment_meta($post_id) {
    if (
        !isset($_POST['gre_apartment_meta_nonce']) ||
        !wp_verify_nonce($_POST['gre_apartment_meta_nonce'], 'gre_save_apartment_meta') ||
        defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ||
        !current_user_can('edit_post', $post_id)
    ) return;

    $fields = [
        'apartment_code','apartment_number','floor_number','model_id','tower_id','status',
        'custom_price_usd','custom_finishing_level','custom_finishing_type','custom_images'
    ];

    foreach ($fields as $field) {
        $value = sanitize_text_field($_POST["gre_apartment_$field"] ?? '');
        update_post_meta($post_id, "_gre_apartment_$field", $value);
    }
}