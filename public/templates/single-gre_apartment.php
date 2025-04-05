<?php
/**
 * قالب عرض تفاصيل الشقة - single-gre_apartment.php
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $post_id = get_the_ID();

        // صورة الشقة (إذا كانت موجودة)
        $image_url = get_the_post_thumbnail_url($post_id, 'large');
        $model_id = get_post_meta($post_id, '_gre_apartment_model_id', true);
        $status = get_post_meta($post_id, '_gre_apartment_status', true);
        $custom_price_usd = get_post_meta($post_id, '_gre_apartment_custom_price_usd', true);
        $custom_images = get_post_meta($post_id, '_gre_apartment_custom_images', true);
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
                    <h2>تفاصيل الشقة</h2>
                    <?php gre_render_entity_details($post_id, 'apartment'); ?>
                </section>

                <?php if ($status) : ?>
                    <section class="gre-entity-status">
                        <h2>الحالة</h2>
                        <p><?php echo esc_html(gre_get_apartment_status_label($status)); ?></p>
                    </section>
                <?php endif; ?>

                <?php if ($custom_price_usd) : ?>
                    <section class="gre-entity-price">
                        <h2>السعر المخصص</h2>
                        <p><?php echo esc_html($custom_price_usd); ?> دولار أمريكي</p>
                    </section>
                <?php endif; ?>

                <?php if ($custom_images) : ?>
                    <section class="gre-entity-images">
                        <h2>صور إضافية</h2>
                        <div class="gre-entity-images-gallery">
                            <?php
                            $image_ids = explode(',', $custom_images);
                            foreach ($image_ids as $image_id) {
                                $image_url = wp_get_attachment_url(trim($image_id));
                                if ($image_url) {
                                    echo '<img src="' . esc_url($image_url) . '" alt="صورة شقة" loading="lazy">';
                                }
                            }
                            ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ($status === 'available') : ?>
                    <section class="gre-entity-booking">
                        <h2>حجز الشقة</h2>
                        <a href="https://wa.me/967XXXXXXXXX?text=مرحباً، أرغب بحجز الشقة رقم <?php echo get_post_meta($post_id, '_gre_apartment_apartment_number', true); ?>" class="booking-btn" target="_blank">احجز الآن عبر واتساب</a>
                    </section>
                <?php endif; ?>

                <?php if ($model_id) : ?>
                    <section class="gre-entity-model-link">
                        <h2>النموذج المرتبط</h2>
                        <a href="<?php echo esc_url(get_permalink($model_id)); ?>">عرض تفاصيل النموذج</a>
                    </section>
                <?php endif; ?>
            </article>
        </main>
<?php
    endwhile;
endif;

get_sidebar();
get_footer();
?>