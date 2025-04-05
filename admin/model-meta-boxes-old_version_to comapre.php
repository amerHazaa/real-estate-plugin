<?php
add_action('add_meta_boxes', 'gre_add_model_meta_boxes');
add_action('save_post', 'gre_save_model_meta', 10, 2);
add_action('wp_insert_post_data', 'gre_set_model_post_title_if_empty', 10, 2);

add_action('admin_enqueue_scripts', 'gre_enqueue_model_script');
function gre_enqueue_model_script($hook) {
    if (in_array($hook, ['post-new.php', 'post.php']) && get_post_type() === 'gre_model') {
        wp_enqueue_script(
            'gre-model-meta-script',
            plugin_dir_url(__FILE__) . '../assets/js/gre-model-meta.js',
            ['jquery'],
            false,
            true
        );
    }
}

add_action('wp_ajax_gre_generate_model_short_name', 'gre_ajax_generate_model_short_name');

function gre_ajax_generate_model_short_name() {
    $tower_id = intval($_GET['tower_id']);
    $short = get_post_meta($tower_id, '_gre_tower_short_name', true);
    $count = count(get_posts(['post_type' => 'gre_model', 'post_status' => 'any', 'meta_query' => [['key' => '_gre_model_tower_id', 'value' => $tower_id]]])) + 1;
    wp_send_json_success(['short_name' => $short . '-M' . $count]);
}

function gre_set_model_post_title_if_empty($data, $postarr) {
    if ($data['post_type'] === 'gre_model' && empty($data['post_title']) && !empty($postarr['ID']) && $short_name = get_post_meta($postarr['ID'], '_gre_model_code', true)) {
        $data['post_title'] = $short_name;
    }
    return $data;
}

function gre_add_model_meta_boxes() {
    add_meta_box('gre_model_details', 'تفاصيل النموذج', 'gre_render_model_meta_box', 'gre_model', 'normal', 'high');
}

function gre_get_model_meta($post_id, $key, $default = '') {
    return get_post_meta($post_id, "_gre_model_$key", true) ?: $default;
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
        $fields[$key] = gre_get_model_meta($post->ID, $key, $default);
    }

    if (get_current_screen()->action === 'add' && empty($fields['code'])) {
       // $fields['code'] = gre_generate_model_code();
        $fields['tower_id'] = gre_get_model_meta($post->ID, 'tower_id') ?: (get_posts(['post_type' => 'gre_tower', 'posts_per_page' => 1, 'orderby' => 'ID', 'order' => 'DESC'])[0]->ID ?? '');
    }

    echo '<style>
        .gre-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .gre-full { grid-column: span 3; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; }
    </style>';

    echo '<div class="gre-grid">';
    $towers = get_posts(['post_type' => 'gre_tower', 'numberposts' => -1, 'orderby' => 'date', 'order' => 'DESC']);
    echo '<div><label>البرج التابع</label><select name="gre_model_tower_id" id="gre_model_tower_id">';
    foreach ($towers as $tower) {
        echo "<option value='{$tower->ID}'" . selected($fields['tower_id'], $tower->ID, false) . ">{$tower->post_title}</option>";
    }
    echo '</select></div>';
    echo '<div><label>الرمز المختصر</label><input type="text" name="gre_model_code" id="gre_model_code" value="' . esc_attr($fields['code']) . '" /></div>';
    echo '<div><label>المساحة الإجمالية</label><input type="text" name="gre_model_area" value="' . esc_attr($fields['area']) . '" /></div>';
    echo '<div><label>عدد الغرف</label><input type="number" name="gre_model_rooms_count" value="' . esc_attr($fields['rooms_count']) . '" /></div>';
    echo '<div><label>عدد الحمّامات</label><input type="number" name="gre_model_bathrooms_count" value="' . esc_attr($fields['bathrooms_count']) . '" /></div>';
    echo '<div><label>عدد المطابخ</label><input type="number" name="gre_model_kitchens_count" value="' . esc_attr($fields['kitchens_count']) . '" /></div>';
    $select_fields = [
        'finishing_level' => ['ready' => 'مشطب', 'semi' => 'نصف تشطيب', 'none' => 'بدون تشطيب'],
        'finishing_type' => ['luxury' => 'فاخر', 'deluxe' => 'ديلوكس', 'normal' => 'عادي'],
        'default_status' => ['available' => 'متاحة', 'sold' => 'مباعة', 'under_preparation' => 'قيد التجهيز', 'for_finishing' => 'تحتاج تشطيب']
    ];
    foreach ($select_fields as $key => $options) {
        echo "<div><label>" . ($key === 'default_status' ? 'حالة الشقق الافتراضية' : ($key === 'finishing_level' ? 'مستوى التشطيب' : 'نوع التشطيب')) . "</label><select name='gre_model_$key'>";
        foreach ($options as $value => $label) {
            echo "<option value='$value'" . selected($fields[$key], $value, false) . ">$label</option>";
        }
        echo "</select></div>";
    }
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
        echo "<div><label><input type='checkbox' name='gre_model_$key' value='1'" . checked($fields[$key], '1', false) . " /> $label</label></div>";
    }
    echo '<div class="gre-full"><label>وصف عام للنموذج</label><textarea name="gre_model_description">' . esc_textarea($fields['description']) . '</textarea></div>';
    echo '</div>';

echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    const towerSelect = document.getElementById("gre_model_tower_id");
    const shortNameField = document.getElementById("gre_model_code");
    const titleField = document.getElementById("title"); // <-- إضافة: الحصول على حقل العنوان الرئيسي
    const isAddNewScreen = document.body.classList.contains("post-new-php"); // <-- إضافة: التحقق إذا كنا في صفحة إضافة جديد

    if (towerSelect && shortNameField && titleField) { // تأكد من وجود جميع الحقول
        // استدعاء التحديث عند تغيير البرج
        towerSelect.addEventListener("change", function() {
            if (this.value) { // تأكد من وجود قيمة للبرج
                updateShortName(this.value);
            }
        });

        // استدعاء التحديث عند تحميل صفحة "إضافة جديد" إذا كان هناك برج محدد
        if (isAddNewScreen && towerSelect.value) {
            updateShortName(towerSelect.value);
        }
    }

    async function updateShortName(towerId) {
        if (!towerId) return; // لا تفعل شيئاً إذا لم يتم تحديد برج

        try {
            const res = await fetch(ajaxurl + "?action=gre_generate_model_short_name&tower_id=" + encodeURIComponent(towerId));
            if (!res.ok) {
               throw new Error(`HTTP error! status: ${res.status}`);
            }
            const data = await res.json();

            if (data.success && data.short_name) { // تأكد من نجاح العملية ووجود الاسم المختصر
                // تحديث حقل الرمز المختصر
                if (shortNameField) {
                    shortNameField.value = data.short_name;
                }
                // تحديث حقل العنوان الرئيسي فقط إذا كان فارغاً
                if (titleField && titleField.value.trim() === "") {
                    titleField.value = data.short_name;
                }
            } else {
                console.error("AJAX call failed or returned no short name:", data);
            }
        } catch (error) {
            console.error("Error fetching short name:", error);
        }
    }
});
</script>';
}

function gre_generate_model_code($tower_id = 0) {
    $tower_id = $tower_id ?: ($_POST['gre_model_tower_id'] ?? 0);
    if (!$tower_id) return '';

    $tower_short = get_post_meta($tower_id, '_gre_tower_short_name', true);
    for ($i = 1; $i <= 10; $i++) {
        $candidate = $tower_short . '-M' . $i;
        if (empty(get_posts(['post_type' => 'gre_model', 'post_status' => 'any', 'meta_key' => '_gre_model_code', 'meta_value' => $candidate, 'numberposts' => 1]))) {
            return $candidate;
        }
    }
    return '';
}

function gre_save_model_meta($post_id, $post) {
    if (!isset($_POST['gre_model_meta_nonce']) || !wp_verify_nonce($_POST['gre_model_meta_nonce'], 'gre_save_model_meta') || defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || !current_user_can('edit_post', $post_id)) return;

    $fields = [
        'tower_id','code','description','area','rooms_count','bathrooms_count','kitchens_count',
        'finishing_level','finishing_type','default_status','availability_date','price_usd',
        'has_living_room','has_majlis','has_balcony','has_storage','has_equipped_kitchen',
        'has_ac','has_fire_safety','has_cctv','has_water_meter','has_electricity_meter',
        'has_internet','has_wc_western','has_jacuzzi_sauna'
    ];

    foreach ($fields as $field) {
        update_post_meta($post_id, "_gre_model_$field", sanitize_text_field($_POST["gre_model_$field"] ?? ''));
    }

    if (empty(gre_get_model_meta($post_id, 'code')) && $tower_id = gre_get_model_meta($post_id, 'tower_id')) {
        update_post_meta($post_id, '_gre_model_code', gre_generate_model_code($tower_id));
    }

    if (gre_get_post_meta($post_id, '_gre_apartment_generated', true) !== '1' && $tower_id = gre_get_model_meta($post_id, 'tower_id') && $model_code = gre_get_model_meta($post_id, 'code')) {
        $tower_short = get_post_meta($tower_id, '_gre_tower_short_name', true);
        $floors = (int) get_post_meta($tower_id, '_gre_tower_floors', true);
        $status = gre_get_model_meta($post_id, 'default_status');

        for ($i = 1; $i <= $floors; $i++) {
            $code = "{$tower_short}-{$model_code}-{$i}";
            if (empty(get_posts(['post_type' => 'gre_apartment', 'meta_key' => '_gre_apartment_apartment_code', 'meta_value' => $code, 'post_status' => 'any', 'numberposts' => 1]))) {
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
                        '_gre_apartment_custom_price_usd' => gre_get_model_meta($post_id, 'price_usd'),
                        '_gre_apartment_custom_finishing_level' => gre_get_model_meta($post_id, 'finishing_level'),
                        '_gre_apartment_custom_finishing_type' => gre_get_model_meta($post_id, 'finishing_type')
                    ]
                ]);
            }
        }
        update_post_meta($post_id, '_gre_apartment_generated', '1');
    }
}

add_action('before_delete_post', function($post_id) {
    if (get_post_type($post_id) === 'gre_model') {
        foreach (get_posts(['post_type' => 'gre_apartment', 'posts_per_page' => -1, 'meta_query' => [['key' => '_gre_apartment_model_id', 'value' => $post_id]]]) as $apt) {
            wp_delete_post($apt->ID, true);
        }
    }
});