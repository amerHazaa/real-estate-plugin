<?php
// ملف class-tower.php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Tower {
    public $id;
    public $title;
    public $location;
    public $units;

    public function __construct($post_id) {
        $this->id    = $post_id;
        $this->title = get_the_title($post_id);
        $details     = rem_get_tower_details($post_id);
        $this->location = $details['location'];
        $this->units    = $details['units'];
    }

    public function display() {
        $output  = '<div class="tower-item">';
        $output .= '<h2>' . esc_html($this->title) . '</h2>';
        $output .= get_the_post_thumbnail($this->id, 'medium');
        $output .= '<p>الموقع: ' . esc_html($this->location) . '</p>';
        $output .= '<p>عدد الوحدات: ' . esc_html($this->units) . '</p>';
        $output .= '</div>';
        return $output;
    }
}
