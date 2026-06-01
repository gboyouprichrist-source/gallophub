<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Top bar -->
<div class="topbar">
    Venta directa · 30+ caballos disponibles · España · Entrega Europa
</div>

<!-- Navbar -->
<header class="site-header" id="site-header">
    <div class="container">
        <nav class="nav-inner" aria-label="Navigation principale">

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="GallopHub — Accueil">
                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect width="40" height="40" rx="6" fill="#1A3C5E"/>
                    <path d="M8 28 C8 28 10 22 14 20 C14 20 13 17 15 15 C17 13 20 13 21 14 C22 15 23 13 25 12 C27 11 29 12 29 14 C29 16 28 17 27 17 C28 18 30 20 30 23 C30 26 28 28 26 28 L8 28Z" fill="#C8A951"/>
                    <path d="M14 28L14 32L16 32L16 28M20 28L20 32L22 32L22 28M25 28L25 32L27 32L27 28M9 28L9 32L11 32L11 28" fill="#C8A951"/>
                </svg>
                <span><span class="logo-gallop">Gallop</span><span class="logo-hub">Hub</span></span>
            </a>

            <!-- Primary nav -->
            <ul class="primary-nav" role="list">
                <li><a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) ); ?>" <?php echo is_post_type_archive('horse') ? 'class="current-menu-item"' : ''; ?>>
                    <?php esc_html_e( 'Tous les caballos', 'gallophub' ); ?>
                </a></li>
                <li><a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) . '?disciplines[]=dressage' ); ?>">Dressage</a></li>
                <li><a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) . '?disciplines[]=jumping' ); ?>">Saut</a></li>
                <li><a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) . '?disciplines[]=western' ); ?>">Western</a></li>
                <li><a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) . '?disciplines[]=leisure' ); ?>">Loisirs</a></li>
                <li><a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) . '?disciplines[]=pony' ); ?>">Poneys</a></li>
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <li><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Boutique', 'gallophub' ); ?></a></li>
                <?php else: ?>
                <li><a href="<?php echo esc_url( home_url( '/boutique/' ) ); ?>"><?php esc_html_e( 'Boutique', 'gallophub' ); ?></a></li>
                <?php endif; ?>
            </ul>

            <!-- Right -->
            <div class="nav-right">
                <!-- Language switcher -->
                <div class="lang-switcher" aria-label="Langue">
                    <?php
                    $langs = [ 'es' => 'Español', 'nl' => 'Nederlands', 'fr' => 'Français' ];
                    $cur   = isset( $_COOKIE['gh_lang'] ) ? $_COOKIE['gh_lang'] : 'es';
                    foreach ( $langs as $code => $label ) :
                    ?>
                        <a href="#" data-lang="<?= esc_attr( $code ) ?>" class="<?= $cur === $code ? 'active' : '' ?>" aria-label="<?= esc_attr( $label ) ?>">
                            <img src="https://flagcdn.com/w20/<?= esc_attr( $code ) ?>.png" width="20" height="15" alt="<?= esc_attr( $label ) ?>">
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- WhatsApp -->
                <a href="https://wa.me/<?php echo esc_attr( get_option( 'gh_whatsapp_number', '34600000000' ) ); ?>"
                   target="_blank" rel="noopener noreferrer" class="nav-wa" aria-label="WhatsApp">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>

                <!-- CTA -->
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-gold">
                    <?php esc_html_e( 'Contactar', 'gallophub' ); ?>
                </a>

                <!-- Hamburger -->
                <button class="nav-toggle" aria-label="Menu" aria-expanded="false" aria-controls="mobile-drawer">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </nav>
    </div>
</header>

<!-- Mobile drawer -->
<div class="mobile-drawer" id="mobile-drawer" role="dialog" aria-modal="true" aria-label="Menu mobile">
    <div class="mobile-drawer-overlay"></div>
    <div class="mobile-drawer-panel">
        <button class="mobile-drawer-close" aria-label="Fermer le menu">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) ); ?>"><?php esc_html_e( 'Tous les caballos', 'gallophub' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'À propos', 'gallophub' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'gallophub' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">FAQ</a>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contactar', 'gallophub' ); ?></a>
        <div class="lang-switcher" style="margin-top:1rem">
            <?php foreach ( $langs as $code => $label ) : ?>
                <a href="#" data-lang="<?= esc_attr( $code ) ?>" style="display:flex;align-items:center;gap:.35rem;font-size:13px;<?= $cur === $code ? 'opacity:1' : 'opacity:.5' ?>">
                    <img src="https://flagcdn.com/w20/<?= esc_attr( $code ) ?>.png" width="20" height="15" alt="<?= esc_attr( $label ) ?>">
                    <?= esc_html( $label ) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<main class="site-main" id="main-content">
