<?php
/**
 * Single horse detail page
 */
get_header();

if ( ! have_posts() ) { get_footer(); return; }

the_post();
$meta        = gh_get_horse_meta( get_the_ID() );
$status      = gh_status_info( $meta['status'] );
$flag_code   = gh_country_to_flag( $meta['country'] );
$disciplines = wp_get_post_terms( get_the_ID(), 'discipline', [ 'fields' => 'names' ] );
$wa_num      = get_option( 'gh_whatsapp_number', '34600000000' );
$wa_msg      = urlencode( 'Bonjour, je suis intéressé par le cheval ' . get_the_title() . ' — ' . get_permalink() );

// Gallery images — main thumb + attachments
$main_img  = get_the_post_thumbnail_url( get_the_ID(), 'horse-hero' );
$gallery   = get_posts( [
    'post_type'      => 'attachment',
    'post_status'    => 'inherit',
    'post_parent'    => get_the_ID(),
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'posts_per_page' => 10,
] );
if ( $main_img && empty( array_filter( $gallery, fn($g) => wp_get_attachment_image_url( $g->ID, 'horse-hero' ) === $main_img ) ) ) {
    // prepend featured
}

// SEO + Schema
gh_seo_meta(
    get_the_title() . ' — ' . $meta['breed'] . ' — GallopHub',
    get_the_excerpt() ?: get_the_title() . ', ' . $meta['breed'] . ', ' . $meta['age'] . ' ans. ' . number_format( $meta['price'], 0, ',', ' ' ) . ' €.',
    $main_img
);
echo gh_horse_schema_org( get_the_ID() );

// Breadcrumb
gh_breadcrumb( [
    [ 'Accueil',          home_url( '/' ) ],
    [ 'Caballos en venta', get_post_type_archive_link( 'horse' ) ],
    [ get_the_title(),    get_permalink() ],
] );
?>

<div class="container" style="padding-top:1.5rem;padding-bottom:8rem">
    <div class="horse-single-layout">

        <!-- LEFT col -->
        <div>
            <!-- Main gallery image -->
            <div class="gallery-main">
                <img src="<?= esc_url( $main_img ?: 'https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=1200' ) ?>"
                     alt="<?php the_title_attribute(); ?>"
                     id="gallery-main-img"
                     width="1200" height="675">
                <span class="badge-status <?= esc_attr( $status['class'] ) ?>" style="position:absolute;top:1rem;left:1rem">
                    <?= esc_html( $status['label'] ) ?>
                </span>
            </div>

            <!-- Thumbnails -->
            <?php if ( count( $gallery ) > 1 ) : ?>
            <div class="gallery-thumbs">
                <?php foreach ( $gallery as $i => $att ) :
                    $thumb = wp_get_attachment_image_url( $att->ID, 'horse-thumb' );
                    $large = wp_get_attachment_image_url( $att->ID, 'horse-hero' );
                ?>
                    <div class="gallery-thumb <?= $i === 0 ? 'active' : '' ?>"
                         data-large="<?= esc_url( $large ) ?>"
                         role="button" tabindex="0"
                         aria-label="Photo <?= esc_attr( $i + 1 ) ?>">
                        <img src="<?= esc_url( $thumb ) ?>" alt="" width="180" height="135" loading="lazy">
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- YouTube embed -->
            <?php if ( $meta['youtube_url'] ) :
                $yt_id = '';
                preg_match( '/(?:v=|\/embed\/|youtu\.be\/)([A-Za-z0-9_-]{11})/', $meta['youtube_url'], $m );
                $yt_id = $m[1] ?? '';
                if ( $yt_id ) : ?>
                <div style="margin:1.5rem 0;border-radius:var(--radius);overflow:hidden;aspect-ratio:16/9">
                    <iframe src="https://www.youtube.com/embed/<?= esc_attr( $yt_id ) ?>"
                            style="width:100%;height:100%;border:0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy"
                            title="Vidéo — <?php the_title_attribute(); ?>"></iframe>
                </div>
            <?php endif; endif; ?>

            <!-- Specs grid -->
            <h2 style="font-size:1.5rem;margin:1.75rem 0 1rem">Características</h2>
            <div class="specs-grid">
                <?php
                $specs = [
                    'Raza'       => $meta['breed'],
                    'Edad'       => $meta['age'] ? $meta['age'] . ' ans' : null,
                    'Sexo'       => $meta['gender'] ? gh_gender_label( $meta['gender'] ) : null,
                    'Alzada'     => $meta['height_cm'] ? $meta['height_cm'] . ' cm' : null,
                    'Capa'       => $meta['color'],
                    'Pedigrí'    => $meta['pedigree'],
                    'Disciplina' => ! empty( $disciplines ) ? implode( ', ', $disciplines ) : null,
                    'Ubicación'  => $meta['city'] && $meta['country'] ? $meta['city'] . ', ' . $meta['country'] : $meta['city'],
                ];
                foreach ( $specs as $label => $value ) :
                    if ( ! $value ) continue; ?>
                    <div class="spec-item">
                        <div class="spec-label"><?= esc_html( $label ) ?></div>
                        <div class="spec-value"><?= esc_html( $value ) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Description -->
            <?php if ( get_the_content() ) : ?>
            <h2 style="font-size:1.5rem;margin:1.75rem 0 .75rem">Descripción</h2>
            <div style="font-size:13.5px;color:var(--sub);line-height:1.75">
                <?php the_content(); ?>
            </div>
            <?php endif; ?>

            <!-- GMC info block -->
            <div class="gmc-info" style="margin-top:1.5rem">
                <div class="gmc-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    Entrega: 5–15 días hábiles
                </div>
                <div class="gmc-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                    Paiement : virement SEPA ou carte via Stripe
                </div>
                <div class="gmc-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                    <a href="<?php echo esc_url( home_url( '/politique-de-retour/' ) ); ?>">Política de devolución</a>
                </div>
                <?php if ( $meta['country'] ) : ?>
                <div class="gmc-row">
                    <img src="https://flagcdn.com/w20/<?= esc_attr( $flag_code ) ?>.png" width="16" height="12" alt="<?= esc_attr( $meta['country'] ) ?>">
                    Ubicación: <?= esc_html( $meta['city'] ? $meta['city'] . ', ' . $meta['country'] : $meta['country'] ) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT sidebar -->
        <aside class="horse-sidebar">
            <!-- Price card -->
            <div class="price-card">
                <h1><?php the_title(); ?></h1>
                <div class="price-card-meta">
                    <?php if ( $meta['age'] ) : ?>
                        <span><?= esc_html( $meta['age'] ) ?> ans</span>
                    <?php endif; ?>
                    <?php if ( $meta['city'] ) : ?>
                        <span><?= esc_html( $meta['city'] ) ?></span>
                    <?php endif; ?>
                    <?php if ( $meta['country'] ) : ?>
                        <span>
                            <img src="https://flagcdn.com/w20/<?= esc_attr( $flag_code ) ?>.png" width="16" height="12" alt="<?= esc_attr( $meta['country'] ) ?>" style="display:inline;vertical-align:middle">
                            <?= esc_html( $meta['country'] ) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div style="margin-bottom:1rem">
                    <?php if ( $meta['price'] ) : ?>
                        <span class="price-value"><?= esc_html( number_format( $meta['price'], 0, ',', ' ' ) ) ?> €</span>
                        <?php if ( $meta['negotiable'] ) : ?>
                            <span class="price-neg"> — Prix négociable</span>
                        <?php endif; ?>
                    <?php else : ?>
                        <span style="color:var(--sub);font-size:15px">Prix sur demande</span>
                    <?php endif; ?>
                </div>

                <a href="https://wa.me/<?= esc_attr( $wa_num ) ?>?text=<?= esc_attr( $wa_msg ) ?>"
                   target="_blank" rel="noopener noreferrer" class="btn-wa-full">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
                <button class="btn-share" onclick="navigator.share?.({title:'<?php the_title_attribute(); ?>',url:window.location.href})">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                    Partager
                </button>
            </div>

            <!-- Contact form -->
            <div class="contact-card">
                <h3>Contacter le vendeur</h3>
                <form class="gh-contact-form" novalidate>
                    <input type="hidden" name="horse_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
                    <div class="form-field">
                        <input type="text" name="name" placeholder="Votre nom *" required>
                    </div>
                    <div class="form-field">
                        <input type="email" name="email" placeholder="Email *" required>
                    </div>
                    <div class="form-field">
                        <input type="tel" name="phone" placeholder="Téléphone">
                    </div>
                    <div class="form-field">
                        <textarea name="message" rows="4" placeholder="Message *" required>Me interesa el caballo <?php the_title(); ?>. ¿Podría darme más información?</textarea>
                    </div>
                    <button type="submit" class="btn-submit">Envoyer le message</button>
                </form>
            </div>
        </aside>

    </div><!-- /.horse-single-layout -->

    <!-- Similar horses -->
    <?php
    $similar = new WP_Query( [
        'post_type'      => 'horse',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
        'post__not_in'   => [ get_the_ID() ],
        'meta_query'     => [ [ 'key' => 'gh_status', 'value' => 'available' ] ],
        'orderby'        => 'rand',
    ] );
    if ( $similar->have_posts() ) : ?>
    <div style="margin-top:4rem">
        <h2 style="font-size:2rem;margin-bottom:2rem">Caballos similares</h2>
        <div class="grid-3">
            <?php while ( $similar->have_posts() ) { $similar->the_post(); get_template_part( 'template-parts/horse-card' ); } ?>
        </div>
    </div>
    <?php wp_reset_postdata(); endif; ?>

</div>

<?php get_footer(); ?>
