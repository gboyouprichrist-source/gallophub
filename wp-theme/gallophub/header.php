<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ── Top bar ── -->
<div class="topbar" role="banner">
    <?php
    echo esc_html( function_exists( 'pll__' )
        ? pll__( 'Venta directa · 30+ caballos disponibles · España · Entrega Europa' )
        : __( 'Venta directa · 30+ caballos disponibles · España · Entrega Europa', 'gallophub' )
    );
    ?>
</div>

<!-- ── Main navbar ── -->
<header class="site-header" id="site-header" role="banner">
    <div class="container">
        <nav class="nav-inner" aria-label="<?php esc_attr_e( 'Navigation principale', 'gallophub' ); ?>">

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
               class="site-logo"
               aria-label="GallopHub — <?php esc_attr_e( 'Accueil', 'gallophub' ); ?>">
                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                    <rect width="40" height="40" rx="6" fill="#1A3C5E"/>
                    <path d="M8 28 C8 28 10 22 14 20 C14 20 13 17 15 15 C17 13 20 13 21 14 C22 15 23 13 25 12 C27 11 29 12 29 14 C29 16 28 17 27 17 C28 18 30 20 30 23 C30 26 28 28 26 28 L8 28Z" fill="#C8A951"/>
                    <path d="M14 28L14 32L16 32L16 28M20 28L20 32L22 32L22 28M25 28L25 32L27 32L27 28M9 28L9 32L11 32L11 28" fill="#C8A951"/>
                </svg>
                <span>
                    <span class="logo-gallop">Gallop</span><span class="logo-hub">Hub</span>
                </span>
            </a>

            <!-- Desktop primary nav -->
            <ul class="primary-nav" role="list">
                <?php
                $archive_url = get_post_type_archive_link( 'horse' );
                $is_archive  = is_post_type_archive( 'horse' ) || is_singular( 'horse' );
                $nav_items   = [
                    [ 'url' => $archive_url,                            'label' => __( 'Todos los caballos', 'gallophub' ), 'active' => $is_archive ],
                    [ 'url' => $archive_url . '?disciplines[]=dressage', 'label' => __( 'Dressage', 'gallophub' ),          'active' => false ],
                    [ 'url' => $archive_url . '?disciplines[]=jumping',  'label' => __( 'Saut', 'gallophub' ),              'active' => false ],
                    [ 'url' => $archive_url . '?disciplines[]=western',  'label' => __( 'Western', 'gallophub' ),           'active' => false ],
                    [ 'url' => $archive_url . '?disciplines[]=leisure',  'label' => __( 'Loisirs', 'gallophub' ),           'active' => false ],
                    [ 'url' => $archive_url . '?disciplines[]=pony',     'label' => __( 'Poneys', 'gallophub' ),            'active' => false ],
                ];
                foreach ( $nav_items as $item ) :
                ?>
                    <li>
                        <a href="<?php echo esc_url( $item['url'] ); ?>"
                           <?php echo $item['active'] ? 'class="current-menu-item" aria-current="page"' : ''; ?>>
                            <?php echo esc_html( $item['label'] ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>

                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <li>
                    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
                       <?php echo is_shop() || is_woocommerce() ? 'class="current-menu-item"' : ''; ?>>
                        <?php esc_html_e( 'Boutique', 'gallophub' ); ?>
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Right actions -->
            <div class="nav-right">

                <!-- Language switcher -->
                <div class="lang-switcher" aria-label="<?php esc_attr_e( 'Langue', 'gallophub' ); ?>" role="navigation">
                    <?php
                    $langs = [ 'es' => 'Español', 'nl' => 'Nederlands', 'fr' => 'Français' ];
                    // If Polylang is active, render real language links
                    if ( function_exists( 'pll_the_languages' ) ) {
                        $pll_langs = pll_the_languages( [ 'raw' => 1 ] );
                        foreach ( $pll_langs as $lang ) {
                            $is_current = $lang['current_lang'];
                            $flag_code  = strtolower( substr( $lang['locale'], 0, 2 ) );
                            printf(
                                '<a href="%s" class="%s" aria-label="%s" hreflang="%s"><img src="https://flagcdn.com/w20/%s.png" width="20" height="15" alt="%s"></a>',
                                esc_url( $lang['url'] ),
                                $is_current ? 'active' : '',
                                esc_attr( $lang['name'] ),
                                esc_attr( $lang['slug'] ),
                                esc_attr( $flag_code ),
                                esc_attr( $lang['name'] )
                            );
                        }
                    } else {
                        // Fallback JS switcher
                        $cur = isset( $_COOKIE['gh_lang'] ) ? sanitize_key( $_COOKIE['gh_lang'] ) : 'es';
                        foreach ( $langs as $code => $label ) :
                        ?>
                            <a href="#"
                               data-lang="<?= esc_attr( $code ) ?>"
                               class="<?= $cur === $code ? 'active' : '' ?>"
                               aria-label="<?= esc_attr( $label ) ?>"
                               hreflang="<?= esc_attr( $code ) ?>">
                                <img src="https://flagcdn.com/w20/<?= esc_attr( $code ) ?>.png"
                                     width="20" height="15"
                                     alt="<?= esc_attr( $label ) ?>">
                            </a>
                        <?php endforeach;
                    }
                    ?>
                </div>

                <!-- WhatsApp button -->
                <?php $wa_num = esc_attr( get_option( 'gh_whatsapp_number', '34600000000' ) ); ?>
                <a href="https://wa.me/<?= $wa_num ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="nav-wa"
                   aria-label="WhatsApp">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14" aria-hidden="true" focusable="false">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    WhatsApp
                </a>

                <!-- WooCommerce mini-cart -->
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
                   class="nav-cart"
                   aria-label="<?php esc_attr_e( 'Panier', 'gallophub' ); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                    <span class="gh-cart-count"><?php echo absint( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
                </a>
                <?php endif; ?>

                <!-- CTA contact -->
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
                   class="btn-gold">
                    <?php esc_html_e( 'Contactar', 'gallophub' ); ?>
                </a>

                <!-- Hamburger toggle -->
                <button class="nav-toggle"
                        aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'gallophub' ); ?>"
                        aria-expanded="false"
                        aria-controls="mobile-drawer">
                    <span></span><span></span><span></span>
                </button>
            </div>

        </nav>
    </div>
</header>

<!-- ── Mobile drawer ── -->
<div class="mobile-drawer" id="mobile-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu mobile', 'gallophub' ); ?>">
    <div class="mobile-drawer-overlay" aria-hidden="true"></div>
    <div class="mobile-drawer-panel">
        <button class="mobile-drawer-close" aria-label="<?php esc_attr_e( 'Fermer le menu', 'gallophub' ); ?>">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) ); ?>"><?php esc_html_e( 'Todos los caballos', 'gallophub' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'À propos', 'gallophub' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'gallophub' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">FAQ</a>
        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Boutique', 'gallophub' ); ?></a>
        <?php endif; ?>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contactar', 'gallophub' ); ?></a>

        <!-- Lang switcher mobile -->
        <div class="lang-switcher" style="margin-top:1rem" aria-label="<?php esc_attr_e( 'Langue', 'gallophub' ); ?>">
            <?php
            $langs = [ 'es' => 'Español', 'nl' => 'Nederlands', 'fr' => 'Français' ];
            if ( function_exists( 'pll_the_languages' ) ) {
                $pll_langs = pll_the_languages( [ 'raw' => 1 ] );
                foreach ( $pll_langs as $lang ) {
                    $flag_code = strtolower( substr( $lang['locale'], 0, 2 ) );
                    printf(
                        '<a href="%s" class="%s" style="display:flex;align-items:center;gap:.35rem;font-size:13px;opacity:%s" aria-label="%s">
                            <img src="https://flagcdn.com/w20/%s.png" width="20" height="15" alt="%s">
                            %s
                        </a>',
                        esc_url( $lang['url'] ),
                        $lang['current_lang'] ? 'active' : '',
                        $lang['current_lang'] ? '1' : '.5',
                        esc_attr( $lang['name'] ),
                        esc_attr( $flag_code ),
                        esc_attr( $lang['name'] ),
                        esc_html( $lang['name'] )
                    );
                }
            } else {
                $cur = isset( $_COOKIE['gh_lang'] ) ? sanitize_key( $_COOKIE['gh_lang'] ) : 'es';
                foreach ( $langs as $code => $label ) :
                ?>
                    <a href="#"
                       data-lang="<?= esc_attr( $code ) ?>"
                       style="display:flex;align-items:center;gap:.35rem;font-size:13px;opacity:<?= $cur === $code ? '1' : '.5' ?>">
                        <img src="https://flagcdn.com/w20/<?= esc_attr( $code ) ?>.png" width="20" height="15" alt="<?= esc_attr( $label ) ?>">
                        <?= esc_html( $label ) ?>
                    </a>
                <?php endforeach;
            }
            ?>
        </div>
    </div>
</div>

<!-- ── Yoast / RankMath Breadcrumb (injected below navbar on inner pages) ── -->
<?php if ( ! is_front_page() ) : ?>
    <?php gh_breadcrumb_seo(); ?>
<?php endif; ?>

<main class="site-main" id="main-content" tabindex="-1">
