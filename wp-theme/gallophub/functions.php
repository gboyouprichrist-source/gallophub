<?php
/**
 * GallopHub — functions.php (version complète)
 *
 * @package GallopHub
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

define( 'GH_FILE',    __FILE__ );
define( 'GH_VERSION', '2.0.0' );
define( 'GH_DIR',     get_template_directory() );
define( 'GH_URI',     get_template_directory_uri() );

/* ═══════════════════════════════════════════════════════════
   INCLUDES
═══════════════════════════════════════════════════════════ */
require_once GH_DIR . '/inc/custom-post-types.php';
require_once GH_DIR . '/inc/meta-fields.php';
require_once GH_DIR . '/inc/ajax.php';
require_once GH_DIR . '/inc/schema.php';

/* ═══════════════════════════════════════════════════════════
   THEME SETUP
═══════════════════════════════════════════════════════════ */
add_action( 'after_setup_theme', 'gh_setup' );

function gh_setup(): void {
    load_theme_textdomain( 'gallophub', GH_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'gallery',
        'caption', 'script', 'style', 'navigation-widgets',
    ] );
    add_theme_support( 'custom-logo' );

    // WooCommerce
    add_theme_support( 'woocommerce', [
        'thumbnail_image_width' => 600,
        'single_image_width'    => 1200,
        'product_grid'          => [
            'default_rows'    => 3,
            'min_rows'        => 1,
            'default_columns' => 3,
            'min_columns'     => 1,
            'max_columns'     => 4,
        ],
    ] );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    add_image_size( 'horse-card',  600, 450, true );
    add_image_size( 'horse-hero', 1200, 800, true );
    add_image_size( 'horse-thumb', 180, 135, true );

    register_nav_menus( [
        'primary' => __( 'Menu principal', 'gallophub' ),
        'footer'  => __( 'Menu footer', 'gallophub' ),
    ] );
}

/* ═══════════════════════════════════════════════════════════
   ENQUEUE ASSETS
═══════════════════════════════════════════════════════════ */
add_action( 'wp_enqueue_scripts', 'gh_enqueue_assets' );

function gh_enqueue_assets(): void {
    wp_enqueue_style(
        'gallophub-main',
        GH_URI . '/assets/css/main.css',
        [], GH_VERSION
    );

    // WooCommerce extra styles (on WC pages only)
    if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        wp_enqueue_style(
            'gallophub-woo',
            GH_URI . '/assets/css/woocommerce.css',
            [ 'gallophub-main' ], GH_VERSION
        );
    }

    wp_enqueue_script(
        'gallophub-main',
        GH_URI . '/assets/js/main.js',
        [], GH_VERSION, true
    );

    wp_localize_script( 'gallophub-main', 'ghAjax', [
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'gh_ajax' ),
        'waNum' => get_option( 'gh_whatsapp_number', '34600000000' ),
        'i18n'  => [
            'sending'  => __( 'Enviando…', 'gallophub' ),
            'success'  => __( 'Mensaje enviado. Te respondemos pronto.', 'gallophub' ),
            'error'    => __( 'Error al enviar. Inténtalo de nuevo.', 'gallophub' ),
            'noResult' => __( 'No se encontraron caballos con estos filtros.', 'gallophub' ),
        ],
    ] );
}

/* ── Remove bloat ── */
add_action( 'wp_enqueue_scripts', 'gh_dequeue_bloat', 20 );

function gh_dequeue_bloat(): void {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'classic-theme-styles' );
}

/* ── Remove default WooCommerce styles (we override everything) ── */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/* ═══════════════════════════════════════════════════════════
   WOOCOMMERCE CART FRAGMENTS (mini-cart AJAX)
═══════════════════════════════════════════════════════════ */
add_filter( 'woocommerce_add_to_cart_fragments', 'gh_cart_fragment' );

function gh_cart_fragment( array $fragments ): array {
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $fragments['span.gh-cart-count'] = '<span class="gh-cart-count">' . absint( $count ) . '</span>';
    return $fragments;
}

/* ═══════════════════════════════════════════════════════════
   WOOCOMMERCE — LINK CPT HORSE ↔ WC PRODUCT
   Strategy : horse CPT remains the editorial source of truth.
   A WC Product (virtual) is created/synced for payment only.
═══════════════════════════════════════════════════════════ */
add_action( 'save_post_horse', 'gh_sync_horse_to_wc_product', 20 );

function gh_sync_horse_to_wc_product( int $post_id ): void {
    if ( ! class_exists( 'WooCommerce' ) ) return;
    if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) return;

    $meta        = gh_get_horse_meta( $post_id );
    $wc_id       = (int) get_post_meta( $post_id, 'gh_wc_product_id', true );
    $horse_title = get_the_title( $post_id );
    $price       = $meta['price'] ?: 0;
    $status      = get_post_status( $post_id );

    if ( $wc_id && get_post_type( $wc_id ) === 'product' ) {
        // Update existing product
        $product = wc_get_product( $wc_id );
        if ( ! $product ) return;
    } else {
        // Create new virtual product
        $product = new WC_Product_Simple();
        $product->set_virtual( true );
        $product->set_catalog_visibility( 'hidden' );
    }

    $product->set_name( $horse_title );
    $product->set_regular_price( (string) $price );
    $product->set_status( $status === 'publish' && $meta['status'] === 'available' ? 'publish' : 'draft' );
    $product->set_description( get_post_field( 'post_content', $post_id ) );

    if ( has_post_thumbnail( $post_id ) ) {
        $product->set_image_id( get_post_thumbnail_id( $post_id ) );
    }

    $new_wc_id = $product->save();
    if ( $new_wc_id && ! $wc_id ) {
        update_post_meta( $post_id, 'gh_wc_product_id', $new_wc_id );
        update_post_meta( $new_wc_id, '_gh_horse_id', $post_id );
    }
}

/* ── Add "Ajouter au panier" link on horse fiche ── */
function gh_horse_add_to_cart_url( int $horse_id ): string {
    $wc_id = (int) get_post_meta( $horse_id, 'gh_wc_product_id', true );
    if ( ! $wc_id || ! class_exists( 'WooCommerce' ) ) return '';
    $product = wc_get_product( $wc_id );
    return $product ? $product->add_to_cart_url() : '';
}

/* ═══════════════════════════════════════════════════════════
   POLYLANG INTEGRATION
═══════════════════════════════════════════════════════════ */

/* ── Helper: pll__() fallback ── */
function gh_t( string $string ): string {
    if ( function_exists( 'pll__' ) ) {
        return pll__( $string );
    }
    return $string;
}

add_action( 'init', 'gh_polylang_register_strings', 20 );

function gh_polylang_register_strings(): void {
    if ( ! function_exists( 'pll_register_string' ) ) return;

    $strings = [
        'whatsapp_number' => get_option( 'gh_whatsapp_number', '34600000000' ),
        'contact_email'   => get_option( 'gh_contact_email', get_option( 'admin_email' ) ),
        'hero_title'      => 'Caballos de calidad, venta directa',
        'hero_subtitle'   => 'Más de 30 caballos disponibles · Todas las razas · Entrega en Europa',
        'hero_badge'      => 'Venta directa · Sin intermediarios',
        'cta_title'       => 'Encuentra tu caballo ideal',
        'cta_subtitle'    => 'Contacta con nosotros hoy y te ayudaremos a encontrar el caballo perfecto.',
        'topbar_text'     => 'Venta directa · 30+ caballos disponibles · España · Entrega Europa',
    ];

    foreach ( $strings as $name => $value ) {
        pll_register_string( $name, $value, 'GallopHub' );
    }
}

/* ── Make horse CPT + discipline taxonomy translatable by Polylang ── */
add_filter( 'pll_get_post_types', 'gh_polylang_post_types' );

function gh_polylang_post_types( array $types ): array {
    $types['horse'] = 'horse';
    return $types;
}

add_filter( 'pll_get_taxonomies', 'gh_polylang_taxonomies' );

function gh_polylang_taxonomies( array $taxonomies ): array {
    $taxonomies['discipline'] = 'discipline';
    return $taxonomies;
}

/* ═══════════════════════════════════════════════════════════
   YOAST SEO / RANKMATH — CONFLICT PREVENTION
═══════════════════════════════════════════════════════════ */

function gh_seo_plugin_active(): bool {
    return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' );
}

/* ── Disable theme Schema.org output when a SEO plugin handles it ── */
add_action( 'wp', 'gh_maybe_disable_theme_schema' );

function gh_maybe_disable_theme_schema(): void {
    if ( gh_seo_plugin_active() ) {
        remove_action( 'wp_head', 'gh_output_schema_horse' );
    }
}

/* ── Remove Yoast/RM duplicate canonical / og tags ── */
add_action( 'after_setup_theme', function () {
    if ( ! gh_seo_plugin_active() ) return;
    add_filter( 'wpseo_canonical', '__return_false' );
    add_filter( 'rank_math/frontend/canonical', '__return_false' );
} );

/* ── Yoast Breadcrumb in header ── */
function gh_breadcrumb_seo(): void {
    if ( function_exists( 'yoast_breadcrumb' ) ) {
        yoast_breadcrumb(
            '<nav class="breadcrumb" aria-label="Breadcrumb"><div class="container"><div class="breadcrumb-inner">',
            '</div></div></nav>'
        );
    } elseif ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
        echo '<nav class="breadcrumb" aria-label="Breadcrumb"><div class="container"><div class="breadcrumb-inner">';
        rank_math_the_breadcrumbs();
        echo '</div></div></nav>';
    }
}

/* ── Fallback SEO meta (only when no SEO plugin active) ── */
function gh_seo_meta( string $title, string $description, string $image = '' ): void {
    if ( gh_seo_plugin_active() ) return; // let the plugin handle it

    $url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if ( ! $image ) {
        $image = GH_URI . '/assets/img/og-default.jpg';
    }
    ?>
    <meta name="description" content="<?= esc_attr( $description ) ?>">
    <meta property="og:title" content="<?= esc_attr( $title ) ?> | <?php bloginfo( 'name' ); ?>">
    <meta property="og:description" content="<?= esc_attr( $description ) ?>">
    <meta property="og:image" content="<?= esc_url( $image ) ?>">
    <meta property="og:url" content="<?= esc_url( $url ) ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc_attr( $title ) ?>">
    <meta name="twitter:description" content="<?= esc_attr( $description ) ?>">
    <meta name="twitter:image" content="<?= esc_url( $image ) ?>">
    <link rel="canonical" href="<?= esc_url( $url ) ?>">
    <?php
}

/* ═══════════════════════════════════════════════════════════
   WP MAIL SMTP — EMAIL CONFIG
═══════════════════════════════════════════════════════════ */
add_filter( 'wp_mail_from', 'gh_mail_from' );

function gh_mail_from( string $email ): string {
    $custom = get_option( 'gh_contact_email', '' );
    return $custom ?: $email;
}

add_filter( 'wp_mail_from_name', 'gh_mail_from_name' );

function gh_mail_from_name( string $name ): string {
    return 'GallopHub';
}

/* ═══════════════════════════════════════════════════════════
   COOKIE NOTICE / RGPD — CONDITIONAL SCRIPTS
═══════════════════════════════════════════════════════════ */
add_action( 'wp_head', 'gh_analytics_head', 1 );

function gh_analytics_head(): void {
    $ga4_id    = get_option( 'gh_ga4_id', '' );
    $pixel_id  = get_option( 'gh_fb_pixel_id', '' );

    // If Cookie Notice plugin is active, only inject if consent granted
    $consent_given = true;
    if ( function_exists( 'cn_cookies_accepted' ) ) {
        $consent_given = cn_cookies_accepted();
    }

    if ( ! $consent_given ) return;

    if ( $ga4_id ) : ?>
<!-- Google Analytics 4 — ID: <?= esc_html( $ga4_id ) ?> -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= esc_attr( $ga4_id ) ?>"></script>
<script>
window.dataLayer=window.dataLayer||[];
function gtag(){dataLayer.push(arguments);}
gtag('js',new Date());
gtag('config','<?= esc_js( $ga4_id ) ?>',{anonymize_ip:true});
</script>
    <?php endif;

    if ( $pixel_id ) : ?>
<!-- Meta Pixel — ID: <?= esc_html( $pixel_id ) ?> -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init','<?= esc_js( $pixel_id ) ?>');
fbq('track','PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=<?= esc_attr( $pixel_id ) ?>&ev=PageView&noscript=1"/></noscript>
    <?php endif;
}

/* ── Disable WooCommerce non-essential cookies until consent ── */
add_action( 'init', 'gh_woo_cookie_consent' );

function gh_woo_cookie_consent(): void {
    if ( ! function_exists( 'cn_cookies_accepted' ) ) return;
    if ( cn_cookies_accepted() ) return;

    // Prevent WooCommerce from setting session/cart cookies before consent
    add_filter( 'woocommerce_set_cookie_options', function ( array $options ) {
        // Only block non-essential cookies; session cookie is needed for cart
        return $options;
    } );

    // Remove WC tracking scripts
    add_filter( 'woocommerce_ga_integration_scripts', '__return_false' );
}

/* ═══════════════════════════════════════════════════════════
   DATABASE — CONTACT MESSAGES TABLE
═══════════════════════════════════════════════════════════ */
add_action( 'after_switch_theme', 'gh_create_db_tables' );

function gh_create_db_tables(): void {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}gh_contacts (
        id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        horse_id    BIGINT UNSIGNED DEFAULT NULL,
        name        VARCHAR(120) NOT NULL,
        email       VARCHAR(200) NOT NULL,
        phone       VARCHAR(50)  DEFAULT '',
        subject     VARCHAR(200) DEFAULT '',
        message     TEXT         NOT NULL,
        is_read     TINYINT(1)   DEFAULT 0,
        created_at  DATETIME     NOT NULL
    ) {$charset};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );

    // Flush rewrite rules
    gh_register_horse_cpt();
    flush_rewrite_rules();
}

/* ═══════════════════════════════════════════════════════════
   ADMIN — CONTACTS LIST
═══════════════════════════════════════════════════════════ */
add_action( 'admin_menu', 'gh_admin_menus' );

function gh_admin_menus(): void {
    add_menu_page(
        __( 'Messages contact', 'gallophub' ),
        __( 'Messages', 'gallophub' ),
        'manage_options',
        'gh-contacts',
        'gh_contacts_page',
        'dashicons-email-alt',
        6
    );

    add_options_page(
        'GallopHub',
        'GallopHub',
        'manage_options',
        'gallophub-settings',
        'gh_settings_page'
    );
}

function gh_contacts_page(): void {
    global $wpdb;
    $rows = $wpdb->get_results(
        "SELECT * FROM {$wpdb->prefix}gh_contacts ORDER BY created_at DESC LIMIT 200"
    );
    echo '<div class="wrap"><h1>' . esc_html__( 'Messages de contact', 'gallophub' ) . '</h1>';
    echo '<table class="widefat striped"><thead><tr>';
    foreach ( [ 'Date', 'Nom', 'Email', 'Téléphone', 'Sujet', 'Message', 'Cheval' ] as $h ) {
        echo '<th>' . esc_html( $h ) . '</th>';
    }
    echo '</tr></thead><tbody>';
    foreach ( $rows as $row ) {
        $horse = $row->horse_id ? get_the_title( (int) $row->horse_id ) : '—';
        printf(
            '<tr><td>%s</td><td>%s</td><td><a href="mailto:%s">%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
            esc_html( $row->created_at ),
            esc_html( $row->name ),
            esc_attr( $row->email ), esc_html( $row->email ),
            esc_html( $row->phone ),
            esc_html( $row->subject ),
            esc_html( wp_trim_words( $row->message, 15 ) ),
            esc_html( $horse )
        );
    }
    echo '</tbody></table></div>';
}

function gh_settings_page(): void {
    if ( isset( $_POST['gh_save'] ) && check_admin_referer( 'gh_settings' ) ) {
        $options = [
            'gh_whatsapp_number' => sanitize_text_field( $_POST['gh_whatsapp_number'] ?? '' ),
            'gh_contact_email'   => sanitize_email( $_POST['gh_contact_email'] ?? '' ),
            'gh_ga4_id'          => sanitize_text_field( $_POST['gh_ga4_id'] ?? '' ),
            'gh_fb_pixel_id'     => sanitize_text_field( $_POST['gh_fb_pixel_id'] ?? '' ),
            'gh_cookie_policy_page' => absint( $_POST['gh_cookie_policy_page'] ?? 0 ),
        ];
        foreach ( $options as $key => $value ) {
            update_option( $key, $value );
        }
        echo '<div class="notice notice-success"><p>' . esc_html__( 'Paramètres sauvegardés.', 'gallophub' ) . '</p></div>';
    }

    $wa    = get_option( 'gh_whatsapp_number', '34600000000' );
    $email = get_option( 'gh_contact_email', get_option( 'admin_email' ) );
    $ga4   = get_option( 'gh_ga4_id', '' );
    $pixel = get_option( 'gh_fb_pixel_id', '' );
    ?>
    <div class="wrap">
        <h1>GallopHub — <?php esc_html_e( 'Paramètres', 'gallophub' ); ?></h1>
        <form method="post">
            <?php wp_nonce_field( 'gh_settings' ); ?>
            <table class="form-table">
                <tr>
                    <th><label for="gh_whatsapp_number"><?php esc_html_e( 'Numéro WhatsApp', 'gallophub' ); ?></label></th>
                    <td>
                        <input class="regular-text" type="text" id="gh_whatsapp_number" name="gh_whatsapp_number" value="<?= esc_attr( $wa ) ?>">
                        <p class="description"><?php esc_html_e( 'Format : 34612345678 (sans +)', 'gallophub' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="gh_contact_email"><?php esc_html_e( 'Email de destination des formulaires', 'gallophub' ); ?></label></th>
                    <td><input class="regular-text" type="email" id="gh_contact_email" name="gh_contact_email" value="<?= esc_attr( $email ) ?>"></td>
                </tr>
                <tr>
                    <th><label for="gh_ga4_id"><?php esc_html_e( 'Google Analytics 4 (Measurement ID)', 'gallophub' ); ?></label></th>
                    <td>
                        <input class="regular-text" type="text" id="gh_ga4_id" name="gh_ga4_id" value="<?= esc_attr( $ga4 ) ?>" placeholder="G-XXXXXXXXXX">
                        <p class="description"><?php esc_html_e( 'Laisser vide si géré par Yoast/RankMath', 'gallophub' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="gh_fb_pixel_id"><?php esc_html_e( 'Meta Pixel ID', 'gallophub' ); ?></label></th>
                    <td><input class="regular-text" type="text" id="gh_fb_pixel_id" name="gh_fb_pixel_id" value="<?= esc_attr( $pixel ) ?>" placeholder="123456789012345"></td>
                </tr>
            </table>
            <?php submit_button( __( 'Sauvegarder', 'gallophub' ), 'primary', 'gh_save' ); ?>
        </form>
    </div>
    <?php
}

/* ═══════════════════════════════════════════════════════════
   HELPERS
═══════════════════════════════════════════════════════════ */

/** Simple breadcrumb — falls back to theme implementation if no SEO plugin */
function gh_breadcrumb( array $items ): void {
    if ( gh_seo_plugin_active() ) {
        gh_breadcrumb_seo();
        return;
    }
    echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'Fil d\'Ariane', 'gallophub' ) . '">';
    echo '<div class="container"><div class="breadcrumb-inner">';
    $last = array_key_last( $items );
    foreach ( $items as $i => [ $label, $url ] ) {
        if ( $i === $last ) {
            echo '<span class="breadcrumb-current">' . esc_html( $label ) . '</span>';
        } else {
            echo '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
            echo '<span class="breadcrumb-sep" aria-hidden="true">/</span>';
        }
    }
    echo '</div></div></nav>';
}

/** Schema.org output hook (disabled if SEO plugin active — see gh_maybe_disable_theme_schema) */
function gh_output_schema_horse(): void {
    if ( ! is_singular( 'horse' ) ) return;
    echo gh_horse_schema_org( get_the_ID() ); // defined in inc/schema.php
}
add_action( 'wp_head', 'gh_output_schema_horse' );
