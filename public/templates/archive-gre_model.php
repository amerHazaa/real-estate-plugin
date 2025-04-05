<?php
/**
 * قالب أرشيف النماذج - archive-gre_model.php
 */
get_header();
?>

<main id="primary" class="site-main gre-entity-archive">
    <section class="gre-entity-filter">
        <h1 class="page-title">قائمة النماذج السكنية</h1>
        <form method="GET" class="gre-entity-filter-form">
            <div class="filter-row">

                <div class="filter-item">
                    <label for="search_query">بحث بالاسم أو الكود:</label>
                    <input type="text" name="search_query" id="search_query" value="<?php echo esc_attr($_GET['search_query'] ?? '') ?>" placeholder="مثلاً: M1 أو اسم النموذج">
                </div>

                <div class="filter-item">
                    <label for="rooms">عدد الغرف:</label>
                    <select name="rooms" id="rooms">
                        <option value="">الكل</option>
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo selected($_GET['rooms'] ?? '', $i); ?>><?php echo $i; ?> غرف</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="bathrooms">عدد الحمامات:</label>
                    <select name="bathrooms" id="bathrooms">
                        <option value="">الكل</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo selected($_GET['bathrooms'] ?? '', $i); ?>><?php echo $i; ?> حمام</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="finishing_type">نوع التشطيب:</label>
                    <input type="text" name="finishing_type" id="finishing_type" value="<?php echo esc_attr($_GET['finishing_type'] ?? '') ?>" placeholder="مثلاً: سوبر لوكس">
                </div>

                <div class="filter-item">
                    <label for="price_min">السعر من:</label>
                    <input type="number" name="price_min" id="price_min" value="<?php echo esc_attr($_GET['price_min'] ?? '') ?>" placeholder="10000">
                </div>

                <div class="filter-item">
                    <label for="price_max">إلى:</label>
                    <input type="number" name="price_max" id="price_max" value="<?php echo esc_attr($_GET['price_max'] ?? '') ?>" placeholder="50000">
                </div>

                <div class="filter-item filter-submit">
                    <button type="submit">بحث</button>
                    <a href="<?php echo esc_url(get_post_type_archive_link('gre_model')); ?>" class="reset-button">إعادة تعيين</a>
                </div>
            </div>
        </form>
    </section>

    <section class="gre-entity-grid gre-model-archive"> <?php
        $paged = get_query_var('paged') ?: 1;
        $args = [
            'post_type' => 'gre_model',
            'posts_per_page' => 12,
            'paged' => $paged,
            'meta_query' => [],
            's' => sanitize_text_field($_GET['search_query'] ?? ''),
        ];

        $filters = [
            'rooms_count' => ['value' => intval($_GET['rooms'] ?? 0), 'type' => 'NUMERIC'],
            'bathrooms_count' => ['value' => intval($_GET['bathrooms'] ?? 0), 'type' => 'NUMERIC'],
            'finishing_type' => ['value' => sanitize_text_field($_GET['finishing_type'] ?? ''), 'compare' => 'LIKE'],
            'price_usd' => ['value' => [intval($_GET['price_min'] ?? 0), intval($_GET['price_max'] ?? 0)], 'type' => 'NUMERIC', 'compare' => 'BETWEEN'],
        ];

        $args['meta_query'] = gre_build_meta_query($filters, 'model');

        $models_query = new WP_Query($args);

        if ($models_query->have_posts()) :
            echo '<div class="entity-card-container">';
            while ($models_query->have_posts()) : $models_query->the_post();
                $post_id = get_the_ID();
                $image = get_the_post_thumbnail_url($post_id, 'medium') ?: plugin_dir_url(__FILE__) . '../assets/img/default-model.jpg';
                $area = get_post_meta($post_id, '_gre_model_area', true);
                $rooms = get_post_meta($post_id, '_gre_model_rooms_count', true);
                $baths = get_post_meta($post_id, '_gre_model_bathrooms_count', true);
                $price = get_post_meta($post_id, '_gre_model_price_usd', true);
            ?>
                <div class="entity-card">
                    <img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>" class="entity-thumb">
                    <div class="entity-info">
                        <h2 class="entity-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p class="entity-meta">المساحة: <?php echo esc_html($area); ?> م²</p>
                        <p class="entity-meta">الغرف: <?php echo esc_html($rooms); ?> | الحمامات: <?php echo esc_html($baths); ?></p>
                        <?php if ($price): ?>
                            <p class="entity-price">السعر: <?php echo esc_html($price); ?> $</p>
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
            echo '<p>لا توجد نماذج مطابقة حالياً.</p>';
        endif;

        wp_reset_postdata();
        ?>
    </section>
</main>

<?php get_footer(); ?>