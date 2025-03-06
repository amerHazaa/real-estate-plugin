<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Interactive_Floor_Plan {

    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    // Enqueue necessary scripts and styles
    public function enqueue_scripts() {
        wp_enqueue_script('interactive-floor-plan', plugin_dir_url(__FILE__) . 'js/interactive-floor-plan.js', array('jquery'), '1.0', true);
        wp_enqueue_style('interactive-floor-plan', plugin_dir_url(__FILE__) . 'css/interactive-floor-plan.css', array(), '1.0');
    }

    // Render the interactive floor plan builder
    public function render_floor_plan_builder() {
        echo '<div id="floor-plan-builder">
                <div id="components">
                    <h3>' . __('Components', 'golden-tower-manager') . '</h3>
                    <ul>
                        <li data-component="room">' . __('Room', 'golden-tower-manager') . '</li>
                        <li data-component="bathroom">' . __('Bathroom', 'golden-tower-manager') . '</li>
                        <li data-component="living-room">' . __('Living Room', 'golden-tower-manager') . '</li>
                        <li data-component="kitchen">' . __('Kitchen', 'golden-tower-manager') . '</li>
                        <li data-component="balcony">' . __('Balcony', 'golden-tower-manager') . '</li>
                    </ul>
                </div>
                <div id="floor-plan-canvas">
                    <h3>' . __('Floor Plan', 'golden-tower-manager') . '</h3>
                </div>
              </div>';
    }
}
?>