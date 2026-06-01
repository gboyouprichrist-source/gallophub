<?php
/**
 * Homepage
 */
get_header();

$wa_num = get_option( 'gh_whatsapp_number', '34600000000' );
$wa_msg = urlencode( 'Bonjour, je souhaite en savoir plus sur vos chevaux.' );

// SEO meta
gh_seo_meta(
    'GallopHub — Venta directa de caballos en España | 30+ caballos',
    'Más de 30 caballos disponibles. Todas las razas y disciplinas. Venta directa, sin intermediarios. Entrega en Europa.',
    'https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=1200'
);
?>

<!-- ── HERO ── -->
<section class="hero" style="background-image:linear-gradient(to right,rgba(26,60,94,.92) 0%,rgba(26,60,94,.35) 100%),url('https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=1920')">
    <div class="container">
        <div class="hero-content">
            <span class="hero-badge">Venta directa · Sin intermediarios</span>
            <h1>Caballos de calidad,<br>venta directa</h1>
            <p class="hero-subtitle">Más de 30 caballos disponibles · Todas las razas · Entrega en Europa</p>
            <div class="hero-ctas">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) ); ?>" class="btn-hero-primary">
                    Ver caballos
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="https://wa.me/<?= esc_attr( $wa_num ) ?>?text=<?= esc_attr( $wa_msg ) ?>"
                   target="_blank" rel="noopener noreferrer" class="btn-wa">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── STATS BAR ── -->
<div class="stats-bar">
    <div class="container">
        <div class="stats-bar-grid">
            <div><div class="stats-bar-value">30+</div><div class="stats-bar-label">Caballos disponibles</div></div>
            <div><div class="stats-bar-value">20+</div><div class="stats-bar-label">Años de experiencia</div></div>
            <div><div class="stats-bar-value">100%</div><div class="stats-bar-label">Venta directa</div></div>
            <div><div class="stats-bar-value">EU</div><div class="stats-bar-label">Entrega Europa</div></div>
        </div>
    </div>
</div>

<!-- ── LATEST HORSES ── -->
<section class="section-py">
    <div class="container">
        <div class="section-head">
            <div>
                <h2 class="section-title">Últimos caballos</h2>
                <p class="section-sub">Selección actualizada regularmente</p>
            </div>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) ); ?>" class="section-link">
                Ver todos
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <?php
        $horses = new WP_Query( [
            'post_type'      => 'horse',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ] );
        ?>
        <div class="grid-3" id="horses-grid">
            <?php if ( $horses->have_posts() ) :
                while ( $horses->have_posts() ) { $horses->the_post(); get_template_part( 'template-parts/horse-card' ); }
                wp_reset_postdata();
            else : ?>
                <p style="color:var(--sub);grid-column:1/-1">Aucun cheval pour le moment. <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) ); ?>" style="color:var(--navy);text-decoration:underline">Voir tous</a></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ── WHY US ── -->
<section class="section-py section-muted">
    <div class="container">
        <h2 class="section-title text-center" style="margin-bottom:3rem">¿Por qué GallopHub?</h2>
        <div class="grid-3">
            <div class="why-card">
                <div class="why-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
                <h3>Venta 100% directa</h3>
                <p>Sin intermediarios. Trato directo con el propietario. Precios justos y transparentes.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                </div>
                <h3>20+ años de experiencia</h3>
                <p>Seleccionamos cada caballo con criterio. Conocemos las razas, disciplinas y necesidades.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                </div>
                <h3>Entrega en toda Europa</h3>
                <p>Transportistas equinos homologados. Cobertura España, Países Bajos, Bélgica y más.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── TESTIMONIALS ── -->
<section class="section-py">
    <div class="container">
        <h2 class="section-title text-center" style="margin-bottom:3rem">Lo que dicen nuestros clientes</h2>
        <div class="grid-3">
            <?php
            $testimonials = [
                [ 'name' => 'María García', 'location' => 'España', 'flag' => 'es',
                  'text' => 'Compré mi Andaluz de GallopHub y estoy encantada. El proceso fue transparente y el caballo tal como lo describieron.',
                  'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=80' ],
                [ 'name' => 'Jan van der Berg', 'location' => 'Nederland', 'flag' => 'nl',
                  'text' => 'Uitstekende service! Het paard werd veilig geleverd en komt overeen met de beschrijving. Zeer aan te raden.',
                  'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80' ],
                [ 'name' => 'Sophie Dubois', 'location' => 'Belgique', 'flag' => 'be',
                  'text' => 'J\'ai acheté un magnifique PRE via GallopHub. Communication parfaite, livraison rapide, cheval conforme.',
                  'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=80' ],
            ];
            foreach ( $testimonials as $t ) : ?>
                <div class="testimonial-card">
                    <div class="stars">
                        <?php for ( $i = 0; $i < 5; $i++ ) : ?>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <p class="testimonial-text">"<?= esc_html( $t['text'] ) ?>"</p>
                    <div class="testimonial-author">
                        <img src="<?= esc_url( $t['avatar'] ) ?>" alt="<?= esc_attr( $t['name'] ) ?>" width="40" height="40">
                        <div>
                            <div class="testimonial-name"><?= esc_html( $t['name'] ) ?></div>
                            <div class="testimonial-loc">
                                <img src="https://flagcdn.com/w20/<?= esc_attr( $t['flag'] ) ?>.png" width="16" height="12" alt="<?= esc_attr( $t['location'] ) ?>">
                                <?= esc_html( $t['location'] ) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── CTA BANNER ── -->
<section class="cta-banner">
    <div class="container">
        <h2>Encuentra tu caballo ideal</h2>
        <p>Contacta con nosotros hoy y te ayudaremos a encontrar el caballo perfecto.</p>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-gold" style="font-size:15px;padding:.85rem 2rem">
            Contactar ahora
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

<?php get_footer(); ?>
