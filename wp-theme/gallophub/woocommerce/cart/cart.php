<?php
/**
 * WooCommerce cart page — GallopHub
 */
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div class="archive-header">
    <div class="container">
        <h1><?php esc_html_e( 'Panier', 'gallophub' ); ?></h1>
    </div>
</div>

<div class="container wc-page-wrap">

    <?php do_action( 'woocommerce_before_cart' ); ?>

    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

        <?php do_action( 'woocommerce_before_cart_table' ); ?>

        <table class="wc-cart-table" cellspacing="0">
            <thead>
                <tr>
                    <th class="product-remove"></th>
                    <th class="product-thumbnail">&nbsp;</th>
                    <th class="product-name"><?php esc_html_e( 'Produit', 'gallophub' ); ?></th>
                    <th class="product-price"><?php esc_html_e( 'Prix', 'gallophub' ); ?></th>
                    <th class="product-quantity"><?php esc_html_e( 'Quantité', 'gallophub' ); ?></th>
                    <th class="product-subtotal"><?php esc_html_e( 'Sous-total', 'gallophub' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] === 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        continue;
                    }
                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                ?>
                <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                    <td class="product-remove">
                        <?php
                        echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s">&times;</a>',
                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                esc_attr__( 'Supprimer cet article', 'gallophub' ),
                                esc_attr( $product_id ),
                                esc_attr( $cart_item_key )
                            ),
                            $cart_item_key
                        );
                        ?>
                    </td>

                    <td class="product-thumbnail">
                        <?php
                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                        if ( $product_permalink ) {
                            echo '<a href="' . esc_url( $product_permalink ) . '">' . $thumbnail . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput
                        } else {
                            echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput
                        }
                        ?>
                    </td>

                    <td class="product-name" data-title="<?php esc_attr_e( 'Produit', 'gallophub' ); ?>">
                        <?php
                        $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                        if ( $product_permalink ) {
                            echo '<a href="' . esc_url( $product_permalink ) . '">' . wp_kses_post( $product_name ) . '</a>';
                        } else {
                            echo wp_kses_post( $product_name );
                        }
                        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
                        echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput
                        ?>
                    </td>

                    <td class="product-price" data-title="<?php esc_attr_e( 'Prix', 'gallophub' ); ?>">
                        <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                    </td>

                    <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantité', 'gallophub' ); ?>">
                        <?php
                        if ( $_product->is_sold_individually() ) {
                            $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1">', $cart_item_key );
                        } else {
                            $product_quantity = woocommerce_quantity_input(
                                [
                                    'input_name'   => "cart[{$cart_item_key}][qty]",
                                    'input_value'  => $cart_item['quantity'],
                                    'max_value'    => $_product->get_max_purchase_quantity(),
                                    'min_value'    => 0,
                                    'product_name' => $_product->get_name(),
                                ],
                                $_product,
                                false
                            );
                        }
                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput
                        ?>
                    </td>

                    <td class="product-subtotal" data-title="<?php esc_attr_e( 'Sous-total', 'gallophub' ); ?>">
                        <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                    </td>

                </tr>
                <?php endforeach; ?>

                <?php do_action( 'woocommerce_cart_contents' ); ?>

                <tr>
                    <td colspan="6" class="actions">
                        <?php if ( wc_coupons_enabled() ) : ?>
                        <div class="coupon">
                            <label for="coupon_code"><?php esc_html_e( 'Code promo', 'gallophub' ); ?></label>
                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" placeholder="<?php esc_attr_e( 'Entrez votre code', 'gallophub' ); ?>">
                            <button type="submit" class="btn-navy" name="apply_coupon" value="<?php esc_attr_e( 'Appliquer', 'gallophub' ); ?>">
                                <?php esc_html_e( 'Appliquer', 'gallophub' ); ?>
                            </button>
                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
                        <?php endif; ?>

                        <button type="submit" class="btn-outline" name="update_cart" value="<?php esc_attr_e( 'Mettre à jour', 'gallophub' ); ?>">
                            <?php esc_html_e( 'Mettre à jour le panier', 'gallophub' ); ?>
                        </button>

                        <?php do_action( 'woocommerce_cart_actions' ); ?>
                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                    </td>
                </tr>

                <?php do_action( 'woocommerce_after_cart_contents' ); ?>
            </tbody>
        </table>

        <?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>

    <div class="cart-collaterals">
        <?php do_action( 'woocommerce_cart_collaterals' ); ?>
    </div>

    <?php do_action( 'woocommerce_after_cart' ); ?>

</div><!-- /.container -->

<?php get_footer( 'shop' ); ?>
