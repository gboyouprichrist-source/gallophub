<?php
/**
 * Template Name: Boutique
 * Shop landing page — GallopHub
 */
defined( 'ABSPATH' ) || exit;

get_header();

gh_seo_meta(
    __( 'Boutique équestre — GallopHub', 'gallophub' ),
    __( 'Accessoires, équipements et services équestres. Selles, brides, couvertures, soins — livraison en Europe.', 'gallophub' )
);
?>

<!-- Hero -->
<section class="shop-hero">
    <div class="container">
        <h1><?php esc_html_e( 'Boutique équestre', 'gallophub' ); ?></h1>
        <p><?php esc_html_e( 'Accessoires, équipements, soins — sélectionnés par des cavaliers professionnels', 'gallophub' ); ?></p>
        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-gold">
            <?php esc_html_e( 'Voir tous les produits', 'gallophub' ); ?>
        </a>
        <?php endif; ?>
    </div>
</section>

<!-- Category highlights -->
<section class="shop-categories">
    <div class="container">
        <h2><?php esc_html_e( 'Nos catégories', 'gallophub' ); ?></h2>
        <div class="shop-cats-grid">
            <?php
            $cats = [
                [ 'icon' => '🐴', 'title' => __( 'Sellerie', 'gallophub' ),    'slug' => 'sellerie' ],
                [ 'icon' => '🧴', 'title' => __( 'Soins', 'gallophub' ),       'slug' => 'soins' ],
                [ 'icon' => '🧥', 'title' => __( 'Vêtements', 'gallophub' ),   'slug' => 'vetements' ],
                [ 'icon' => '📦', 'title' => __( 'Accessoires', 'gallophub' ), 'slug' => 'accessoires' ],
            ];
            if ( class_exists( 'WooCommerce' ) ) :
                foreach ( $cats as $cat ) :
                    $term = get_term_by( 'slug', $cat['slug'], 'product_cat' );
                    $url  = $term ? get_term_link( $term ) : wc_get_page_permalink( 'shop' );
                ?>
                <a href="<?php echo esc_url( $url ); ?>" class="shop-cat-card">
                    <span class="shop-cat-icon" aria-hidden="true"><?php echo esc_html( $cat['icon'] ); ?></span>
                    <span class="shop-cat-name"><?php echo esc_html( $cat['title'] ); ?></span>
                </a>
                <?php endforeach;
            else :
                foreach ( $cats as $cat ) : ?>
                <div class="shop-cat-card">
                    <span class="shop-cat-icon" aria-hidden="true"><?php echo esc_html( $cat['icon'] ); ?></span>
                    <span class="shop-cat-name"><?php echo esc_html( $cat['title'] ); ?></span>
                </div>
                <?php endforeach;
            endif;
            ?>
        </div>
    </div>
</section>

<!-- Featured products -->
<?php if ( class_exists( 'WooCommerce' ) ) : ?>
<section class="shop-featured">
    <div class="container">
        <h2><?php esc_html_e( 'Produits à la une', 'gallophub' ); ?></h2>
        <?php
        $featured = new WP_Query( [
            'post_type'      => 'product',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
            'tax_query'      => [ [
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ] ],
        ] );

        if ( $featured->have_posts() ) :
            woocommerce_product_loop_start();
            while ( $featured->have_posts() ) {
                $featured->the_post();
                wc_get_template_part( 'content', 'product' );
            }
            woocommerce_product_loop_end();
            wp_reset_postdata();
        else : ?>
            <p style="color:var(--sub);text-align:center;padding:3rem 0">
                <?php esc_html_e( 'Produits bientôt disponibles.', 'gallophub' ); ?>
            </p>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- Trust strip -->
<section class="trust-strip">
    <div class="container trust-strip-inner">
        <div class="trust-item">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
            </svg>
            <div>
                <strong><?php esc_html_e( 'Livraison Europe', 'gallophub' ); ?></strong>
                <span><?php esc_html_e( '3–7 jours ouvrés', 'gallophub' ); ?></span>
            </div>
        </div>
        <div class="trust-item">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
            </svg>
            <div>
                <strong><?php esc_html_e( 'Retour 30 jours', 'gallophub' ); ?></strong>
                <span><?php esc_html_e( 'Remboursement garanti', 'gallophub' ); ?></span>
            </div>
        </div>
        <div class="trust-item">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
            </svg>
            <div>
                <strong><?php esc_html_e( 'Paiement sécurisé', 'gallophub' ); ?></strong>
                <span><?php esc_html_e( 'SSL · Stripe · PayPal', 'gallophub' ); ?></span>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
