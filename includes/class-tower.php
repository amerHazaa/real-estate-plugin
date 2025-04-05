<?php
class GRE_Tower {
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('edit_form_after_title', [$this, 'add_media_button_above_fields']);
        require_once plugin_dir_path(__FILE__) . '/../admin/tower-meta-boxes.php';
    }

    public function register_post_type() {
        register_post_type('gre_tower', [
            'label' => 'الأبراج',
            'public' => true,
            'supports' => ['title', 'thumbnail'], // لا نريد المحرر
            'menu_position' => 5,
            'menu_icon' => 'dashicons-building',
            'has_archive' => true,
            'rewrite' => ['slug' => 'towers'],
        ]);
    }

    public function add_media_button_above_fields($post) {
        if ($post->post_type === 'gre_tower') {
            echo '<div style="margin: 10px 0;">';
            do_action('media_buttons', $post->ID);
            echo '</div>';
        }
    }
}
?>
