<?php
/**
 * WooCommerce my account page — GallopHub
 */
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div class="archive-header">
    <div class="container">
        <h1>
            <?php
            $current_user = wp_get_current_user();
            echo esc_html( sprintf(
                /* translators: %s: customer username */
                __( 'Bonjour, %s', 'gallophub' ),
                $current_user->display_name
            ) );
            ?>
        </h1>
    </div>
</div>

<div class="container wc-page-wrap">

    <?php do_action( 'woocommerce_before_account_navigation' ); ?>

    <div class="myaccount-layout">

        <!-- Navigation -->
        <nav class="myaccount-nav" aria-label="<?php esc_attr_e( 'Navigation compte', 'gallophub' ); ?>">
            <?php do_action( 'woocommerce_account_navigation' ); ?>
        </nav>

        <!-- Content -->
        <div class="myaccount-content">
            <?php do_action( 'woocommerce_account_content' ); ?>
        </div>

    </div><!-- /.myaccount-layout -->

</div><!-- /.container -->

<?php get_footer( 'shop' ); ?>
