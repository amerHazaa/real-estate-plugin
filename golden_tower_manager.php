<?php
/*
Plugin Name: Golden Tower Manager
Description: A plugin to manage residential towers, apartments, and interactive floor plans.
Version: 4.6
Author: Amer Hazaa
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files
include_once plugin_dir_path(__FILE__) . 'class-golden-tower-manager.php';

// Initialize the plugin
function initialize_golden_tower_manager() {
    $plugin = new Golden_Tower_Manager();
    $plugin->run();
}
add_action('plugins_loaded', 'initialize_golden_tower_manager');

// Add sample data on plugin activation
function golden_tower_manager_add_sample_data() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Create or update towers table
    $towers_table = $wpdb->prefix . 'towers';
    $sql = "CREATE TABLE IF NOT EXISTS $towers_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        location text NOT NULL,
        longitude float NOT NULL,
        latitude float NOT NULL,
        floors int NOT NULL,
        apartments_per_floor int NOT NULL,
        total_area float NOT NULL,
        year_built int NOT NULL,
        status varchar(20) NOT NULL,
        description text NOT NULL,
        features text NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Create or update apartments table
    $apartments_table = $wpdb->prefix . 'apartments';
    $sql = "CREATE TABLE IF NOT EXISTS $apartments_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        tower_id mediumint(9) NOT NULL,
        model_name tinytext NOT NULL,
        total_area float NOT NULL,
        rooms int NOT NULL,
        bathrooms int NOT NULL,
        living_rooms int NOT NULL,
        kitchen int NOT NULL,
        balcony int NOT NULL,
        price float NOT NULL,
        features text NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (tower_id) REFERENCES $towers_table(id)
    ) $charset_collate;";
    dbDelta($sql);

    // Add sample data if not exists
    $existing_tower = $wpdb->get_var("SELECT COUNT(*) FROM $towers_table WHERE name = 'توليب صنعاء'");
    if ($existing_tower == 0) {
        $wpdb->insert($towers_table, array(
            'name' => 'توليب صنعاء',
            'location' => 'الأصبحي، صنعاء',
            'longitude' => 15.28715553154145,
            'latitude' => 44.239845427776174,
            'floors' => 10,
            'apartments_per_floor' => 4,
            'total_area' => 5000.0,
            'year_built' => 2025,
            'status' => 'ready',
            'description' => 'برج سكني فاخر يقع في الأصبحي، صنعاء، يحتوي على شقق فاخرة بمساحات مختلفة.',
            'features' => 'مواقف سيارات، مصاعد، صالات رياضية، مسابح، أمن، استقبال.'
        ));

        $tower_id = $wpdb->insert_id;

        $apartments = array(
            array(
                'tower_id' => $tower_id,
                'model_name' => 'نموذج A',
                'total_area' => 120.0,
                'rooms' => 3,
                'bathrooms' => 2,
                'living_rooms' => 1,
                'kitchen' => 1,
                'balcony' => 1,
                'price' => 100000.0,
                'features' => 'تشطيب فاخر، تكييف مركزي، إطلالة بانورامية.'
            ),
            array(
                'tower_id' => $tower_id,
                'model_name' => 'نموذج B',
                'total_area' => 150.0,
                'rooms' => 4,
                'bathrooms' => 3,
                'living_rooms' => 1,
                'kitchen' => 1,
                'balcony' => 2,
                'price' => 150000.0,
                'features' => 'تشطيب فاخر، تكييف مركزي، إطلالة بانورامية، شرفة.'
            ),
            array(
                'tower_id' => $tower_id,
                'model_name' => 'نموذج C',
                'total_area' => 180.0,
                'rooms' => 5,
                'bathrooms' => 4,
                'living_rooms' => 1,
                'kitchen' => 1,
                'balcony' => 2,
                'price' => 200000.0,
                'features' => 'تشطيب فاخر، تكييف مركزي، إطلالة بانورامية، شرفة واسعة.'
            )
        );

        foreach ($apartments as $apartment) {
            $wpdb->insert($apartments_table, $apartment);
        }
    }

    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'golden_tower_manager_add_sample_data');

// Load the custom template
function load_custom_template($template) {
    if (is_singular('tower') || is_singular('apartment')) {
        $custom_template = plugin_dir_path(__FILE__) . 'single-tower-or-apartment.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'load_custom_template');
?>