<?php
/**
 * GallopHub — Schema.org + SEO meta
 *
 * @package GallopHub
 */

defined( 'ABSPATH' ) || exit;

/* ═══════════════════════════════════════════════════════════
   SCHEMA.ORG — HORSE PRODUCT
═══════════════════════════════════════════════════════════ */

/**
 * Generate Schema.org Product JSON-LD for a horse.
 * Called by functions.php::gh_output_schema_horse() only when
 * no SEO plugin is active.
 */
function gh_horse_schema_org( int $post_id ): string {
    $meta          = gh_get_horse_meta( $post_id );
    $photo_url     = get_the_post_thumbnail_url( $post_id, 'horse-hero' )
                     ?: get_template_directory_uri() . '/assets/img/og-default.jpg';
    $valid_until   = gmdate( 'Y-m-d', strtotime( '+30 days' ) );
    $availability  = $meta['status'] === 'available'
                     ? 'https://schema.org/InStock'
                     : 'https://schema.org/SoldOut';

    $disciplines = wp_get_post_terms( $post_id, 'discipline', [ 'fields' => 'names' ] );
    $desc        = wp_strip_all_tags( get_the_excerpt( $post_id ) )
                   ?: implode( ', ', (array) $disciplines );

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Product',
        'name'     => get_the_title( $post_id ),
        'description' => $desc,
        'image'    => [ $photo_url ],
        'url'      => get_permalink( $post_id ),
        'sku'      => 'GH-' . $post_id,
        'brand'    => [
            '@type' => 'Brand',
            'name'  => 'GallopHub',
        ],
        'offers'   => [
            '@type'           => 'Offer',
            'priceCurrency'   => 'EUR',
            'price'           => $meta['price'] > 0 ? (string) $meta['price'] : '0',
            'availability'    => $availability,
            'priceValidUntil' => $valid_until,
            'url'             => get_permalink( $post_id ),
            'seller'          => [
                '@type' => 'Organization',
                'name'  => 'GallopHub',
                'url'   => home_url( '/' ),
            ],
            'shippingDetails' => [
                '@type'        => 'OfferShippingDetails',
                'shippingRate' => [
                    '@type'    => 'MonetaryAmount',
                    'value'    => '800',
                    'currency' => 'EUR',
                ],
                'shippingDestination' => [
                    '@type'           => 'DefinedRegion',
                    'addressCountry'  => [ 'ES', 'NL', 'BE', 'FR', 'DE', 'PT' ],
                ],
                'deliveryTime' => [
                    '@type'        => 'ShippingDeliveryTime',
                    'handlingTime' => [
                        '@type'    => 'QuantitativeValue',
                        'minValue' => 1,
                        'maxValue' => 3,
                        'unitCode' => 'DAY',
                    ],
                    'transitTime' => [
                        '@type'    => 'QuantitativeValue',
                        'minValue' => 5,
                        'maxValue' => 15,
                        'unitCode' => 'DAY',
                    ],
                ],
            ],
            'hasMerchantReturnPolicy' => [
                '@type'                => 'MerchantReturnPolicy',
                'applicableCountry'    => [ 'ES', 'NL', 'BE', 'FR', 'DE', 'PT' ],
                'returnPolicyCategory' => 'https://schema.org/MerchantReturnNotPermitted',
                'merchantReturnDays'   => 30,
                'returnMethod'         => 'https://schema.org/ReturnByMail',
                'returnFees'           => 'https://schema.org/FreeReturn',
                'returnPolicySeasonalOverride' => [],
            ],
        ],
    ];

    // Optional additional properties
    if ( $meta['breed'] ) {
        $schema['additionalProperty'][] = [
            '@type' => 'PropertyValue',
            'name'  => 'Race',
            'value' => $meta['breed'],
        ];
    }
    if ( $meta['age'] ) {
        $schema['additionalProperty'][] = [
            '@type'     => 'PropertyValue',
            'name'      => 'Âge',
            'value'     => $meta['age'],
            'unitCode'  => 'ANN',
        ];
    }

    return '<script type="application/ld+json">'
           . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT )
           . '</script>' . "\n";
}

/* ═══════════════════════════════════════════════════════════
   SEO META FALLBACK (when no SEO plugin active)
═══════════════════════════════════════════════════════════ */

/**
 * Output basic meta tags.
 * No-op if Yoast SEO or RankMath is active.
 */
function gh_seo_meta( string $title, string $description, string $image = '' ): void {
    if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) return;

    $url = ( is_ssl() ? 'https://' : 'http://' ) . ( $_SERVER['HTTP_HOST'] ?? '' ) . ( $_SERVER['REQUEST_URI'] ?? '/' );
    if ( ! $image ) {
        $image = get_template_directory_uri() . '/assets/img/og-default.jpg';
    }
    $site_name = get_bloginfo( 'name' );
    $full_title = $title . ' | ' . $site_name;
    ?>
<meta name="description" content="<?= esc_attr( $description ) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= esc_attr( $site_name ) ?>">
<meta property="og:title" content="<?= esc_attr( $full_title ) ?>">
<meta property="og:description" content="<?= esc_attr( $description ) ?>">
<meta property="og:image" content="<?= esc_url( $image ) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="<?= esc_url( $url ) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= esc_attr( $full_title ) ?>">
<meta name="twitter:description" content="<?= esc_attr( $description ) ?>">
<meta name="twitter:image" content="<?= esc_url( $image ) ?>">
<link rel="canonical" href="<?= esc_url( $url ) ?>">
    <?php
}

/* ── Override Yoast/RM title on horse pages for correctness ── */
add_filter( 'wpseo_title', 'gh_yoast_horse_title' );
add_filter( 'rank_math/frontend/title', 'gh_yoast_horse_title' );

function gh_yoast_horse_title( string $title ): string {
    if ( ! is_singular( 'horse' ) ) return $title;

    $meta  = gh_get_horse_meta( get_the_ID() );
    $parts = array_filter( [ get_the_title(), $meta['breed'], $meta['age'] ? $meta['age'] . ' ans' : '' ] );
    return implode( ' — ', $parts ) . ' | ' . get_bloginfo( 'name' );
}

/* ── Feed Yoast with the horse featured image ── */
add_filter( 'wpseo_opengraph_image', 'gh_yoast_og_image' );

function gh_yoast_og_image( string $image ): string {
    if ( is_singular( 'horse' ) && has_post_thumbnail() ) {
        return (string) get_the_post_thumbnail_url( get_the_ID(), 'horse-hero' );
    }
    return $image;
}

/* ── Organization schema on homepage ── */
add_action( 'wp_head', 'gh_organization_schema' );

function gh_organization_schema(): void {
    if ( ! is_front_page() || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) return;
    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => 'GallopHub',
        'url'      => home_url( '/' ),
        'logo'     => get_template_directory_uri() . '/assets/img/logo.svg',
        'email'    => 'contact@gallophub.es',
        'address'  => [
            '@type'           => 'PostalAddress',
            'addressCountry'  => 'ES',
        ],
        'sameAs'   => [],
    ];
    echo '<script type="application/ld+json">'
         . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
         . '</script>' . "\n";
}
