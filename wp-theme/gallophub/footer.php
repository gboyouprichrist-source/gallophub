</main><!-- /#main-content -->

<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-grid">

            <!-- Brand -->
            <div class="footer-brand">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="GallopHub">
                    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect width="40" height="40" rx="6" fill="#1A3C5E"/>
                        <path d="M8 28 C8 28 10 22 14 20 C14 20 13 17 15 15 C17 13 20 13 21 14 C22 15 23 13 25 12 C27 11 29 12 29 14 C29 16 28 17 27 17 C28 18 30 20 30 23 C30 26 28 28 26 28 L8 28Z" fill="#C8A951"/>
                        <path d="M14 28L14 32L16 32L16 28M20 28L20 32L22 32L22 28M25 28L25 32L27 32L27 28M9 28L9 32L11 32L11 28" fill="#C8A951"/>
                    </svg>
                    <span><span class="logo-gallop" style="color:#fff">Gallop</span><span class="logo-hub">Hub</span></span>
                </a>
                <p class="footer-tagline">Venta directa de caballos en España</p>
                <div>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        <a href="mailto:contact@gallophub.es">contact@gallophub.es</a>
                    </div>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        <span>España</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <a href="https://wa.me/<?php echo esc_attr( get_option( 'gh_whatsapp_number', '34600000000' ) ); ?>" target="_blank" rel="noopener noreferrer">WhatsApp</a>
                    </div>
                </div>
            </div>

            <!-- Horses -->
            <div class="footer-col">
                <h3 class="footer-col-title"><?php esc_html_e( 'Caballos', 'gallophub' ); ?></h3>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) ); ?>"><?php esc_html_e( 'Tous les caballos', 'gallophub' ); ?></a>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) . '?disciplines[]=dressage' ); ?>">Dressage</a>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) . '?disciplines[]=jumping' ); ?>">Saut</a>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) . '?disciplines[]=western' ); ?>">Western</a>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) . '?disciplines[]=leisure' ); ?>">Loisirs</a>
            </div>

            <!-- GallopHub -->
            <div class="footer-col">
                <h3 class="footer-col-title">GallopHub</h3>
                <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'À propos', 'gallophub' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'gallophub' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">FAQ</a>
                <a href="<?php echo esc_url( home_url( '/boutique/' ) ); ?>"><?php esc_html_e( 'Boutique', 'gallophub' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contactar', 'gallophub' ); ?></a>
            </div>

            <!-- Legal -->
            <div class="footer-col">
                <h3 class="footer-col-title"><?php esc_html_e( 'Legal', 'gallophub' ); ?></h3>
                <a href="<?php echo esc_url( home_url( '/cgv/' ) ); ?>"><?php esc_html_e( 'Condiciones de venta', 'gallophub' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/politique-de-retour/' ) ); ?>"><?php esc_html_e( 'Política de devolución', 'gallophub' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>"><?php esc_html_e( 'Aviso legal', 'gallophub' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/confidentialite/' ) ); ?>"><?php esc_html_e( 'Privacidad', 'gallophub' ); ?></a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© <?php echo date( 'Y' ); ?> GallopHub · gallophub.es · España</p>
            <p>Vendeur professionnel · Venta directa de caballos</p>
        </div>
    </div>
</footer>

<!-- Mobile bottom nav -->
<nav class="bottom-nav" aria-label="Navigation mobile">
    <div class="bottom-nav-inner">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bottom-nav-link">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Inicio
        </a>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) ); ?>" class="bottom-nav-link">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Caballos
        </a>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="bottom-nav-link">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Contacto
        </a>
    </div>
</nav>

<!-- WhatsApp float -->
<a href="https://wa.me/<?php echo esc_attr( get_option( 'gh_whatsapp_number', '34600000000' ) ); ?>"
   target="_blank" rel="noopener noreferrer"
   class="wa-float" aria-label="WhatsApp">
    <span class="wa-float-ring" aria-hidden="true"></span>
    <svg viewBox="0 0 24 24" fill="white" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
