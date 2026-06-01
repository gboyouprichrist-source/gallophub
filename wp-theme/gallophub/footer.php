<?php
defined( 'ABSPATH' ) || exit;
?>
</main><!-- /#main-content -->

<!-- ── Footer ── -->
<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-grid">

            <!-- Col 1 — Brand -->
            <div class="footer-brand">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="GallopHub">
                    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="36" height="36">
                        <rect width="40" height="40" rx="6" fill="#1A3C5E"/>
                        <path d="M8 28 C8 28 10 22 14 20 C14 20 13 17 15 15 C17 13 20 13 21 14 C22 15 23 13 25 12 C27 11 29 12 29 14 C29 16 28 17 27 17 C28 18 30 20 30 23 C30 26 28 28 26 28 L8 28Z" fill="#C8A951"/>
                        <path d="M14 28L14 32L16 32L16 28M20 28L20 32L22 32L22 28M25 28L25 32L27 32L27 28M9 28L9 32L11 32L11 28" fill="#C8A951"/>
                    </svg>
                    <span>
                        <span class="logo-gallop">Gallop</span><span class="logo-hub">Hub</span>
                    </span>
                </a>
                <p class="footer-tagline">
                    <?php esc_html_e( 'Vente de chevaux de sport — Espagne · Livraison Europe', 'gallophub' ); ?>
                </p>
                <address class="footer-contact">
                    <a href="mailto:contact@gallophub.es">contact@gallophub.es</a><br>
                    <?php $wa_num = get_option( 'gh_whatsapp_number', '34600000000' ); ?>
                    <a href="https://wa.me/<?php echo esc_attr( $wa_num ); ?>" target="_blank" rel="noopener noreferrer">
                        +<?php echo esc_html( $wa_num ); ?>
                    </a>
                </address>
            </div>

            <!-- Col 2 — Horses -->
            <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Catégories chevaux', 'gallophub' ); ?>">
                <h3><?php esc_html_e( 'Chevaux', 'gallophub' ); ?></h3>
                <?php
                $archive = get_post_type_archive_link( 'horse' );
                $cats    = [
                    [ 'url' => $archive,                             'label' => __( 'Tous les chevaux', 'gallophub' ) ],
                    [ 'url' => $archive . '?disciplines[]=dressage', 'label' => __( 'Dressage', 'gallophub' ) ],
                    [ 'url' => $archive . '?disciplines[]=jumping',  'label' => __( "Saut d'obstacles", 'gallophub' ) ],
                    [ 'url' => $archive . '?disciplines[]=western',  'label' => __( 'Western', 'gallophub' ) ],
                    [ 'url' => $archive . '?disciplines[]=leisure',  'label' => __( 'Loisirs', 'gallophub' ) ],
                    [ 'url' => $archive . '?disciplines[]=pony',     'label' => __( 'Poneys', 'gallophub' ) ],
                ];
                ?>
                <ul role="list">
                    <?php foreach ( $cats as $cat ) : ?>
                    <li><a href="<?php echo esc_url( $cat['url'] ); ?>"><?php echo esc_html( $cat['label'] ); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <!-- Col 3 — Company -->
            <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Liens société', 'gallophub' ); ?>">
                <h3><?php esc_html_e( 'GallopHub', 'gallophub' ); ?></h3>
                <ul role="list">
                    <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'À propos', 'gallophub' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'gallophub' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">FAQ</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'gallophub' ); ?></a></li>
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <li><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Boutique', 'gallophub' ); ?></a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <!-- Col 4 — Legal -->
            <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Liens légaux', 'gallophub' ); ?>">
                <h3><?php esc_html_e( 'Informations légales', 'gallophub' ); ?></h3>
                <ul role="list">
                    <li><a href="<?php echo esc_url( home_url( '/cgv/' ) ); ?>"><?php esc_html_e( 'Conditions générales de vente', 'gallophub' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>"><?php esc_html_e( 'Mentions légales', 'gallophub' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>"><?php esc_html_e( 'Politique de confidentialité', 'gallophub' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/retours/' ) ); ?>"><?php esc_html_e( 'Politique de retour', 'gallophub' ); ?></a></li>
                </ul>

                <!-- Payment badges -->
                <div class="footer-badges" aria-label="<?php esc_attr_e( 'Moyens de paiement acceptés', 'gallophub' ); ?>">
                    <svg viewBox="0 0 38 24" width="38" height="24" aria-label="Visa" role="img">
                        <rect width="38" height="24" rx="4" fill="#1A1F71"/>
                        <text x="19" y="16" text-anchor="middle" fill="#fff" font-size="10" font-family="Arial" font-weight="bold">VISA</text>
                    </svg>
                    <svg viewBox="0 0 38 24" width="38" height="24" aria-label="Mastercard" role="img">
                        <rect width="38" height="24" rx="4" fill="#252525"/>
                        <circle cx="15" cy="12" r="7" fill="#EB001B"/>
                        <circle cx="23" cy="12" r="7" fill="#F79E1B"/>
                        <path d="M19 6.8a7 7 0 010 10.4A7 7 0 0119 6.8z" fill="#FF5F00"/>
                    </svg>
                    <svg viewBox="0 0 38 24" width="38" height="24" aria-label="PayPal" role="img">
                        <rect width="38" height="24" rx="4" fill="#003087"/>
                        <text x="19" y="16" text-anchor="middle" fill="#009CDE" font-size="8" font-family="Arial" font-weight="bold">PayPal</text>
                    </svg>
                </div>
            </nav>

        </div><!-- /.footer-grid -->

        <!-- Bottom bar -->
        <div class="footer-bottom">
            <p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php esc_html_e( 'GallopHub — Tous droits réservés', 'gallophub' ); ?></p>
            <p class="footer-legal-links">
                <a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>"><?php esc_html_e( 'Mentions légales', 'gallophub' ); ?></a>
                &middot;
                <a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>"><?php esc_html_e( 'Confidentialité', 'gallophub' ); ?></a>
                &middot;
                <a href="<?php echo esc_url( home_url( '/cgv/' ) ); ?>">CGV</a>
            </p>
        </div>

    </div><!-- /.container -->
</footer>

<!-- ── WhatsApp float ── -->
<?php $wa_num = get_option( 'gh_whatsapp_number', '34600000000' ); ?>
<a href="https://wa.me/<?php echo esc_attr( $wa_num ); ?>?text=<?php echo rawurlencode( __( 'Bonjour, je suis intéressé(e) par un cheval sur GallopHub.', 'gallophub' ) ); ?>"
   target="_blank"
   rel="noopener noreferrer"
   class="whatsapp-float"
   aria-label="WhatsApp">
    <svg viewBox="0 0 24 24" fill="currentColor" width="26" height="26" aria-hidden="true" focusable="false">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
    <span class="whatsapp-pulse" aria-hidden="true"></span>
</a>

<!-- ── Mobile bottom nav ── -->
<nav class="mobile-bottom-nav" aria-label="<?php esc_attr_e( 'Navigation mobile', 'gallophub' ); ?>">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo is_front_page() ? 'active' : ''; ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
        </svg>
        <span><?php esc_html_e( 'Accueil', 'gallophub' ); ?></span>
    </a>
    <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) ); ?>" class="<?php echo ( is_post_type_archive( 'horse' ) || is_singular( 'horse' ) ) ? 'active' : ''; ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
        </svg>
        <span><?php esc_html_e( 'Chevaux', 'gallophub' ); ?></span>
    </a>
    <a href="https://wa.me/<?php echo esc_attr( $wa_num ); ?>" target="_blank" rel="noopener noreferrer" class="mobile-nav-wa">
        <svg viewBox="0 0 24 24" fill="currentColor" width="22" height="22" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span>WhatsApp</span>
    </a>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="<?php echo is_page( 'contact' ) ? 'active' : ''; ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
        </svg>
        <span><?php esc_html_e( 'Contact', 'gallophub' ); ?></span>
    </a>
</nav>

<!-- ── Meta Pixel noscript fallback (consent-gated) ── -->
<?php
$pixel_id = get_option( 'gh_meta_pixel_id', '' );
if ( $pixel_id && function_exists( 'cn_cookies_accepted' ) && cn_cookies_accepted() ) :
?>
<noscript>
    <img height="1" width="1" style="display:none"
         src="https://www.facebook.com/tr?id=<?php echo esc_attr( $pixel_id ); ?>&ev=PageView&noscript=1"
         alt="">
</noscript>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
