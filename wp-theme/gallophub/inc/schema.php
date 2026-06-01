<?php
/**
 * Schema.org JSON-LD for horse single pages
 */

function gh_horse_schema_org( int $post_id ): string {
    $meta          = gh_get_horse_meta( $post_id );
    $photo_url     = get_the_post_thumbnail_url( $post_id, 'large' ) ?: get_template_directory_uri() . '/assets/img/placeholder.jpg';
    $valid_until   = date( 'Y-m-d', strtotime( '+30 days' ) );
    $availability  = $meta['status'] === 'available' ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut';
    $disciplines   = wp_get_post_terms( $post_id, 'discipline', [ 'fields' => 'names' ] );
    $desc          = get_the_excerpt( $post_id ) ?: implode( ', ', $disciplines );

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Product',
        'name'     => get_the_title( $post_id ),
        'description' => $desc,
        'image'    => $photo_url,
        'url'      => get_permalink( $post_id ),
        'offers'   => [
            '@type'           => 'Offer',
            'priceCurrency'   => 'EUR',
            'price'           => (string) $meta['price'],
            'availability'    => $availability,
            'priceValidUntil' => $valid_until,
            'url'             => get_permalink( $post_id ),
            'shippingDetails' => [
                '@type'        => 'OfferShippingDetails',
                'shippingRate' => [
                    '@type'    => 'MonetaryAmount',
                    'value'    => '800',
                    'currency' => 'EUR',
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
                'returnPolicyCategory' => 'https://schema.org/MerchantReturnNotPermitted',
                'merchantReturnDays'   => 30,
                'returnMethod'         => 'https://schema.org/ReturnByMail',
                'returnFees'           => 'https://schema.org/FreeReturn',
            ],
        ],
    ];

    return '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
}

/* ── SEO meta tags helper ── */
function gh_seo_meta( string $title, string $description, string $image = '' ): void {
    $url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if ( ! $image ) $image = get_template_directory_uri() . '/assets/img/og-default.jpg';
    ?>
    <meta name="description" content="<?= esc_attr( $description ) ?>">
    <meta property="og:title" content="<?= esc_attr( $title ) ?>">
    <meta property="og:description" content="<?= esc_attr( $description ) ?>">
    <meta property="og:image" content="<?= esc_url( $image ) ?>">
    <meta property="og:url" content="<?= esc_url( $url ) ?>">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="<?= esc_url( $url ) ?>">
    <?php
}
