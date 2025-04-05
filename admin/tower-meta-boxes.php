<?php
add_action('add_meta_boxes', 'gre_add_tower_meta_boxes');
add_action('save_post', 'gre_save_tower_meta');
add_filter('enter_title_here', 'gre_change_title_placeholder');

define('GRE_DEFAULT_LAT', 15.3694);
define('GRE_DEFAULT_LNG', 44.1910);

function gre_change_title_placeholder($title) {
    $screen = get_current_screen();
    if ($screen->post_type === 'gre_tower') return 'اسم البرج';
    return $title;
}

function gre_add_tower_meta_boxes() {
    add_meta_box('gre_tower_details', 'تفاصيل البرج', 'gre_render_tower_meta_box', 'gre_tower', 'normal', 'high');
}

function gre_get_tower_meta($post_id, $key, $default = '') {
    return get_post_meta($post_id, "_gre_tower_$key", true) ?: $default;
}

function gre_render_tower_meta_box($post) {
    wp_nonce_field('gre_save_tower_meta', 'gre_tower_meta_nonce');

    $fields = [
        'short_name' => '', 'floors' => '', 'location_desc' => '', 'location_lat' => '',
        'location_lng' => '', 'city' => 'أمانة العاصمة', 'district' => '', 'has_parking' => '',
        'total_units' => '', 'available_units' => '', 'building_type' => '', 'build_year' => '',
        'status' => '', 'elevators' => '', 'has_generator' => '', 'has_shops' => '',
        'general_description' => ''
    ];

    foreach ($fields as $key => $default) {
        $fields[$key] = gre_get_tower_meta($post->ID, $key, $default);
    }

    echo '<style>
        .gre-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }
        .gre-half { grid-column: span 2; }
        .gre-full { grid-column: span 4; }
        .gre-map-wrap { display: flex; gap: 20px; margin-top: 20px; }
        .gre-map { width: 50%; height: 400px; }
        .gre-map-fields { width: 50%; display: flex; flex-direction: column; gap: 10px; }
        input[type="number"] { max-width: 100%; }
        select, input[type="text"], textarea { width: 100%; }
    </style>';

    echo '<div class="gre-grid">';
    echo '<div><label>الاسم المختصر (بالإنجليزية)</label><input type="text" name="gre_tower_short_name" value="' . esc_attr($fields['short_name']) . '" style="width: 100px;" /></div>';
    echo '<div><label>عدد الأدوار</label><input type="number" name="gre_tower_floors" value="' . esc_attr($fields['floors']) . '" /></div>';
    echo '<div><label>عدد المصاعد</label><input type="number" name="gre_tower_elevators" value="' . esc_attr($fields['elevators']) . '" /></div>';
    echo '<div><label>سنة البناء</label><input type="number" name="gre_tower_build_year" value="' . esc_attr($fields['build_year']) . '" /></div>';

    $checkboxes = [
        'has_generator' => 'مولد كهربائي',
        'has_parking' => 'موقف سيارات',
        'has_shops' => 'محلات تجارية'
    ];
    foreach ($checkboxes as $key => $label) {
        echo "<div><label><input type='checkbox' name='gre_tower_$key' value='1'" . checked($fields[$key], '1', false) . "> $label</label></div>";
    }

    echo '<div><label>نوع المبنى</label><select name="gre_tower_building_type">
        <option value="tower"' . selected($fields['building_type'], 'tower', false) . '>برج بعدة نماذج وشقق</option>
        <option value="villa"' . selected($fields['building_type'], 'villa', false) . '>فلة بنموذج وحيد</option>
    </select></div>';
    echo '</div>';

    gre_render_location_fields($fields);

    echo '<div class="gre-grid">';
    echo '<div class="gre-full"><label>الوصف المكاني</label><textarea name="gre_tower_location_desc">' . esc_textarea($fields['location_desc']) . '</textarea></div>';
    echo '<div class="gre-full"><label>الوصف العام</label><textarea name="gre_tower_general_description">' . esc_textarea($fields['general_description']) . '</textarea></div>';
    echo '</div>';

    echo '<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>';
    echo '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>';
    echo '<script>
        const GRE_DEFAULT_LAT = ' . GRE_DEFAULT_LAT . ';
        const GRE_DEFAULT_LNG = ' . GRE_DEFAULT_LNG . ';

        document.addEventListener("DOMContentLoaded", function() {
            var latInput = document.getElementById("gre_tower_location_lat");
            var lngInput = document.getElementById("gre_tower_location_lng");
            var lat = parseFloat(latInput.value) || GRE_DEFAULT_LAT;
            var lng = parseFloat(lngInput.value) || GRE_DEFAULT_LNG;
            var map = L.map("map").setView([lat, lng], 13);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);
            var marker = L.marker([lat, lng], {draggable: true}).addTo(map);
            marker.on("dragend", function(e) {
                var position = marker.getLatLng();
                latInput.value = position.lat.toFixed(6);
                lngInput.value = position.lng.toFixed(6);
            });
        });
    </script>';
}

function gre_render_location_fields($fields) {
    echo '<div class="gre-map-wrap">';
    echo '<div id="map" class="gre-map"></div>';
    echo '<div class="gre-map-fields">';
    echo '<label>اسم المدينة / المنطقة</label><select name="gre_tower_city">
        <option value="أمانة العاصمة"' . selected($fields['city'], 'أمانة العاصمة', false) . '>أمانة العاصمة</option>
        <option value="عدن"' . selected($fields['city'], 'عدن', false) . '>عدن</option>
        <option value="تعز"' . selected($fields['city'], 'تعز', false) . '>تعز</option>
        <option value="الحديدة"' . selected($fields['city'], 'الحديدة', false) . '>الحديدة</option>
        <option value="حضرموت"' . selected($fields['city'], 'حضرموت', false) . '>حضرموت</option>
        <option value="إب"' . selected($fields['city'], 'إب', false) . '>إب</option>
        <option value="ذمار"' . selected($fields['city'], 'ذمار', false) . '>ذمار</option>
        <option value="عمران"' . selected($fields['city'], 'عمران', false) . '>عمران</option>
        <option value="صنعاء"' . selected($fields['city'], 'صنعاء', false) . '>صنعاء</option>
        <option value="مأرب"' . selected($fields['city'], 'مأرب', false) . '>مأرب</option>
    </select>';
    echo '<label>اسم المديرية</label><input type="text" name="gre_tower_district" value="' . esc_attr($fields['district']) . '" />';
    echo '<label>خط العرض</label><input type="text" name="gre_tower_location_lat" id="gre_tower_location_lat" value="' . esc_attr($fields['location_lat']) . '" />';
    echo '<label>خط الطول</label><input type="text" name="gre_tower_location_lng" id="gre_tower_location_lng" value="' . esc_attr($fields['location_lng']) . '" />';
    echo '<label>عدد الشقق الإجمالي</label><input type="number" name="gre_tower_total_units" value="' . esc_attr($fields['total_units']) . '" readonly />';
    echo '<label>عدد الشقق المتوفرة</label><input type="number" name="gre_tower_available_units" value="' . esc_attr($fields['available_units']) . '" readonly />';
    echo '</div></div>';
}

function gre_save_tower_field($post_id, $key) {
    $val = sanitize_text_field($_POST["gre_tower_$key"] ?? '');
    update_post_meta($post_id, "_gre_tower_$key", $val);
}

function gre_save_tower_meta($post_id) {
    if (
        !isset($_POST['gre_tower_meta_nonce']) ||
        !wp_verify_nonce($_POST['gre_tower_meta_nonce'], 'gre_save_tower_meta') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        !current_user_can('edit_post', $post_id)
    ) return;

    $fields = ['short_name', 'floors', 'location_desc', 'location_lat', 'location_lng', 'city', 'district', 'has_parking',
               'total_units', 'available_units', 'building_type', 'build_year', 'status', 'elevators', 'has_generator',
               'has_shops', 'general_description'];

    foreach ($fields as $field) {
        gre_save_tower_field($post_id, $field);
    }
}
?>