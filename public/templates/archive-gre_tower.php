<?php
/**
 * قالب أرشيف الأبراج - archive-gre_tower.php
 */

get_header();

?>

<main id="primary" class="site-main gre-entity-archive">
    <section class="gre-entity-filter">
        <h1 class="page-title">قائمة الأبراج السكنية</h1>
        <form method="GET" class="gre-entity-filter-form">
            <div class="filter-row">

                <div class="filter-item">
                    <label for="search_query">بحث بالاسم:</label>
                    <input type="text" name="search_query" id="search_query" value="<?php echo esc_attr($_GET['search_query'] ?? '') ?>" placeholder="اسم البرج أو وصف">
                </div>

                <div class="filter-item">
                    <label for="city">المدينة:</label>
                    <select name="city" id="city">
                        <option value="">الكل</option>
                        <option value="أمانة العاصمة" <?php echo selected($_GET['city'] ?? '', 'أمانة العاصمة'); ?>>أمانة العاصمة</option>
                        <option value="عدن" <?php echo selected($_GET['city'] ?? '', 'عدن'); ?>>عدن</option>
                        <option value="تعز" <?php echo selected($_GET['city'] ?? '', 'تعز'); ?>>تعز</option>
                        <option value="الحديدة" <?php echo selected($_GET['city'] ?? '', 'الحديدة'); ?>>الحديدة</option>
                        <option value="حضرموت" <?php echo selected($_GET['city'] ?? '', 'حضرموت'); ?>>حضرموت</option>
                        <option value="إب" <?php echo selected($_GET['city'] ?? '', 'إب'); ?>>إب</option>
                        <option value="ذمار" <?php echo selected($_GET['city'] ?? '', 'ذمار'); ?>>ذمار</option>
                        <option value="عمران" <?php echo selected($_GET['city'] ?? '', 'عمران'); ?>>عمران</option>
                        <option value="صنعاء" <?php echo selected($_GET['city'] ?? '', 'صنعاء'); ?>>صنعاء</option>
                        <option value="مأرب" <?php echo selected($_GET['city'] ?? '', 'مأرب'); ?>>مأرب</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="district">المديرية:</label>
                    <input type="text" name="district" id="district" value="<?php echo esc_attr($_GET['district'] ?? '') ?>" placeholder="مثلاً: التحرير">
                </div>

                <div class="filter-item filter-submit">
                    <button type="submit">بحث</button>
                    <a href="<?php echo esc_url(get_post_type_archive_link('gre_tower')); ?>" class="reset-button">إعادة تعيين</a>
                </div>
            </div>
        </form>
    </section>

    <section class="gre-entity-grid gre-tower-archive">
        <?php
        $paged = get_query_var('paged') ?: 1;
        $args = [
            'post_type' => 'gre_tower',
            'posts_per_page' => 12,
            'paged' => $paged,
            'meta_query' => [],
            's' => sanitize_text_field($_GET['search_query'] ?? ''),
        ];

        $filters = [
            'city' => ['value' => sanitize_text_field($_GET['city'] ?? ''), 'compare' => 'LIKE'],
            'district' => ['value' => sanitize_text_field($_GET['district'] ?? ''), 'compare' => 'LIKE'],
        ];

        $args['meta_query'] = gre_build_meta_query($filters, 'tower');

        $towers_query = new WP_Query($args);

        if ($towers_query->have_posts()) :
            echo '<div class="entity-card-container">';
            while ($towers_query->have_posts()) : $towers_query->the_post();
                $post_id = get_the_ID();
                $image = get_the_post_thumbnail_url($post_id, 'medium') ?: plugin_dir_url(__FILE__) . '../assets/img/default-tower.jpg';
                $short_name = get_post_meta($post_id, '_gre_tower_short_name', true);
                $city = get_post_meta($post_id, '_gre_tower_city', true);
                $district = get_post_meta($post_id, '_gre_tower_district', true);
                $floors = get_post_meta($post_id, '_gre_tower_floors', true);
                $total_units = get_post_meta($post_id, '_gre_tower_total_units', true);

                // Combine city and district for display
                $location = !empty($city) ? esc_html($city) : '';
                if (!empty($district)) {
                    $location .= !empty($location) ? ' - ' . esc_html($district) : esc_html($district);
                }
        ?>
                <div class="entity-card">
                    <img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>" class="entity-thumb">
                    <div class="entity-info">
                        <h2 class="entity-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php if ($short_name): ?>
                            <p class="entity-meta">الاسم المختصر: <?php echo esc_html($short_name); ?></p>
                        <?php endif; ?>

                        <?php if ($location): ?>
                            <p class="entity-meta">الموقع: <?php echo esc_html($location); ?></p>
                        <?php endif; ?>

                        <?php if ($floors): ?>
                            <p class="entity-meta">عدد الأدوار: <?php echo esc_html($floors); ?></p>
                        <?php endif; ?>

                        <?php if ($total_units): ?>
                            <p class="entity-meta">عدد الشقق الإجمالي: <?php echo esc_html($total_units); ?></p>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="details-button">عرض التفاصيل</a>
                    </div>
                </div>
        <?php
            endwhile;
            echo '</div>';
            wp_reset_postdata();
            the_posts_pagination([
                'prev_text' => __('← السابق', 'textdomain'),
                'next_text' => __('التالي →', 'textdomain'),
            ]);
        else :
            echo '<p>لا توجد أبراج مطابقة حالياً.</p>';
        endif;
        ?>
    </section>
</main>

<?php get_footer(); ?>