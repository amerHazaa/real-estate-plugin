<?php
/**
 * قالب عرض تفاصيل النموذج - single-gre_model.php
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $post_id = get_the_ID();

        // بيانات مخصصة
        $image_url = get_the_post_thumbnail_url($post_id, 'large');
        $description = get_post_meta($post_id, '_gre_model_description', true);
        $price_usd = get_post_meta($post_id, '_gre_model_price_usd', true);
?>
        <main id="primary" class="site-main">
            <article id="post-<?php the_ID(); ?>" <?php post_class('gre-entity-single'); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php if ($image_url) : ?>
                        <img class="gre-entity-thumb" src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                </header>

                <section class="gre-entity-details">
                    <h2>تفاصيل النموذج</h2>
                    <?php gre_render_entity_details($post_id, 'model'); ?>
                </section>

                <?php if ($description) : ?>
                    <section class="gre-entity-description">
                        <h2>الوصف</h2>
                        <p><?php echo esc_html($description); ?></p>
                    </section>
                <?php endif; ?>

                <?php if ($price_usd) : ?>
                    <section class="gre-entity-price">
                        <h2>السعر</h2>
                        <p><?php echo esc_html($price_usd); ?> دولار أمريكي</p>
                    </section>
                <?php endif; ?>

                <section class="gre-related-apartments">
                    <h2>الشقق التابعة لهذا النموذج</h2>
                    <ul class="apartment-list">
                        <?php
                        $args = [
                            'post_type' => 'gre_apartment',
                            'meta_query' => [
                                [
                                    'key' => '_gre_apartment_model_id',
                                    'value' => $post_id,
                                    'compare' => '=',
                                ],
                            ],
                            'posts_per_page' => -1,
                        ];
                        $apartments = get_posts($args);
                        foreach ($apartments as $apt) {
                            echo '<li><a href="' . get_permalink($apt->ID) . '">' . esc_html(get_the_title($apt->ID)) . '</a></li>';
                        }
                        ?>
                    </ul>
                </section>
            </article>
        </main>
<?php
    endwhile;
endif;

get_sidebar();
get_footer();
?>