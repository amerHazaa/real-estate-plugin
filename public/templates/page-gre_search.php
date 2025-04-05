<?php
/**
 * قالب صفحة البحث الموحد - page-gre_search.php
 */
get_header();

$q = sanitize_text_field($_GET['q'] ?? '');
$type = $_GET['type'] ?? '';
$city = sanitize_text_field($_GET['city'] ?? '');
$price_min = intval($_GET['price_min'] ?? 0);
$price_max = intval($_GET['price_max'] ?? 0);

$post_type = $type ?: ['gre_tower', 'gre_model', 'gre_apartment'];

$args = [
    'post_type' => $post_type,
    'posts_per_page' => 12,
    'paged' => get_query_var('paged') ?: 1,
    's' => $q,
    'meta_query' => [],
];

// فلترة المدينة
if ($city) {
    $args['meta_query'][] = [
        'key' => $type === 'gre_model' ? '_gre_model_city' : '_gre_tower_city',
        'value' => $city,
        'compare' => 'LIKE'
    ];
}

// فلترة السعر للنماذج أو الشقق
if ($price_min && $price_max) {
    $args['meta_query'][] = [
        'key' => $type === 'gre_model' ? '_gre_model_price_usd' : '_gre_apartment_custom_price_usd',
        'value' => [$price_min, $price_max],
        'type' => 'NUMERIC',
        'compare' => 'BETWEEN'
    ];
}

$query = new WP_Query($args);
?>

<main id="primary" class="site-main gre-entity-archive">
    <section class="gre-entity-filter">
        <h1 class="page-title">البحث الموحد</h1>
        <form method="get" class="gre-entity-filter-form">
            <div class="filter-row">
                <input type="text" name="q" placeholder="ابحث بالاسم أو الكود..." value="<?php echo esc_attr($q); ?>">
                <select name="type">
                    <option value="">الكل</option>
                    <option value="gre_tower" <?php selected($type, 'gre_tower'); ?>>أبراج</option>
                    <option value="gre_model" <?php selected($type, 'gre_model'); ?>>نماذج</option>
                    <option value="gre_apartment" <?php selected($type, 'gre_apartment'); ?>>شقق</option>
                </select>
                <select name="city">
                    <option value="">المدينة</option>
                    <option value="صنعاء" <?php selected($city, 'صنعاء'); ?>>صنعاء</option>
                    <option value="عدن" <?php selected($city, 'عدن'); ?>>عدن</option>
                </select>
                <input type="number" name="price_min" placeholder="السعر من" value="<?php echo esc_attr($price_min); ?>">
                <input type="number" name="price_max" placeholder="إلى" value="<?php echo esc_attr($price_max); ?>">
                <button type="submit">بحث</button>
            </div>
        </form>
    </section>

    <section class="gre-entity-grid">
        <?php
        if ($query->have_posts()) :
            echo '<div class="entity-card-container">';
            while ($query->have_posts()) : $query->the_post();
                $post_type = get_post_type();
                $post_id = get_the_ID();
                $image = get_the_post_thumbnail_url($post_id, 'medium') ?: plugin_dir_url(__FILE__) . '../assets/img/default.png';
        ?>
                <div class="entity-card">
                    <img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>" class="entity-thumb">
                    <div class="entity-info">
                        <h2 class="entity-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p class="entity-meta">النوع: <?php echo ($post_type == 'gre_tower' ? 'برج' : ($post_type == 'gre_model' ? 'نموذج' : 'شقة')); ?></p>
                        <a href="<?php the_permalink(); ?>" class="details-button">عرض التفاصيل</a>
                    </div>
                </div>
        <?php
            endwhile;
            echo '</div>';
            the_posts_pagination([
                'prev_text' => __('← السابق'),
                'next_text' => __('التالي →'),
            ]);
        else :
            echo '<p>لا توجد نتائج مطابقة حالياً.</p>';
        endif;
        wp_reset_postdata();
        ?>
    </section>
</main>

<?php get_footer(); ?>
