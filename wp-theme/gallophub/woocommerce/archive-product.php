<?php
/**
 * WooCommerce archive/shop page — GallopHub
 */
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<!-- Shop archive header -->
<div class="archive-header">
    <div class="container">
        <?php woocommerce_page_title(); ?>
        <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
        <p><?php esc_html_e( 'Accessoires, équipements et services équestres', 'gallophub' ); ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="container" style="padding-top:2rem;padding-bottom:7rem">

    <?php if ( woocommerce_product_loop() ) : ?>

        <?php do_action( 'woocommerce_before_shop_loop' ); ?>

        <?php woocommerce_product_loop_start(); ?>

            <?php if ( wc_get_loop_prop( 'total' ) ) :
                while ( have_posts() ) :
                    the_post();
                    do_action( 'woocommerce_shop_loop' );
                    wc_get_template_part( 'content', 'product' );
                endwhile;
            endif; ?>

        <?php woocommerce_product_loop_end(); ?>

        <?php do_action( 'woocommerce_after_shop_loop' ); ?>

    <?php else : ?>
        <?php do_action( 'woocommerce_no_products_found' ); ?>
    <?php endif; ?>

</div><!-- /.container -->

<?php
get_footer( 'shop' );
