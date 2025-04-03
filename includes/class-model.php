<?php
class GRE_Model {
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        require_once plugin_dir_path(__FILE__) . '/../admin/model-meta-boxes.php';
    }

    public function register_post_type() {
        register_post_type('gre_model', [
            'label' => 'النماذج',
            'public' => true,
            'supports' => ['title', 'thumbnail'],
            'menu_position' => 6,
            'menu_icon' => 'dashicons-layout',
            'has_archive' => false,
            'rewrite' => ['slug' => 'models'],
            'show_in_rest' => false,
        ]);
    }
}
?>
