<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class Golden_Tower_Manager {

    public function __construct() {
        add_action('init', [$this, 'register_post_types']);
        add_action('admin_menu', [$this, 'add_admin_menus']);
        add_action('admin_post_save_tower', [$this, 'save_tower']);
        add_action('admin_post_save_apartment', [$this, 'save_apartment']);
        add_shortcode('tower_map', [$this, 'render_tower_map']);
    }

    public function register_post_types() {
        $this->register_post_type('tower', __('Towers', 'golden-tower-manager'), 'towers');
        $this->register_post_type('apartment', __('Apartments', 'golden-tower-manager'), 'apartments');
    }

    private function register_post_type($post_type, $label, $slug) {
        register_post_type($post_type, [
            'labels' => [
                'name' => $label,
                'singular_name' => $label,
                'all_items' => sprintf(__('All %s', 'golden-tower-manager'), $label),
                'menu_name' => $label,
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => $slug],
            'show_in_menu' => false, // Set to true if you want the default WordPress menu
        ]);
    }

    public function add_admin_menus() {
        $menu_slug = 'golden-tower-manager';
        add_menu_page(
            __('Golden Tower Manager', 'golden-tower-manager'),
            __('مؤسسة الصرح الذهبي', 'golden-tower-manager'),
            'manage_options',
            $menu_slug,
            null,
            'dashicons-building',
            5
        );

        $this->add_submenu_page($menu_slug, __('Towers', 'golden-tower-manager'), 'towers-management', [$this, 'render_towers_page']);
        $this->add_submenu_page($menu_slug, __('Apartments', 'golden-tower-manager'), 'apartments-management', [$this, 'render_apartments_page']);
        $this->add_submenu_page($menu_slug, __('Add New Tower', 'golden-tower-manager'), 'add-new-tower', [$this, 'render_add_new_tower_page']);
        $this->add_submenu_page($menu_slug, __('Add New Apartment', 'golden-tower-manager'), 'add-new-apartment', [$this, 'render_add_new_apartment_page']);
    }

    private function add_submenu_page($parent_slug, $page_title, $menu_slug, $callback) {
        add_submenu_page(
            $parent_slug,
            $page_title,
            $page_title,
            'manage_options',
            $menu_slug,
            $callback
        );
    }

    public function render_towers_page() {
        global $wpdb;
        $towers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}towers");

        echo '<div class="wrap"><h1>' . __('Towers', 'golden-tower-manager') . '</h1>';
        echo '<a href="' . esc_url(admin_url('admin.php?page=add-new-tower')) . '" class="page-title-action">' . __('Add New', 'golden-tower-manager') . '</a>';

        if ($towers) {
            echo '<table class="wp-list-table widefat fixed striped"><thead><tr><th>' . __('Name', 'golden-tower-manager') . '</th><th>' . __('Location', 'golden-tower-manager') . '</th><th>' . __('Status', 'golden-tower-manager') . '</th><th>' . __('Actions', 'golden-tower-manager') . '</th></tr></thead><tbody>';

            foreach ($towers as $row) {
                echo '<tr><td>' . esc_html($row->name) . '</td><td>' . esc_html($row->location) . '</td><td>' . esc_html($row->status) . '</td><td>';
                echo '<a href="' . esc_url(get_edit_post_link($row->id)) . '"><span class="dashicons dashicons-edit"></span></a> | ';
                echo '<a href="' . esc_url(get_delete_post_link($row->id)) . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><span class="dashicons dashicons-trash"></span></a> | ';
                echo '<a href="' . esc_url(get_permalink($row->id)) . '"><span class="dashicons dashicons-visibility"></span></a>';
                echo '</td></tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>' . __('No towers found.', 'golden-tower-manager') . '</p>';
        }

        echo '</div>';
    }

    public function render_apartments_page() {
        global $wpdb;
        $apartments = $wpdb->get_results("SELECT a.*, t.name AS tower_name FROM {$wpdb->prefix}apartments a LEFT JOIN {$wpdb->prefix}towers t ON a.tower_id = t.id");

        echo '<div class="wrap"><h1>' . __('Apartments', 'golden-tower-manager') . '</h1>';
        echo '<a href="' . esc_url(admin_url('admin.php?page=add-new-apartment')) . '" class="page-title-action">' . __('Add New', 'golden-tower-manager') . '</a>';

        if ($apartments) {
            echo '<table class="wp-list-table widefat fixed striped"><thead><tr><th>' . __('Model', 'golden-tower-manager') . '</th><th>' . __('Tower', 'golden-tower-manager') . '</th><th>' . __('Area', 'golden-tower-manager') . '</th><th>' . __('Price', 'golden-tower-manager') . '</th><th>' . __('Actions', 'golden-tower-manager') . '</th></tr></thead><tbody>';

            foreach ($apartments as $row) {
                echo '<tr><td>' . esc_html($row->model_name) . '</td><td>' . esc_html($row->tower_name) . '</td><td>' . esc_html($row->total_area) . '</td><td>' . esc_html($row->price) . '</td><td>';
                echo '<a href="' . esc_url(get_edit_post_link($row->id)) . '"><span class="dashicons dashicons-edit"></span></a> | ';
                echo '<a href="' . esc_url(get_delete_post_link($row->id)) . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><span class="dashicons dashicons-trash"></span></a> | ';
                echo '<a href="' . esc_url(get_permalink($row->id)) . '"><span class="dashicons dashicons-visibility"></span></a>';
                echo '</td></tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>' . __('No apartments found.', 'golden-tower-manager') . '</p>';
        }

        echo '</div>';
    }

    public function render_add_new_tower_page() {
        echo '<div class="wrap"><h1>' . __('Add New Tower', 'golden-tower-manager') . '</h1>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        echo '<input type="hidden" name="action" value="save_tower">';
        wp_nonce_field('save_tower_nonce');
        echo '<table class="form-table">';
        echo '<tr><th><label for="name">' . __('Name', 'golden-tower-manager') . '</label></th><td><input type="text" name="name" id="name" class="regular-text" required></td></tr>';
        echo '<tr><th><label for="location">' . __('Location', 'golden-tower-manager') . '</label></th><td><input type="text" name="location" id="location" class="regular-text" required></td></tr>';
        echo '<tr><th><label for="longitude">' . __('Longitude', 'golden-tower-manager') . '</label></th><td><input type="number" step="0.000001" name="longitude" id="longitude" class="regular-text" required></td></tr>';
        echo '<tr><th><label for="latitude">' . __('Latitude', 'golden-tower-manager') . '</label></th><td><input type="number" step="0.000001" name="latitude" id="latitude" class="regular-text" required></td></tr>';
        echo '<tr><th><label for="floors">' . __('Floors', 'golden-tower-manager') . '</label></th><td><input type="number" name="floors" id="floors" class="regular-text" required></td></tr>';
        echo '<tr><th><label for="apartments_per_floor">' . __('Apartments per Floor', 'golden-tower-manager') . '</label></th><td><input type="number" name="apartments_per_floor" id="apartments_per_floor" class="regular-text" required></td></tr>';
        echo '<tr><th><label for="total_area">' . __('Total Area', 'golden-tower-manager') . '</label></th><td><input type="number" step="0.01" name="total_area" id="total_area" class="regular-text" required></td></tr>';
        echo '<tr><th><label for="year_built">' . __('Year Built', 'golden-tower-manager') . '</label></th><td><input type="number" name="year_built" id="year_built" class="regular-text" required></td></tr>';
        echo '<tr><th><label for="status">' . __('Status', 'golden-tower-manager') . '</label></th><td>';
        echo '<select name="status" id="status" class="regular-text" required>';
        echo '<option value="ready">' . __('Ready', 'golden-tower-manager') . '</option>';
        echo '<option value="under_construction">' . __('Under Construction', 'golden-tower-manager') . '</option>';
        echo '<option value="in_planning">' . __('In Planning', 'golden-tower-manager') . '</option>';
        echo '</select>';
        echo '</td></tr>';
        echo '<tr><th><label for="description">' . __('Description', 'golden-tower-manager') . '</label></th><td><textarea name="description" id="description" class="large-text" required></textarea></td></tr>';
        echo '<tr><th><label for="features">' . __('Features', 'golden-tower-manager') . '</label></th><td><textarea name="features" id="features" class="large-text" required></textarea></td></tr>';
        echo '<tr><th><label for="available_apartments">' . __('Available Apartments', 'golden-tower-manager') . '</label></th><td><input type="number" name="available_apartments" id="available_apartments" class="regular-text"></td></tr>';
        echo '<tr><th><label for="sold_apartments">' . __('Sold Apartments', 'golden-tower-manager') . '</label></th><td><input type="number" name="sold_apartments" id="sold_apartments" class="regular-text"></td></tr>';
        echo '<tr><th><label for="apartment_models">' . __('Apartment Models', 'golden-tower-manager') . '</label></th><td>';
        echo '<input type="checkbox" name="apartment_models[]" value="A">' . __('A', 'golden-tower-manager');
        echo '<input type="checkbox" name="apartment_models[]" value="B">' . __('B', 'golden-tower-manager');
        echo '<input type="checkbox" name="apartment_models[]" value="C">' . __('C', 'golden-tower-manager');
        echo '<input type="checkbox" name="apartment_models[]" value="D">' . __('D', 'golden-tower-manager');
        echo '<input type="checkbox" name="apartment_models[]" value="1">' . __('1', 'golden-tower-manager');
        echo '<input type="checkbox" name="apartment_models[]" value="2">' . __('2', 'golden-tower-manager');
        echo '<input type="checkbox" name="apartment_models[]" value="3">' . __('3', 'golden-tower-manager');
        echo '<input type="checkbox" name="apartment_models[]" value="4">' . __('4', 'golden-tower-manager');
        echo '<input type="checkbox" name="apartment_models[]" value="5">' . __('5', 'golden-tower-manager');
        echo '<input type="checkbox" name="apartment_models[]" value="6">' . __('6', 'golden-tower-manager');
        echo '</td></tr>';
        echo '<tr><th><label for="main_image">' . __('Main Image', 'golden-tower-manager') . '</label></th><td><input type="file" name="main_image" id="main_image" class="regular-text"></td></tr>';
        echo '<tr><th><label for="additional_images">' . __('Additional Images', 'golden-tower-manager') . '</label></th><td><input type="file" name="additional_images[]" id="additional_images" class="regular-text" multiple></td></tr>';
        echo '<tr><th><label for="floor_plan_images">' . __('Floor Plan Images', 'golden-tower-manager') . '</label></th><td><input type="file" name="floor_plan_images[]" id="floor_plan_images" class="regular-text" multiple></td></tr>';
        echo '</table>';
        submit_button(__('Save Tower', 'golden-tower-manager'));
        echo '</form>';
        echo '</div>';
    }
        
public function render_add_new_apartment_page() {
    echo '<div class="wrap"><h1>' . __('Add New Apartment', 'golden-tower-manager') . '</h1>';
    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" enctype="multipart/form-data">';
    echo '<input type="hidden" name="action" value="save_apartment">';
    wp_nonce_field('save_apartment_nonce');
    echo '<table class="form-table">';
    echo '<tr><th><label for="tower_id">' . __('Tower', 'golden-tower-manager') . '</label></th><td>';
    global $wpdb;
    $towers = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}towers");
    echo '<select name="tower_id" id="tower_id" class="regular-text" required>';
    foreach ($towers as $tower) {
        echo '<option value="' . esc_attr($tower->id) . '">' . esc_html($tower->name) . '</option>';
    }
    echo '</select>';
    echo '</td></tr>';
    echo '<tr><th><label for="model_name">' . __('Model Name', 'golden-tower-manager') . '</label></th><td><input type="text" name="model_name" id="model_name" class="regular-text" required></td></tr>';
    echo '<tr><th><label for="total_area">' . __('Total Area', 'golden-tower-manager') . '</label></th><td><input type="number" step="0.01" name="total_area" id="total_area" class="regular-text" required></td></tr>';
    echo '<tr><th><label for="rooms">' . __('Rooms', 'golden-tower-manager') . '</label></th><td><input type="number" name="rooms" id="rooms" class="regular-text" required></td></tr>';
    echo '<tr><th><label for="bathrooms">' . __('Bathrooms', 'golden-tower-manager') . '</label></th><td><input type="number" name="bathrooms" id="bathrooms" class="regular-text" required></td></tr>';
    echo '<tr><th><label for="living_rooms">' . __('Living Rooms', 'golden-tower-manager') . '</label></th><td><input type="number" name="living_rooms" id="living_rooms" class="regular-text" required></td></tr>';
    echo '<tr><th><label for="kitchen">' . __('Kitchen', 'golden-tower-manager') . '</label></th><td><input type="number" name="kitchen" id="kitchen" class="regular-text" required></td></tr>';
    echo '<tr><th><label for="balcony">' . __('Balcony', 'golden-tower-manager') . '</label></th><td><input type="number" name="balcony" id="balcony" class="regular-text" required></td></tr>';
    echo '<tr><th><label for="price">' . __('Price', 'golden-tower-manager') . '</label></th><td><input type="number" step="0.01" name="price" id="price" class="regular-text" required></td></tr>';
    echo '<tr><th><label for="features">' . __('Features', 'golden-tower-manager') . '</label></th><td><textarea name="features" id="features" class="large-text" required></textarea></td></tr>';
    echo '<tr><th><label for="available_units">' . __('Available Units', 'golden-tower-manager') . '</label></th><td><input type="number" name="available_units" id="available_units" class="regular-text"></td></tr>';
    echo '<tr><th><label for="sold_units">' . __('Sold Units', 'golden-tower-manager') . '</label></th><td><input type="number" name="sold_units" id="sold_units" class="regular-text"></td></tr>';
    echo '<tr><th><label for="main_image">' . __('Main Image', 'golden-tower-manager') . '</label></th><td><input type="file" name="main_image" id="main_image" class="regular-text"></td></tr>';
    echo '<tr><th><label for="additional_images">' . __('Additional Images', 'golden-tower-manager') . '</label></th><td><input type="file" name="additional_images[]" id="additional_images" class="regular-text" multiple></td></tr>';
    echo '<tr><th><label for="floor_plan_images">' . __('Floor Plan Images', 'golden-tower-manager') . '</label></th><td><input type="file" name="floor_plan_images[]" id="floor_plan_images" class="regular-text" multiple></td></tr>';
    echo '</table>';
    submit_button(__('Save Apartment', 'golden-tower-manager'));
    echo '</form>';
    echo '</div>';
}

public function save_tower() {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'save_tower_nonce')) {
        wp_die(__('Invalid nonce', 'golden-tower-manager'));
    }

    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}towers", [
        'name' => sanitize_text_field($_POST['name']),
        'location' => sanitize_text_field($_POST['location']),
        'longitude' => floatval($_POST['longitude']),
        'latitude' => floatval($_POST['latitude']),
        'floors' => intval($_POST['floors']),
        'apartments_per_floor' => intval($_POST['apartments_per_floor']),
        'total_area' => floatval($_POST['total_area']),
        'year_built' => intval($_POST['year_built']),
        'status' => sanitize_text_field($_POST['status']),
        'description' => sanitize_textarea_field($_POST['description']),
        'features' => sanitize_textarea_field($_POST['features']),
    ]);

    wp_redirect(admin_url('admin.php?page=towers-management'));
    exit;
}

public function save_apartment() {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'save_apartment_nonce')) {
        wp_die(__('Invalid nonce', 'golden-tower-manager'));
    }

    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}apartments", [
        'tower_id' => intval($_POST['tower_id']),
        'model_name' => sanitize_text_field($_POST['model_name']),
        'total_area' => floatval($_POST['total_area']),
        'rooms' => intval($_POST['rooms']),
        'bathrooms' => intval($_POST['bathrooms']),
        'living_rooms' => intval($_POST['living_rooms']),
        'kitchen' => intval($_POST['kitchen']),
        'balcony' => intval($_POST['balcony']),
        'price' => floatval($_POST['price']),
        'features' => sanitize_textarea_field($_POST['features']),
    ]);

    wp_redirect(admin_url('admin.php?page=apartments-management'));
    exit;
}

public function render_tower_map() {
    global $wpdb;
    $towers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}towers");

    $map_data = [];
    foreach ($towers as $row) {
        $map_data[] = [
            'name' => $row->name,
            'location' => $row->location,
            'longitude' => $row->longitude,
            'latitude' => $row->latitude,
            'status' => $row->status,
        ];
    }

    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY', [], null, true);
    wp_enqueue_script('tower-map', plugin_dir_url(__FILE__) . 'js/tower-map.js', ['google-maps'], '1.0', true);
    wp_localize_script('tower-map', 'tower_map_data', $map_data);

    return '<div id="tower-map" style="width:100%;height:500px;"></div>';
}

public function run() {
    // Initialization logic (if any)
}
}
?>