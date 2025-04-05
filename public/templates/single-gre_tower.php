<?php
/**
 * قالب عرض تفاصيل البرج - single-gre_tower.php
 */

get_header(); // ترويسة القالب

if (have_posts()) :
    while (have_posts()) : the_post();
        $post_id = get_the_ID();

        // بيانات مخصصة
        $short_name = get_post_meta($post_id, '_gre_tower_short_name', true);
        $floors = get_post_meta($post_id, '_gre_tower_floors', true);
        $city = get_post_meta($post_id, '_gre_tower_city', true);
        $district = get_post_meta($post_id, '_gre_tower_district', true);
        $lat = get_post_meta($post_id, '_gre_tower_location_lat', true);
        $lng = get_post_meta($post_id, '_gre_tower_location_lng', true);
        $desc = get_post_meta($post_id, '_gre_tower_general_description', true);
        $has_parking = get_post_meta($post_id, '_gre_tower_has_parking', true);
        $has_generator = get_post_meta($post_id, '_gre_tower_has_generator', true);
        $has_shops = get_post_meta($post_id, '_gre_tower_has_shops', true);
        $image_url = get_the_post_thumbnail_url($post_id, 'large');
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
                    <h2>تفاصيل البرج</h2>
                    <?php gre_render_entity_details($post_id, 'tower'); ?>
                </section>

                <?php if ($desc) : ?>
                    <section class="gre-entity-description">
                        <h2>الوصف العام</h2>
                        <p><?php echo esc_html($desc); ?></p>
                    </section>
                <?php endif; ?>

                <?php if ($lat && $lng) : ?>
                    <section class="gre-entity-map">
                        <h2>الموقع على الخريطة</h2>
                        <div id="map" style="height: 400px;"></div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                var map = L.map('map').setView([<?php echo esc_js($lat); ?>, <?php echo esc_js($lng); ?>], 16);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                                L.marker([<?php echo esc_js($lat); ?>, <?php echo esc_js($lng); ?>]).addTo(map);
                            });
                        </script>
                    </section>
                <?php endif; ?>
            </article>
        </main>
<?php
    endwhile;
endif;

get_sidebar(); // الشريط الجانبي
get_footer(); // تذييل القالب
?>
