<?php
/**
 * GallopHub — functions.php
 */

defined( 'ABSPATH' ) || exit;
define( 'GH_FILE',    __FILE__ );
define( 'GH_VERSION', '1.0.0' );

/* ── Load includes ── */
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/meta-fields.php';
require_once get_template_directory() . '/inc/ajax.php';
require_once get_template_directory() . '/inc/schema.php';

/* ── Theme supports ── */
add_action( 'after_setup_theme', function () {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption', 'script', 'style' ] );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'woocommerce' );

    add_image_size( 'horse-card',   600, 450, true );
    add_image_size( 'horse-hero',  1200, 800, true );
    add_image_size( 'horse-thumb',  180, 135, true );

    register_nav_menus( [
        'primary' => __( 'Menu principal', 'gallophub' ),
        'footer'  => __( 'Menu footer', 'gallophub' ),
    ] );

    load_theme_textdomain( 'gallophub', get_template_directory() . '/languages' );
} );

/* ── Enqueue assets ── */
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'gallophub-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [], GH_VERSION
    );
    wp_enqueue_script( 'gallophub-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [], GH_VERSION, true
    );
    wp_localize_script( 'gallophub-main', 'ghAjax', [
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'gh_ajax' ),
        'waNum' => get_option( 'gh_whatsapp_number', '34600000000' ),
    ] );
} );

/* ── Remove default WP scripts we don't need on front ── */
add_action( 'wp_enqueue_scripts', function () {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'classic-theme-styles' );
}, 20 );

/* ── DB table for contact messages ── */
add_action( 'after_switch_theme', function () {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}gh_contacts (
        id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        horse_id    BIGINT UNSIGNED DEFAULT NULL,
        name        VARCHAR(120) NOT NULL,
        email       VARCHAR(200) NOT NULL,
        phone       VARCHAR(50)  DEFAULT '',
        subject     VARCHAR(200) DEFAULT '',
        message     TEXT NOT NULL,
        is_read     TINYINT(1) DEFAULT 0,
        created_at  DATETIME NOT NULL
    ) {$charset};";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
} );

/* ── Admin: contact messages list ── */
add_action( 'admin_menu', function () {
    add_menu_page(
        __( 'Messages contact', 'gallophub' ),
        __( 'Messages', 'gallophub' ),
        'manage_options',
        'gh-contacts',
        'gh_contacts_page',
        'dashicons-email-alt',
        6
    );
} );

function gh_contacts_page(): void {
    global $wpdb;
    $rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}gh_contacts ORDER BY created_at DESC LIMIT 100" );
    echo '<div class="wrap"><h1>' . esc_html__( 'Messages de contact', 'gallophub' ) . '</h1>';
    echo '<table class="widefat"><thead><tr><th>Date</th><th>Nom</th><th>Email</th><th>Sujet</th><th>Message</th><th>Cheval</th></tr></thead><tbody>';
    foreach ( $rows as $row ) {
        $horse = $row->horse_id ? get_the_title( (int) $row->horse_id ) : '—';
        printf(
            '<tr><td>%s</td><td>%s</td><td><a href="mailto:%s">%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>',
            esc_html( $row->created_at ),
            esc_html( $row->name ),
            esc_attr( $row->email ), esc_html( $row->email ),
            esc_html( $row->subject ),
            esc_html( wp_trim_words( $row->message, 12 ) ),
            esc_html( $horse )
        );
    }
    echo '</tbody></table></div>';
}

/* ── Theme options (WhatsApp number, etc.) ── */
add_action( 'admin_menu', function () {
    add_options_page( 'GallopHub', 'GallopHub', 'manage_options', 'gallophub-settings', 'gh_settings_page' );
} );

function gh_settings_page(): void {
    if ( isset( $_POST['gh_save'] ) && check_admin_referer( 'gh_settings' ) ) {
        update_option( 'gh_whatsapp_number', sanitize_text_field( $_POST['gh_whatsapp_number'] ) );
        update_option( 'gh_contact_email',   sanitize_email( $_POST['gh_contact_email'] ) );
        echo '<div class="notice notice-success"><p>Paramètres sauvegardés.</p></div>';
    }
    $wa    = get_option( 'gh_whatsapp_number', '34600000000' );
    $email = get_option( 'gh_contact_email', get_option( 'admin_email' ) );
    ?>
    <div class="wrap">
        <h1>GallopHub — Paramètres</h1>
        <form method="post">
            <?php wp_nonce_field( 'gh_settings' ); ?>
            <table class="form-table">
                <tr>
                    <th><label for="gh_whatsapp_number">Numéro WhatsApp</label></th>
                    <td><input class="regular-text" type="text" id="gh_whatsapp_number" name="gh_whatsapp_number" value="<?= esc_attr( $wa ) ?>">
                    <p class="description">Format : 34612345678 (sans +)</p></td>
                </tr>
                <tr>
                    <th><label for="gh_contact_email">Email de contact</label></th>
                    <td><input class="regular-text" type="email" id="gh_contact_email" name="gh_contact_email" value="<?= esc_attr( $email ) ?>"></td>
                </tr>
            </table>
            <?php submit_button( 'Sauvegarder', 'primary', 'gh_save' ); ?>
        </form>
    </div>
    <?php
}

/* ── Breadcrumb helper ── */
function gh_breadcrumb( array $items ): void {
    echo '<nav class="breadcrumb" aria-label="Breadcrumb"><div class="container"><ul style="display:flex;gap:.5rem;font-size:12px;color:var(--sub);list-style:none;padding:1rem 0">';
    $last = array_key_last( $items );
    foreach ( $items as $i => [ $label, $url ] ) {
        if ( $i === $last ) {
            echo '<li style="color:var(--navy);font-weight:500">' . esc_html( $label ) . '</li>';
        } else {
            echo '<li><a href="' . esc_url( $url ) . '" style="color:var(--sub)">' . esc_html( $label ) . '</a></li>';
            echo '<li aria-hidden="true" style="opacity:.4">/</li>';
        }
    }
    echo '</ul></div></nav>';
}
