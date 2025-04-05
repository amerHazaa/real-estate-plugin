<?php
/**
 * صفحة البحث الموحد - page-gre-search.php
 */

get_header();

?>
<main id="primary" class="site-main gre-entity-archive">
    <section class="gre-entity-filter">
        <h1 class="page-title">البحث الموحد عن الأبراج والنماذج والشقق</h1>
        <form method="GET" class="gre-entity-filter-form">
            <div class="filter-row">
                <div class="filter-item">
                    <label for="search_query">كلمة البحث:</label>
                    <input type="text" name="search_query" id="search_query" value="<?php echo esc_attr($_GET['search_query'] ?? '') ?>" placeholder="ابحث بالاسم، الكود، الموقع...">
                </div>

                <div class="filter-item">
                    <label for="type">نوع النتيجة:</label>
                    <select name="type" id="type">
                        <option value="">الكل</option>
                        <option value="gre_apartment" <?php selected($_GET['type'] ?? '', 'gre_apartment'); ?>>شقق</option>
                        <option value="gre_model" <?php selected($_GET['type'] ?? '', 'gre_model'); ?>>نماذج</option>
                        <option value="gre_tower" <?php selected($_GET['type'] ?? '', 'gre_tower'); ?>>أبراج</option>
                    </select>
                </div>

                <div class="filter-item filter-submit">
                    <button type="submit">بحث</button>
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="reset-button">إعادة تعيين</a>
                </div>
            </div>
        </form>
    </section>

    <section class="gre-entity-grid">
        <?php
        $search_query = sanitize_text_field($_GET['search_query'] ?? '');
        $post_type = sanitize_text_field($_GET['type'] ?? '');

        $args = [
            'post_type' => $post_type ?: ['gre_apartment', 'gre_model', 'gre_tower'],
            'posts_per_page' => 12,
            'paged' => get_query_var('paged') ?: 1,
            's' => $search_query,
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            echo '<div class="entity-card-container">';
            while ($query->have_posts()) : $query->the_post();
                $type = get_post_type();
                $post_id = get_the_ID();
                $title = get_the_title();
                $link = get_permalink();
                $thumb = get_the_post_thumbnail_url($post_id, 'medium') ?: plugin_dir_url(__FILE__) . '../assets/img/default-' . str_replace('gre_', '', $type) . '.jpg';

                echo '<div class="entity-card">';
                echo '<img src="' . esc_url($thumb) . '" class="entity-thumb" alt="' . esc_attr($title) . '">';
                echo '<div class="entity-info">';
                echo '<h2 class="entity-title"><a href="' . esc_url($link) . '">' . esc_html($title) . '</a></h2>';

                // تفاصيل مخصصة لكل نوع
                if ($type === 'gre_apartment') {
                    $num = get_post_meta($post_id, '_gre_apartment_apartment_number', true);
                    $floor = get_post_meta($post_id, '_gre_apartment_floor_number', true);
                    $status = gre_get_apartment_status_label(get_post_meta($post_id, '_gre_apartment_status', true));
                    echo '<p class="apartment-meta">رقم الشقة: ' . esc_html($num) . '</p>';
                    echo '<p class="apartment-meta">الدور: ' . esc_html($floor) . '</p>';
                    echo '<p class="apartment-meta">الحالة: ' . esc_html($status) . '</p>';
                } elseif ($type === 'gre_model') {
                    $rooms = get_post_meta($post_id, '_gre_model_rooms_count', true);
                    $area = get_post_meta($post_id, '_gre_model_area', true);
                    echo '<p class="model-meta">المساحة: ' . esc_html($area) . ' م²</p>';
                    echo '<p class="model-meta">عدد الغرف: ' . esc_html($rooms) . '</p>';
                } elseif ($type === 'gre_tower') {
                    $short = get_post_meta($post_id, '_gre_tower_short_name', true);
                    $city = get_post_meta($post_id, '_gre_tower_city', true);
                    echo '<p class="tower-meta">الاسم المختصر: ' . esc_html($short) . '</p>';
                    echo '<p class="tower-meta">المدينة: ' . esc_html($city) . '</p>';
                }

                echo '<a href="' . esc_url($link) . '" class="details-button">عرض التفاصيل</a>';
                echo '</div></div>';

            endwhile;
            echo '</div>';

            the_posts_pagination([
                'prev_text' => __('← السابق', 'textdomain'),
                'next_text' => __('التالي →', 'textdomain'),
            ]);

        else :
            echo '<p>لا توجد نتائج مطابقة حالياً.</p>';
        endif;

        wp_reset_postdata();
        ?>
    </section>
</main>

<?php get_footer(); ?>
