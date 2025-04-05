<?php
/**
 * دوال الواجهة الأمامية العامة للإضافة.
 */

/**
 * تسجيل وتضمين أنماط وخطوط الإضافة.
 */
function gre_enqueue_styles() {
    // تضمين Leaflet CSS و JS
    wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], null, true);

    // تضمين ملف أنماط الكيان
    wp_enqueue_style('gre-entity-styles', plugin_dir_url(__FILE__) . 'assets/css/gre-entity-single.css', [], '1.0.0');
}
add_action('wp_enqueue_scripts', 'gre_enqueue_styles');

/**
 * دالة مساعدة لتوليد استعلام meta_query ديناميكي.
 *
 * @param array $filters مصفوفة الفلاتر.
 * @param string $entity_type نوع الكيان (apartment, model, tower).
 * @return array استعلام meta_query.
 */
function gre_build_meta_query($filters, $entity_type) {
    $meta_query = [];

    foreach ($filters as $key => $filter) {
        $meta_key = "_gre_{$entity_type}_{$key}";
        $value = $filter['value'];
        $compare = $filter['compare'] ?? '=';
        $type = $filter['type'] ?? 'CHAR';

        if (empty($value) && $type !== 'ARRAY') continue;

        if (is_array($value)) {
            $meta_query[] = [
                'key' => $meta_key,
                'value' => $value,
                'compare' => $compare,
                'type' => $type,
            ];
        } else {
            $meta_query[] = [
                'key' => $meta_key,
                'value' => sanitize_text_field($value),
                'compare' => $compare,
                'type' => $type,
            ];
        }
    }

    return $meta_query;
}

/**
 * دالة مساعدة لاسترداد معرف النموذج من عنوانه.
 *
 * @param string $title عنوان النموذج.
 * @return int معرف النموذج.
 */
function gre_get_model_id_by_title($title) {
    $model = get_page_by_title($title, OBJECT, 'gre_model');
    return $model ? $model->ID : 0;
}

/**
 * تعرض تفاصيل كيان محدد (نموذج، شقة، برج).
 *
 * @param int $post_id معرف المنشور.
 * @param string $type نوع الكيان: model | apartment | tower.
 */
function gre_render_entity_details($post_id, $type = 'model') {
    $fields_map = [];

    if ($type === 'model') {
        $fields_map = [
            '_gre_model_code'        => ['label' => '<span class="dashicons dashicons-tag"></span> كود النموذج', 'type' => 'text'],
            '_gre_model_area'        => ['label' => '<span class="dashicons dashicons-editor-expand"></span> المساحة الإجمالية (م²)', 'type' => 'text'],
            '_gre_model_rooms_count'   => ['label' => '<span class="dashicons dashicons-admin-home"></span> عدد الغرف', 'type' => 'number'],
            '_gre_model_bathrooms_count' => ['label' => '<span class="dashicons dashicons-bathroom"></span> عدد الحمامات', 'type' => 'number'],
            '_gre_model_finishing_type' => ['label' => '<span class="dashicons dashicons-admin-appearance"></span> نوع التشطيب', 'type' => 'text'],
            '_gre_model_finishing_level' => ['label' => '<span class="dashicons dashicons-editor-ul"></span> مستوى التشطيب', 'type' => 'text'],
        ];
    } elseif ($type === 'apartment') {
        $fields_map = [
            '_gre_apartment_apartment_number' => ['label' => '<span class="dashicons dashicons-building"></span> رقم الشقة', 'type' => 'number'],
            '_gre_apartment_status'        => ['label' => '<span class="dashicons dashicons-info-outline"></span> الحالة', 'type' => 'status'],
            '_gre_apartment_floor_number'   => ['label' => '<span class="dashicons dashicons-editor-textcolor"></span> الدور', 'type' => 'number'],
            '_gre_apartment_custom_price_usd' => ['label' => '<span class="dashicons dashicons-cart"></span> السعر', 'type' => 'price'],
        ];
    } elseif ($type === 'tower') {
        $fields_map = [
            '_gre_tower_short_name'      => ['label' => '<span class="dashicons dashicons-tag"></span> الاسم المختصر', 'type' => 'text'],
            '_gre_tower_floors'          => ['label' => '<span class="dashicons dashicons-editor-ol"></span> عدد الأدوار', 'type' => 'number'],
            '_gre_tower_city'            => ['label' => '<span class="dashicons dashicons-location-alt"></span> المدينة', 'type' => 'text'],
            '_gre_tower_district'        => ['label' => '<span class="dashicons dashicons-admin-site"></span> المديرية', 'type' => 'text'],
            '_gre_tower_build_year'      => ['label' => '<span class="dashicons dashicons-clock"></span> سنة البناء', 'type' => 'number'],
            '_gre_tower_building_type'   => ['label' => '<span class="dashicons dashicons-admin-multisite"></span> نوع المبنى', 'type' => 'text'],
            '_gre_tower_status'          => ['label' => '<span class="dashicons dashicons-info-outline"></span> الحالة', 'type' => 'status'],
            '_gre_tower_total_units'     => ['label' => '<span class="dashicons dashicons-editor-kitchensink"></span> عدد الشقق الإجمالي', 'type' => 'number'],
            '_gre_tower_available_units' => ['label' => '<span class="dashicons dashicons-editor-ul"></span> عدد الشقق المتوفرة', 'type' => 'number'],
        ];
    }

    if (empty($fields_map)) return;

    echo '<ul class="gre-entity-details-list">';
    foreach ($fields_map as $meta_key => $field) {
        $value = get_post_meta($post_id, $meta_key, true);
        if (!empty($value)) {
            $display_value = esc_html($value);
            if ($field['type'] === 'status' && $meta_key === '_gre_apartment_status') {
                $display_value = esc_html(gre_get_apartment_status_label($value));
            }
            echo '<li><span class="detail-label">' . $field['label'] . ':</span> ' . $display_value . '</li>';
        }
    }
    echo '</ul>';
}

/**
 * إرجاع تسمية الحالة المقابلة لقيمة الحالة.
 *
 * @param string $status قيمة الحالة.
 * @return string تسمية الحالة.
 */
function gre_get_apartment_status_label($status) {
    $status_labels = [
        'available' => 'متاحة',
        'sold' => 'مباعة',
        'under_preparation' => 'قيد التجهيز',
        'for_finishing' => 'تحتاج تشطيب',
    ];

    return $status_labels[$status] ?? 'غير محدد';
}

/**
 * تعرض خريطة موقع البرج.
 *
 * @param int $post_id معرف البرج.
 */
function gre_render_tower_location_map($post_id) {
    $lat = get_post_meta($post_id, '_gre_tower_location_lat', true);
    $lng = get_post_meta($post_id, '_gre_tower_location_lng', true);
    $location_desc = get_post_meta($post_id, '_gre_tower_location_desc', true);

    if ($lat && $lng) {
        echo '<div class="map-container">';
        echo '<iframe src="https://www.google.com/maps?q=' . esc_attr($lat) . ',' . esc_attr($lng) . '&z=17&output=embed" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        echo '</div>';
    }

    if ($location_desc) {
        echo '<div class="location-description">';
        echo '<p>' . esc_html($location_desc) . '</p>';
        echo '</div>';
    }
}