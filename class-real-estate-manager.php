<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Real_Estate_Manager {
    private $db_version = '1.0';
    private $towers_table;
    private $apartments_table;

    public function __construct() {
        global $wpdb;
        // تحديد أسماء الجداول مع بادئة قاعدة البيانات
        $this->towers_table = $wpdb->prefix . 'rem_towers';
        $this->apartments_table = $wpdb->prefix . 'rem_apartments';

        // عند تفعيل الإضافة، يتم إنشاء الجداول
        register_activation_hook( REM_PLUGIN_DIR . 'real-estate-manager.php', array( $this, 'install_tables' ) );
    }

    /**
     * إنشاء جداول قاعدة البيانات الخاصة بالأبراج والشقق.
     */
    public function install_tables() {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $charset_collate = $wpdb->get_charset_collate();

        // جدول الأبراج
        $sql_towers = "CREATE TABLE {$this->towers_table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            post_id bigint(20) unsigned NOT NULL,
            address varchar(255) NOT NULL,
            longitude varchar(50) NOT NULL,
            latitude varchar(50) NOT NULL,
            floors int(11) NOT NULL,
            apartments_per_floor int(11) NOT NULL,
            total_area varchar(50) NOT NULL,
            completion_year varchar(50) NOT NULL,
            tower_status varchar(50) NOT NULL,
            description text,
            parking varchar(50) NOT NULL,
            amenities text,
            finishes varchar(100) NOT NULL,
            ac_heating varchar(50) NOT NULL,
            security varchar(50) NOT NULL,
            exterior_images text,
            video_tour varchar(255) NOT NULL,
            floor_plans text,
            apartment_models text,
            price_option varchar(50) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        // جدول الشقق
        $sql_apartments = "CREATE TABLE {$this->apartments_table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            post_id bigint(20) unsigned NOT NULL,
            total_area varchar(50) NOT NULL,
            rooms_count int(11) NOT NULL,
            floor_plan text,
            position varchar(100) NOT NULL,
            finishing varchar(50) NOT NULL,
            price varchar(50) NOT NULL,
            bathrooms int(11) NOT NULL,
            kitchen varchar(50) NOT NULL,
            windows_view varchar(100) NOT NULL,
            balcony varchar(50) NOT NULL,
            balcony_size varchar(50) NOT NULL,
            ac_type varchar(50) NOT NULL,
            security_features varchar(100) NOT NULL,
            images text,
            video_tour varchar(255) NOT NULL,
            special_offers varchar(100) NOT NULL,
            financing_option varchar(100) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta( $sql_towers );
        dbDelta( $sql_apartments );

        add_option( 'rem_db_version', $this->db_version );
    }

    /**
     * إضافة سجل جديد لبرج سكني.
     *
     * @param int   $post_id معرف المنشور الخاص بالبرج.
     * @param array $data    مصفوفة تحتوي على بيانات البرج.
     * @return int|false    يُرجع معرف السجل المُدرج أو false عند الخطأ.
     */
    public function add_new_tower( $post_id, $data ) {
        global $wpdb;
        $wpdb->insert( $this->towers_table, array(
            'post_id'              => $post_id,
            'address'              => isset( $data['address'] ) ? sanitize_text_field( $data['address'] ) : '',
            'longitude'            => isset( $data['longitude'] ) ? sanitize_text_field( $data['longitude'] ) : '',
            'latitude'             => isset( $data['latitude'] ) ? sanitize_text_field( $data['latitude'] ) : '',
            'floors'               => isset( $data['floors'] ) ? intval( $data['floors'] ) : 0,
            'apartments_per_floor' => isset( $data['apartments_per_floor'] ) ? intval( $data['apartments_per_floor'] ) : 0,
            'total_area'           => isset( $data['total_area'] ) ? sanitize_text_field( $data['total_area'] ) : '',
            'completion_year'      => isset( $data['completion_year'] ) ? sanitize_text_field( $data['completion_year'] ) : '',
            'tower_status'         => isset( $data['tower_status'] ) ? sanitize_text_field( $data['tower_status'] ) : '',
            'description'          => isset( $data['description'] ) ? sanitize_textarea_field( $data['description'] ) : '',
            'parking'              => isset( $data['parking'] ) ? sanitize_text_field( $data['parking'] ) : '',
            'amenities'            => isset( $data['amenities'] ) ? sanitize_textarea_field( $data['amenities'] ) : '',
            'finishes'             => isset( $data['finishes'] ) ? sanitize_text_field( $data['finishes'] ) : '',
            'ac_heating'           => isset( $data['ac_heating'] ) ? sanitize_text_field( $data['ac_heating'] ) : '',
            'security'             => isset( $data['security'] ) ? sanitize_text_field( $data['security'] ) : '',
            'exterior_images'      => isset( $data['exterior_images'] ) ? sanitize_text_field( $data['exterior_images'] ) : '',
            'video_tour'           => isset( $data['video_tour'] ) ? esc_url_raw( $data['video_tour'] ) : '',
            'floor_plans'          => isset( $data['floor_plans'] ) ? sanitize_text_field( $data['floor_plans'] ) : '',
            'apartment_models'     => isset( $data['apartment_models'] ) ? sanitize_text_field( $data['apartment_models'] ) : '',
            'price_option'         => isset( $data['price_option'] ) ? sanitize_text_field( $data['price_option'] ) : '',
        ) );

        return $wpdb->insert_id;
    }

    /**
     * إضافة سجل جديد لنموذج شقة سكنية.
     *
     * @param int   $post_id معرف المنشور الخاص بالشقة.
     * @param array $data    مصفوفة تحتوي على بيانات الشقة.
     * @return int|false    يُرجع معرف السجل المُدرج أو false عند الخطأ.
     */
    public function add_new_apartment( $post_id, $data ) {
        global $wpdb;
        $wpdb->insert( $this->apartments_table, array(
            'post_id'           => $post_id,
            'total_area'        => isset( $data['total_area'] ) ? sanitize_text_field( $data['total_area'] ) : '',
            'rooms_count'       => isset( $data['rooms_count'] ) ? intval( $data['rooms_count'] ) : 0,
            'floor_plan'        => isset( $data['floor_plan'] ) ? sanitize_text_field( $data['floor_plan'] ) : '',
            'position'          => isset( $data['position'] ) ? sanitize_text_field( $data['position'] ) : '',
            'finishing'         => isset( $data['finishing'] ) ? sanitize_text_field( $data['finishing'] ) : '',
            'price'             => isset( $data['price'] ) ? sanitize_text_field( $data['price'] ) : '',
            'bathrooms'         => isset( $data['bathrooms'] ) ? intval( $data['bathrooms'] ) : 0,
            'kitchen'           => isset( $data['kitchen'] ) ? sanitize_text_field( $data['kitchen'] ) : '',
            'windows_view'      => isset( $data['windows_view'] ) ? sanitize_text_field( $data['windows_view'] ) : '',
            'balcony'           => isset( $data['balcony'] ) ? sanitize_text_field( $data['balcony'] ) : '',
            'balcony_size'      => isset( $data['balcony_size'] ) ? sanitize_text_field( $data['balcony_size'] ) : '',
            'ac_type'           => isset( $data['ac_type'] ) ? sanitize_text_field( $data['ac_type'] ) : '',
            'security_features' => isset( $data['security_features'] ) ? sanitize_text_field( $data['security_features'] ) : '',
            'images'            => isset( $data['images'] ) ? sanitize_text_field( $data['images'] ) : '',
            'video_tour'        => isset( $data['video_tour'] ) ? esc_url_raw( $data['video_tour'] ) : '',
            'special_offers'    => isset( $data['special_offers'] ) ? sanitize_text_field( $data['special_offers'] ) : '',
            'financing_option'  => isset( $data['financing_option'] ) ? sanitize_text_field( $data['financing_option'] ) : '',
        ) );

        return $wpdb->insert_id;
    }
}
