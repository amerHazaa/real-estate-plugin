<?php
add_action('add_meta_boxes', 'gre_add_model_meta_boxes');
add_action('save_post', 'gre_save_model_meta', 10, 2);
add_action('wp_insert_post_data', 'gre_set_model_post_title_if_empty', 10, 2);

// ربط ملف الجافاسكربت الخاص بتحرير النموذج
add_action('admin_enqueue_scripts', 'gre_enqueue_model_script');
function gre_enqueue_model_script($hook) {
    if ($hook === 'post-new.php' || $hook === 'post.php') {
        global $post;
        if ($post && $post->post_type === 'gre_model') {
            wp_enqueue_script(
                'gre-model-meta-script',
                plugin_dir_url(__FILE__) . '../assets/js/gre-model-meta.js',
                ['jquery'],
                false,
                true
            );
        }
    }
}

// Ajax لتوليد الرمز المختصر حسب البرج
add_action('wp_ajax_gre_generate_model_short_name', function() {
    $tower_id = intval($_GET['tower_id']);
    $short = get_post_meta($tower_id, '_gre_tower_short_name', true);
    $models = get_posts([
        'post_type' => 'gre_model',
        'post_status' => 'any',
        'meta_query' => [
            [
                'key' => '_gre_model_tower_id',
                'value' => $tower_id
            ]
        ]
    ]);
    $count = count($models) + 1;
    wp_send_json_success(['short_name' => $short . '-M' . $count]);
});

function gre_set_model_post_title_if_empty($data, $postarr) {
    if ($data['post_type'] === 'gre_model' && empty($data['post_title']) && !empty($postarr['ID'])) {
        $short_name = get_post_meta($postarr['ID'], '_gre_model_code', true);
        if ($short_name) {
            $data['post_title'] = $short_name;
        }
    }
    return $data;
}

function gre_add_model_meta_boxes() {
    add_meta_box('gre_model_details', 'تفاصيل النموذج', 'gre_render_model_meta_box', 'gre_model', 'normal', 'high');
}

function gre_render_model_meta_box($post) {
    wp_nonce_field('gre_save_model_meta', 'gre_model_meta_nonce');

    $fields = [
        'tower_id' => '', 'code' => '', 'description' => '', 'area' => '',
        'rooms_count' => 0, 'bathrooms_count' => 0, 'kitchens_count' => 0,
        'finishing_level' => '', 'finishing_type' => '', 'default_status' => '',
        'availability_date' => '', 'price_usd' => '',
        'has_living_room' => '', 'has_majlis' => '', 'has_balcony' => '', 'has_storage' => '',
        'has_equipped_kitchen' => '', 'has_ac' => '', 'has_fire_safety' => '',
        'has_cctv' => '', 'has_water_meter' => '', 'has_electricity_meter' => '',
        'has_internet' => '', 'has_wc_western' => '', 'has_jacuzzi_sauna' => ''
    ];

    foreach ($fields as $key => $default) {
        $fields[$key] = get_post_meta($post->ID, "_gre_model_$key", true) ?: $default;
    }

    // توليد الرمز المختصر تلقائيًا عند الإنشاء
    if (get_current_screen()->action === 'add' && empty($fields['code'])) {
        $towers = get_posts([
            'post_type' => 'gre_tower', 'posts_per_page' => 1,
            'orderby' => 'ID', 'order' => 'DESC'
        ]);
        if (!empty($towers)) {
            $tower = $towers[0];
            $tower_id = $tower->ID;
            $tower_short = get_post_meta($tower_id, '_gre_tower_short_name', true);
            $models = get_posts([
                'post_type' => 'gre_model',
                'post_status' => 'any',
                'meta_query' => [[
                    'key' => '_gre_model_tower_id',
                    'value' => $tower_id
                ]]
            ]);
            $fields['code'] = $tower_short . '-M' . (count($models) + 1);
            $fields['tower_id'] = $tower_id;
        }
    }

    echo '<style>
        .gre-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .gre-full { grid-column: span 3; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; }
    </style>';

    echo '<div class="gre-grid">';
    echo '<div><label>البرج التابع</label>';
    $towers = get_posts(['post_type' => 'gre_tower', 'numberposts' => -1, 'orderby' => 'date', 'order' => 'DESC']);
    echo '<select name="gre_model_tower_id" id="gre_model_tower_id">';
    foreach ($towers as $tower) {
        $selected = selected($fields['tower_id'], $tower->ID, false);
        echo "<option value='{$tower->ID}' $selected>{$tower->post_title}</option>";
    }
    echo '</select></div>';
    echo '<div><label>الرمز المختصر</label><input type="text" name="gre_model_code" id="gre_model_code" value="' . esc_attr($fields['code']) . '" /></div>';
    echo '<div><label>المساحة الإجمالية</label><input type="text" name="gre_model_area" value="' . esc_attr($fields['area']) . '" /></div>';
    echo '<div><label>عدد الغرف</label><input type="number" name="gre_model_rooms_count" value="' . esc_attr($fields['rooms_count']) . '" /></div>';
    echo '<div><label>عدد الحمّامات</label><input type="number" name="gre_model_bathrooms_count" value="' . esc_attr($fields['bathrooms_count']) . '" /></div>';
    echo '<div><label>عدد المطابخ</label><input type="number" name="gre_model_kitchens_count" value="' . esc_attr($fields['kitchens_count']) . '" /></div>';
    echo '<div><label>مستوى التشطيب</label><select name="gre_model_finishing_level">
        <option value="ready"' . selected($fields['finishing_level'], 'ready', false) . '>مشطب</option>
        <option value="semi"' . selected($fields['finishing_level'], 'semi', false) . '>نصف تشطيب</option>
        <option value="none"' . selected($fields['finishing_level'], 'none', false) . '>بدون تشطيب</option>
    </select></div>';
    echo '<div><label>نوع التشطيب</label><select name="gre_model_finishing_type">
        <option value="luxury"' . selected($fields['finishing_type'], 'luxury', false) . '>فاخر</option>
        <option value="deluxe"' . selected($fields['finishing_type'], 'deluxe', false) . '>ديلوكس</option>
        <option value="normal"' . selected($fields['finishing_type'], 'normal', false) . '>عادي</option>
    </select></div>';
    echo '<div><label>حالة الشقق الافتراضية</label><select name="gre_model_default_status">
        <option value="available"' . selected($fields['default_status'], 'available', false) . '>متاحة</option>
        <option value="sold"' . selected($fields['default_status'], 'sold', false) . '>مباعة</option>
        <option value="under_preparation"' . selected($fields['default_status'], 'under_preparation', false) . '>قيد التجهيز</option>
        <option value="for_finishing"' . selected($fields['default_status'], 'for_finishing', false) . '>تحتاج تشطيب</option>
    </select></div>';
    echo '<div><label>تاريخ التوافر</label><input type="date" name="gre_model_availability_date" value="' . esc_attr($fields['availability_date']) . '" /></div>';
    echo '<div><label>السعر (اختياري بالدولار)</label><input type="text" name="gre_model_price_usd" value="' . esc_attr($fields['price_usd']) . '" /></div>';

    $checkboxes = [
        'has_living_room' => 'صالة جلوس', 'has_majlis' => 'مجلس منفصل', 'has_balcony' => 'شرفة (بلكونة)',
        'has_storage' => 'مخزن', 'has_equipped_kitchen' => 'مطبخ مجهّز', 'has_ac' => 'تدفئة وتبريد',
        'has_fire_safety' => 'أنظمة حماية', 'has_cctv' => 'كاميرات مراقبة', 'has_water_meter' => 'عداد مياه مستقل',
        'has_electricity_meter' => 'عداد كهرباء مستقل', 'has_internet' => 'اتصال إنترنت',
        'has_wc_western' => 'حمام أفرنجي', 'has_jacuzzi_sauna' => 'جاكوزي / ساونا'
    ];
    foreach ($checkboxes as $key => $label) {
        $checked = checked($fields[$key], '1', false);
        echo "<div><label><input type='checkbox' name='gre_model_$key' value='1' $checked /> $label</label></div>";
    }
    echo '<div class="gre-full"><label>وصف عام للنموذج</label><textarea name="gre_model_description">' . esc_textarea($fields['description']) . '</textarea></div>';
    echo '</div>';

    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        const towerSelect = document.getElementById("gre_model_tower_id");
        const shortNameField = document.getElementById("gre_model_code");

        async function updateShortName(towerId) {
            const res = await fetch(ajaxurl + "?action=gre_generate_model_short_name&tower_id=" + towerId);
            const data = await res.json();
            if (data.success && shortNameField) {
                shortNameField.value = data.short_name;
            }
        }

        if (towerSelect) {
            towerSelect.addEventListener("change", function() {
                updateShortName(this.value);
            });

            if (shortNameField && shortNameField.value.trim() === "") {
                updateShortName(towerSelect.value);
            }
        }
    });
    </script>';
}

function gre_save_model_meta($post_id, $post) {
    if (!isset($_POST['gre_model_meta_nonce']) || !wp_verify_nonce($_POST['gre_model_meta_nonce'], 'gre_save_model_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        'tower_id','code','description','area','rooms_count','bathrooms_count','kitchens_count',
        'finishing_level','finishing_type','default_status','availability_date','price_usd',
        'has_living_room','has_majlis','has_balcony','has_storage','has_equipped_kitchen',
        'has_ac','has_fire_safety','has_cctv','has_water_meter','has_electricity_meter',
        'has_internet','has_wc_western','has_jacuzzi_sauna'
    ];

    $meta = [];
    foreach ($fields as $field) {
        $value = isset($_POST["gre_model_$field"]) ? sanitize_text_field($_POST["gre_model_$field"]) : '';
        update_post_meta($post_id, "_gre_model_$field", $value);
        $meta[$field] = $value;
    }

    // توليد رمز مختصر تلقائي إذا لم يتم إدخاله
    if (empty($meta['code']) && !empty($meta['tower_id'])) {
        $tower_short = get_post_meta($meta['tower_id'], '_gre_tower_short_name', true);
        for ($i = 1; $i <= 10; $i++) {
            $candidate = $tower_short . '-M' . $i;
            $existing = get_posts([
                'post_type' => 'gre_model',
                'post_status' => 'any',
                'meta_key' => '_gre_model_code',
                'meta_value' => $candidate,
                'numberposts' => 1
            ]);
            if (empty($existing)) {
                update_post_meta($post_id, '_gre_model_code', $candidate);
                $meta['code'] = $candidate;
                break;
            }
        }
    }

    // توليد الشقق تلقائيًا
    if (get_post_meta($post_id, '_gre_apartment_generated', true) !== '1' && !empty($meta['tower_id']) && $meta['code']) {
        $tower_id = $meta['tower_id'];
        $tower_short = get_post_meta($tower_id, '_gre_tower_short_name', true);
        $model_code = $meta['code'];
        $floors = (int) get_post_meta($tower_id, '_gre_tower_floors', true);
        $status = $meta['default_status'];

        for ($i = 1; $i <= $floors; $i++) {
            $code = "{$tower_short}-{$model_code}-{$i}";
            $existing = get_posts([
                'post_type' => 'gre_apartment',
                'meta_key' => '_gre_apartment_apartment_code',
                'meta_value' => $code,
                'post_status' => 'any',
                'numberposts' => 1
            ]);
            if (empty($existing)) {
                wp_insert_post([
                    'post_type' => 'gre_apartment',
                    'post_status' => 'publish',
                    'post_title' => "شقة الدور {$i}",
                    'meta_input' => [
                        '_gre_apartment_apartment_code' => $code,
                        '_gre_apartment_apartment_number' => $i,
                        '_gre_apartment_floor_number' => $i,
                        '_gre_apartment_model_id' => $post_id,
                        '_gre_apartment_tower_id' => $tower_id,
                        '_gre_apartment_status' => $status,
                        '_gre_apartment_custom_price_usd' => $meta['price_usd'],
                        '_gre_apartment_custom_finishing_level' => $meta['finishing_level'],
                        '_gre_apartment_custom_finishing_type' => $meta['finishing_type']
                    ]
                ]);
            }
        }

        update_post_meta($post_id, '_gre_apartment_generated', '1');
    }
}

add_action('before_delete_post', function($post_id) {
    $post = get_post($post_id);
    if ($post && $post->post_type === 'gre_model') {
        $apartments = get_posts([
            'post_type' => 'gre_apartment',
            'posts_per_page' => -1,
            'meta_query' => [
                ['key' => '_gre_apartment_model_id', 'value' => $post_id]
            ]
        ]);
        foreach ($apartments as $apt) {
            wp_delete_post($apt->ID, true);
        }
    }
});
