<?php
/**
 * WooCommerce single product — GallopHub
 * Overrides default WC template; horse products redirect to their CPT page.
 */
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

// If this product is linked to a horse CPT, redirect there.
$horse_id = (int) get_post_meta( get_the_ID(), 'gh_horse_id', true );
if ( $horse_id && get_post_status( $horse_id ) === 'publish' ) {
    wp_safe_redirect( get_permalink( $horse_id ), 301 );
    exit;
}
?>

<div class="container" style="padding-top:2rem;padding-bottom:7rem">
    <?php
    while ( have_posts() ) :
        the_post();
        wc_get_template_part( 'content', 'single-product' );
    endwhile;
    ?>
</div>

<?php
get_footer( 'shop' );
