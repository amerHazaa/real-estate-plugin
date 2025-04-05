<?php
/**
 * قالب أرشيف الشقق - archive-gre_apartment.php
 */
get_header();
?>

<main id="primary" class="site-main gre-entity-archive">
    <section class="gre-entity-filter">
        <h1 class="page-title">قائمة الشقق السكنية</h1>
        <form method="GET" class="gre-entity-filter-form">
            <div class="filter-row">
                <div class="filter-item">
                    <label for="status">الحالة:</label>
                    <select name="status" id="status">
                        <option value="">الكل</option>
                        <option value="available" <?php echo selected($_GET['status'] ?? '', 'available'); ?>>متاحة</option>
                        <option value="sold" <?php echo selected($_GET['status'] ?? '', 'sold'); ?>>مباعة</option>
                        <option value="under_preparation" <?php echo selected($_GET['status'] ?? '', 'under_preparation'); ?>>قيد التجهيز</option>
                        <option value="for_finishing" <?php echo selected($_GET['status'] ?? '', 'for_finishing'); ?>>تحتاج تشطيب</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="floor">الدور:</label>
                    <select name="floor" id="floor">
                        <option value="">الكل</option>
                        <?php
                        // تحسين استعلام الأدوار
                        global $wpdb;
                        $query = "
                            SELECT DISTINCT meta_value
                            FROM {$wpdb->postmeta}
                            WHERE meta_key = '_gre_apartment_floor_number'
                            ORDER BY CAST(meta_value AS UNSIGNED)
                        ";
                        $distinct_floors = $wpdb->get_col($query);

                        if (!empty($distinct_floors)) {
                            foreach ($distinct_floors as $floor_num) {
                                if (!is_numeric($floor_num)) continue;
                                $selected = ($_GET['floor'] ?? '') == $floor_num ? 'selected' : '';
                                echo "<option value='" . esc_attr($floor_num) . "' $selected>" . esc_html($floor_num) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="price_min">السعر من:</label>
                    <input type="number" name="price_min" id="price_min" value="<?php echo esc_attr($_GET['price_min'] ?? '') ?>" placeholder="10000">
                </div>

                <div class="filter-item">
                    <label for="price_max">إلى:</label>
                    <input type="number" name="price_max" id="price_max" value="<?php echo esc_attr($_GET['price_max'] ?? '') ?>" placeholder="50000">
                </div>

                <div class="filter-item">
                    <label for="search_query">بحث برقم الشقة أو النموذج:</label>
                    <input type="text" name="search_query" id="search_query" value="<?php echo esc_attr(sanitize_text_field($_GET['search_query'] ?? '')) ?>" placeholder="مثلاً: A5-T3 أو M1">
                </div>

                <div class="filter-item filter-submit">
                    <button type="submit">بحث</button>
                    <a href="<?php echo esc_url(get_post_type_archive_link('gre_apartment')); ?>" class="reset-button">إعادة تعيين</a>
                </div>
            </div>
        </form>
    </section>

    <section class="gre-entity-grid gre-apartment-archive">
        <?php
        $paged = get_query_var('paged') ?: 1;
        $args = [
            'post_type' => 'gre_apartment',
            'posts_per_page' => 12,
            'paged' => $paged,
            'meta_query' => [],
        ];

        $filters = [
            'status' => ['value' => sanitize_text_field($_GET['status'] ?? '')],
            'floor_number' => ['value' => intval($_GET['floor'] ?? 0), 'type' => 'NUMERIC'],
            'custom_price_usd' => ['value' => [intval($_GET['price_min'] ?? 0), intval($_GET['price_max'] ?? 0)], 'type' => 'NUMERIC', 'compare' => 'BETWEEN'],
        ];
        $args['meta_query'] = gre_build_meta_query($filters, 'apartment');

        // بحث نصي برقم الشقة أو اسم النموذج
        $search_query = sanitize_text_field($_GET['search_query'] ?? '');
        if (!empty($search_query)) {
            $args['meta_query']['relation'] = 'OR';
            $args['meta_query'][] = [
                'key' => '_gre_apartment_apartment_number',
                'value' => sanitize_text_field($search_query),
                'compare' => 'LIKE',
            ];

            $model_id = gre_get_model_id_by_title(sanitize_text_field($search_query));
            if ($model_id) {
                $args['meta_query'][] = [
                    'key' => '_gre_apartment_model_id',
                    'value' => $model_id,
                    'compare' => '=',
                ];
            }
        }

        $apartments_query = new WP_Query($args);

        if ($apartments_query->have_posts()) :
            echo '<div class="apartment-card-container">';
            while ($apartments_query->have_posts()) : $apartments_query->the_post();
                $post_id = get_the_ID();
                $image = get_the_post_thumbnail_url($post_id, 'medium') ?: plugin_dir_url(__FILE__) . '../assets/img/default-apartment.jpg';
                $status = get_post_meta($post_id, '_gre_apartment_status', true);
                $price = get_post_meta($post_id, '_gre_apartment_custom_price_usd', true);
                $floor = get_post_meta($post_id, '_gre_apartment_floor_number', true);
                $apartment_number = get_post_meta($post_id, '_gre_apartment_apartment_number', true);
                ?>
                <div class="apartment-card">
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="apartment-thumb">
                    <div class="apartment-info">
                        <h2 class="apartment-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p class="apartment-meta">رقم الشقة: <?php echo esc_html($apartment_number); ?></p>
                        <p class="apartment-meta">الدور: <?php echo esc_html($floor); ?></p>
                        <p class="apartment-meta">الحالة: <?php echo esc_html(gre_get_apartment_status_label($status)); ?></p>
                        <?php if ($price): ?>
                            <p class="apartment-price">السعر: <?php echo esc_html($price); ?> $</p>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="details-button">عرض التفاصيل</a>
                    </div>
                </div>
                <?php
            endwhile;
            echo '</div>';
            the_posts_pagination([
                'prev_text' => __('← السابق', 'textdomain'),
                'next_text' => __('التالي →', 'textdomain'),
            ]);
        else :
            echo '<p>لا توجد شقق متاحة حالياً.</p>';
        endif;

        wp_reset_postdata();
        ?>
    </section>
</main>

<?php get_footer(); ?>