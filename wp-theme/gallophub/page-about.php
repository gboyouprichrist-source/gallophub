<?php /* Template Name: À propos */ get_header();
gh_seo_meta( 'À propos de GallopHub — Vente directe de chevaux en Espagne', 'Plus de 20 ans de passion pour les chevaux. Vente directe, transparente, avec livraison dans toute l\'Europe.' );
?>
<section class="page-hero">
    <div class="container">
        <h1>Notre histoire</h1>
        <p>Plus de 20 ans de passion pour les chevaux et la vente directe</p>
    </div>
</section>

<!-- Story -->
<section class="section-py">
    <div class="container">
        <div class="two-col">
            <img src="https://images.unsplash.com/photo-1599595546558-26c81b4dcd12?w=800" alt="Élevage GallopHub" class="about-img" loading="lazy" width="800" height="600">
            <div class="about-text">
                <span class="about-badge">Notre histoire</span>
                <h2 style="font-size:clamp(1.75rem,3.5vw,2.5rem);margin-bottom:1.5rem">La passion des chevaux<br>depuis 20 ans</h2>
                <p>GallopHub est né d'une passion profonde pour les chevaux et d'un constat simple : le marché de la vente de chevaux manque de transparence et de confiance. Nous avons voulu changer cela.</p>
                <p>Basés en Espagne, nous sélectionnons chaque cheval avec soin — toutes les races, toutes les disciplines. Notre priorité : que l'acheteur et le cheval soient parfaitement compatibles.</p>
                <p>Nous livrons dans toute l'Europe et accompagnons chaque vente de A à Z : visites, examens vétérinaires, transport, administratif.</p>
            </div>
        </div>
    </div>
</section>

<!-- Commitments -->
<section class="section-py section-muted">
    <div class="container">
        <h2 class="section-title text-center" style="margin-bottom:3rem">Nos engagements</h2>
        <div class="grid-3">
            <?php
            $items = [
                [ 'title' => 'Transparencia total',   'desc' => 'Documentación completa, visitas posibles, sin sorpresas.',                  'icon' => 'shield' ],
                [ 'title' => 'Selección rigurosa',    'desc' => 'Cada caballo evaluado por su temperamento, salud y aptitudes.',            'icon' => 'star' ],
                [ 'title' => 'Transporte seguro',     'desc' => 'Transportistas homologados, trayectos bien planificados.',                  'icon' => 'truck' ],
                [ 'title' => 'Bienestar animal',      'desc' => 'Nuestros caballos viven en condiciones óptimas hasta la venta.',           'icon' => 'heart' ],
                [ 'title' => '20+ años experiencia',  'desc' => 'Conocemos el mercado, las razas y las expectativas de los compradores.',   'icon' => 'award' ],
                [ 'title' => 'Red europea',           'desc' => 'Clientes en 15+ países. Entregamos en toda Europa.',                       'icon' => 'globe' ],
            ];
            $icons = [
                'shield' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>',
                'star'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>',
                'truck'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>',
                'heart'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>',
                'award'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>',
                'globe'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>',
            ];
            foreach ( $items as $c ) : ?>
                <div class="why-card">
                    <div class="why-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><?= $icons[ $c['icon'] ] ?></svg>
                    </div>
                    <h3><?= esc_html( $c['title'] ) ?></h3>
                    <p><?= esc_html( $c['desc'] ) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stats -->
<div class="stats-bar" style="padding:3rem 0">
    <div class="container">
        <div class="stats-bar-grid">
            <div><div class="stats-bar-value">20+</div><div class="stats-bar-label">Années d'expérience</div></div>
            <div><div class="stats-bar-value">200+</div><div class="stats-bar-label">Chevaux vendus</div></div>
            <div><div class="stats-bar-value">15+</div><div class="stats-bar-label">Pays</div></div>
            <div><div class="stats-bar-value">98%</div><div class="stats-bar-label">Clients satisfaits</div></div>
        </div>
    </div>
</div>

<!-- CTA -->
<section class="cta-banner">
    <div class="container">
        <h2>Prêt à trouver votre cheval ?</h2>
        <p>Parcourez notre sélection ou contactez-nous directement.</p>
        <div style="display:flex;flex-wrap:wrap;gap:1rem;justify-content:center">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) ); ?>" class="btn-navy" style="font-size:15px;padding:.85rem 1.75rem">
                Voir les caballos
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-gold" style="font-size:15px;padding:.85rem 1.75rem">Nous contacter</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
