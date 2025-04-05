<?php
/**
 * Plugin Name: GRE Real Estate
 * Description: إدارة الأبراج والنماذج والشقق السكنية.
 */

// Autoload classes
require_once plugin_dir_path(__FILE__) . 'includes/class-loader.php';

// Init Post Types
new GRE_Tower();
new GRE_Model();
new GRE_Apartment();

// Admin menus
require_once plugin_dir_path(__FILE__) . 'admin/admin-menus.php';
