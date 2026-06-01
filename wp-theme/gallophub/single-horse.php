<?php
/**
 * Single horse detail page — GallopHub
 */
defined( 'ABSPATH' ) || exit;

get_header();

if ( ! have_posts() ) { get_footer(); return; }
the_post();

$post_id     = get_the_ID();
$meta        = gh_get_horse_meta( $post_id );
$status      = gh_status_info( $meta['status'] );
$flag_code   = gh_country_to_flag( $meta['country'] );
$disciplines = wp_get_post_terms( $post_id, 'discipline', [ 'fields' => 'names' ] );
$wa_num      = get_option( 'gh_whatsapp_number', '34600000000' );
$wa_msg      = rawurlencode( 'Bonjour, je suis intéressé(e) par le cheval « ' . get_the_title() . ' » — ' . get_permalink() );

/* ── Gallery ── */
$main_img = get_the_post_thumbnail_url( $post_id, 'horse-hero' );
$gallery  = get_posts( [
    'post_type'      => 'attachment',
    'post_status'    => 'inherit',
    'post_parent'    => $post_id,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'posts_per_page' => 12,
] );

/* ── SEO + Schema ── */
gh_seo_meta(
    get_the_title() . ( $meta['breed'] ? ' — ' . $meta['breed'] : '' ),
    get_the_excerpt() ?: sprintf(
        '%s, %s, %d ans. %s €.',
        get_the_title(),
        $meta['breed'],
        (int) $meta['age'],
        number_format( (float) $meta['price'], 0, ',', ' ' )
    ),
    $main_img ?: ''
);
echo gh_horse_schema_org( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput
?>

<div class="container horse-single-wrap">
    <div class="horse-single-layout">

        <!-- ── LEFT column ── -->
        <div class="horse-single-left">

            <!-- Main gallery image -->
            <div class="gallery-main" style="position:relative">
                <img src="<?php echo esc_url( $main_img ?: get_template_directory_uri() . '/assets/img/og-default.jpg' ); ?>"
                     alt="<?php the_title_attribute(); ?>"
                     id="gallery-main-img"
                     width="900" height="600"
                     style="width:100%;height:auto;border-radius:var(--radius);display:block">
                <span class="badge-status <?php echo esc_attr( $status['class'] ); ?>"
                      style="position:absolute;top:1rem;left:1rem">
                    <?php echo esc_html( $status['label'] ); ?>
                </span>
                <?php if ( $meta['country'] && $flag_code ) : ?>
                <img src="https://flagcdn.com/w40/<?php echo esc_attr( $flag_code ); ?>.png"
                     width="28" height="21"
                     alt="<?php echo esc_attr( $meta['country'] ); ?>"
                     style="position:absolute;top:1rem;right:1rem;border-radius:3px;box-shadow:0 1px 4px rgba(0,0,0,.3)">
                <?php endif; ?>
            </div>

            <!-- Thumbnails -->
            <?php if ( count( $gallery ) > 1 ) : ?>
            <div class="gallery-thumbs" role="list" aria-label="<?php esc_attr_e( 'Photos du cheval', 'gallophub' ); ?>">
                <?php foreach ( $gallery as $i => $att ) :
                    $thumb_url = wp_get_attachment_image_url( $att->ID, 'horse-thumb' );
                    $hero_url  = wp_get_attachment_image_url( $att->ID, 'horse-hero' );
                    if ( ! $thumb_url ) continue;
                ?>
                <div class="gallery-thumb <?php echo $i === 0 ? 'active' : ''; ?>"
                     data-large="<?php echo esc_url( $hero_url ); ?>"
                     role="button" tabindex="0"
                     aria-label="<?php echo esc_attr( sprintf( __( 'Photo %d', 'gallophub' ), $i + 1 ) ); ?>">
                    <img src="<?php echo esc_url( $thumb_url ); ?>"
                         alt=""
                         width="160" height="107"
                         loading="lazy">
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- YouTube embed -->
            <?php if ( $meta['youtube_url'] ) :
                preg_match( '/(?:v=|\/embed\/|youtu\.be\/)([A-Za-z0-9_-]{11})/', $meta['youtube_url'], $m );
                $yt_id = $m[1] ?? '';
                if ( $yt_id ) : ?>
            <div style="margin:1.5rem 0;border-radius:var(--radius);overflow:hidden;aspect-ratio:16/9">
                <iframe src="https://www.youtube-nocookie.com/embed/<?php echo esc_attr( $yt_id ); ?>"
                        style="width:100%;height:100%;border:0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        loading="lazy"
                        title="<?php echo esc_attr( get_the_title() ); ?> — vidéo"></iframe>
            </div>
            <?php endif; endif; ?>

            <!-- ── Specs grid ── -->
            <h2 class="section-title" style="margin-top:2rem"><?php esc_html_e( 'Características', 'gallophub' ); ?></h2>
            <div class="specs-grid">
                <?php
                $gender_label  = $meta['gender']       ? gh_gender_label( $meta['gender'] ) : '';
                $level_label   = $meta['level_dressage'] ? gh_level_label( $meta['level_dressage'] ) : '';
                $location      = array_filter( [ $meta['city'], $meta['country'] ] );
                $specs = [
                    __( 'Race',        'gallophub' ) => $meta['breed'],
                    __( 'Âge',         'gallophub' ) => $meta['age'] ? $meta['age'] . ' ' . __( 'ans', 'gallophub' ) : '',
                    __( 'Sexe',        'gallophub' ) => $gender_label,
                    __( 'Taille',      'gallophub' ) => $meta['height_cm'] ? $meta['height_cm'] . ' cm' : '',
                    __( 'Robe',        'gallophub' ) => $meta['color'],
                    __( 'Pedigree',    'gallophub' ) => $meta['pedigree'],
                    __( 'Discipline',  'gallophub' ) => ! empty( $disciplines ) ? implode( ', ', (array) $disciplines ) : '',
                    __( 'Niveau',      'gallophub' ) => $level_label,
                    __( 'Passeport',   'gallophub' ) => $meta['passport_number'],
                    __( 'Localisation','gallophub' ) => implode( ', ', $location ),
                ];
                foreach ( $specs as $label => $value ) :
                    if ( ! $value ) continue; ?>
                <div class="spec-item">
                    <div class="spec-label"><?php echo esc_html( $label ); ?></div>
                    <div class="spec-value"><?php echo esc_html( $value ); ?></div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ── Description ── -->
            <?php if ( get_the_content() ) : ?>
            <h2 class="section-title" style="margin-top:2rem"><?php esc_html_e( 'Description', 'gallophub' ); ?></h2>
            <div class="horse-description">
                <?php the_content(); ?>
            </div>
            <?php endif; ?>

            <!-- ── Discipline tags ── -->
            <?php if ( ! empty( $disciplines ) ) : ?>
            <div style="margin-top:1rem;display:flex;flex-wrap:wrap;gap:.4rem">
                <?php foreach ( (array) $disciplines as $disc ) : ?>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'horse' ) . '?disciplines[]=' . sanitize_title( $disc ) ); ?>"
                   class="discipline-pill">
                    <?php echo esc_html( $disc ); ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- ── Shipping / trust block ── -->
            <div class="gmc-info" style="margin-top:2rem">
                <div class="gmc-row">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                    <?php esc_html_e( 'Livraison Europe : 5–15 jours ouvrés', 'gallophub' ); ?>
                </div>
                <div class="gmc-row">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                    <?php esc_html_e( 'Paiement : virement SEPA · Stripe · PayPal', 'gallophub' ); ?>
                </div>
                <div class="gmc-row">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                    </svg>
                    <a href="<?php echo esc_url( home_url( '/retours/' ) ); ?>">
                        <?php esc_html_e( 'Politique de retour', 'gallophub' ); ?>
                    </a>
                </div>
            </div>

        </div><!-- /.horse-single-left -->

        <!-- ── RIGHT sidebar ── -->
        <aside class="horse-sidebar">

            <!-- Price card -->
            <div class="price-card">
                <h1 class="price-card-title"><?php the_title(); ?></h1>

                <div class="price-card-meta">
                    <?php if ( $meta['breed'] ) : ?>
                    <span><?php echo esc_html( $meta['breed'] ); ?></span>
                    <?php endif; ?>
                    <?php if ( $meta['age'] ) : ?>
                    <span><?php echo esc_html( $meta['age'] . ' ' . __( 'ans', 'gallophub' ) ); ?></span>
                    <?php endif; ?>
                    <?php if ( $meta['country'] && $flag_code ) : ?>
                    <span>
                        <img src="https://flagcdn.com/w20/<?php echo esc_attr( $flag_code ); ?>.png"
                             width="16" height="12"
                             alt="<?php echo esc_attr( $meta['country'] ); ?>"
                             style="display:inline;vertical-align:middle">
                        <?php echo esc_html( $meta['city'] ? $meta['city'] . ', ' . $meta['country'] : $meta['country'] ); ?>
                    </span>
                    <?php endif; ?>
                </div>

                <div class="price-card-price">
                    <?php if ( $meta['price'] > 0 ) : ?>
                        <span class="price-value"><?php echo esc_html( number_format( (float) $meta['price'], 0, ',', ' ' ) ); ?> €</span>
                        <?php if ( $meta['negotiable'] ) : ?>
                        <span class="price-neg"><?php esc_html_e( '— Négociable', 'gallophub' ); ?></span>
                        <?php endif; ?>
                    <?php else : ?>
                        <span class="price-on-request"><?php esc_html_e( 'Prix sur demande', 'gallophub' ); ?></span>
                    <?php endif; ?>
                </div>

                <span class="badge-status <?php echo esc_attr( $status['class'] ); ?>" style="display:inline-block;margin-bottom:1rem">
                    <?php echo esc_html( $status['label'] ); ?>
                </span>

                <a href="https://wa.me/<?php echo esc_attr( $wa_num ); ?>?text=<?php echo $wa_msg; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn-wa-full">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    <?php esc_html_e( 'Contacter via WhatsApp', 'gallophub' ); ?>
                </a>

                <button class="btn-share"
                        onclick="navigator.share ? navigator.share({title:'<?php the_title_attribute(); ?>',url:location.href}) : navigator.clipboard.writeText(location.href)"
                        aria-label="<?php esc_attr_e( 'Partager', 'gallophub' ); ?>">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                    </svg>
                    <?php esc_html_e( 'Partager', 'gallophub' ); ?>
                </button>
            </div>

            <!-- Contact form -->
            <div class="contact-card">
                <h3><?php esc_html_e( 'Envoyer un message', 'gallophub' ); ?></h3>
                <form class="gh-contact-form" novalidate aria-label="<?php esc_attr_e( 'Formulaire de contact', 'gallophub' ); ?>">
                    <?php wp_nonce_field( 'gh_ajax', 'nonce' ); ?>
                    <input type="hidden" name="horse_id" value="<?php echo esc_attr( $post_id ); ?>">
                    <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off" aria-hidden="true">

                    <div class="form-field">
                        <label for="cf-name" class="sr-only"><?php esc_html_e( 'Nom', 'gallophub' ); ?></label>
                        <input type="text" id="cf-name" name="name" placeholder="<?php esc_attr_e( 'Votre nom *', 'gallophub' ); ?>" required minlength="2" autocomplete="name">
                    </div>
                    <div class="form-field">
                        <label for="cf-email" class="sr-only"><?php esc_html_e( 'Email', 'gallophub' ); ?></label>
                        <input type="email" id="cf-email" name="email" placeholder="<?php esc_attr_e( 'Email *', 'gallophub' ); ?>" required autocomplete="email">
                    </div>
                    <div class="form-field">
                        <label for="cf-phone" class="sr-only"><?php esc_html_e( 'Téléphone', 'gallophub' ); ?></label>
                        <input type="tel" id="cf-phone" name="phone" placeholder="<?php esc_attr_e( 'Téléphone', 'gallophub' ); ?>" autocomplete="tel">
                    </div>
                    <div class="form-field">
                        <label for="cf-message" class="sr-only"><?php esc_html_e( 'Message', 'gallophub' ); ?></label>
                        <textarea id="cf-message" name="message" rows="4"
                                  placeholder="<?php esc_attr_e( 'Message *', 'gallophub' ); ?>"
                                  required minlength="10"><?php echo esc_textarea( sprintf( __( 'Bonjour, je suis intéressé(e) par le cheval %s. Pouvez-vous me donner plus d\'informations ?', 'gallophub' ), get_the_title() ) ); ?></textarea>
                    </div>
                    <div class="form-field form-status" role="alert" aria-live="polite"></div>
                    <button type="submit" class="btn-submit">
                        <?php esc_html_e( 'Envoyer le message', 'gallophub' ); ?>
                    </button>
                </form>
            </div>

        </aside><!-- /.horse-sidebar -->

    </div><!-- /.horse-single-layout -->

    <!-- ── Similar horses ── -->
    <?php
    $tax_query_similar = [];
    if ( ! empty( $disciplines ) ) {
        $tax_query_similar = [ [
            'taxonomy' => 'discipline',
            'field'    => 'name',
            'terms'    => (array) $disciplines,
        ] ];
    }
    $similar = new WP_Query( [
        'post_type'      => 'horse',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
        'post__not_in'   => [ $post_id ],
        'meta_query'     => [ [ 'key' => 'gh_status', 'value' => 'available' ] ],
        'tax_query'      => $tax_query_similar,
        'orderby'        => 'rand',
    ] );
    if ( $similar->have_posts() ) : ?>
    <section class="similar-section" aria-labelledby="similar-heading">
        <h2 id="similar-heading"><?php esc_html_e( 'Chevaux similaires', 'gallophub' ); ?></h2>
        <div class="horses-grid">
            <?php while ( $similar->have_posts() ) {
                $similar->the_post();
                get_template_part( 'template-parts/horse-card' );
            } ?>
        </div>
    </section>
    <?php
    wp_reset_postdata();
    endif;
    ?>

</div><!-- /.container -->

<?php get_footer(); ?>
