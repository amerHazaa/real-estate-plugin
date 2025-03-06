<?php
// ملف class-apartment.php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Apartment {
    public $id;
    public $title;
    public $area;
    public $price;

    public function __construct($post_id) {
        $this->id    = $post_id;
        $this->title = get_the_title($post_id);
        $details     = rem_get_apartment_details($post_id);
        $this->area  = $details['area'];
        $this->price = $details['price'];
    }

    public function display() {
        $output  = '<div class="apartment-item">';
        $output .= '<h2>' . esc_html($this->title) . '</h2>';
        $output .= get_the_post_thumbnail($this->id, 'medium');
        $output .= '<p>المساحة: ' . esc_html($this->area) . ' م²</p>';
        $output .= '<p>السعر: ' . esc_html($this->price) . '</p>';
        $output .= '</div>';
        return $output;
    }
}
