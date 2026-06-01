<?php
/**
 * Template part: horse-card
 * Used in: archive-horse.php, AJAX filter handler, front-page.php
 */

$meta       = gh_get_horse_meta( get_the_ID() );
$status     = gh_status_info( $meta['status'] );
$flag_code  = gh_country_to_flag( $meta['country'] );
$thumb_url  = get_the_post_thumbnail_url( get_the_ID(), 'horse-card' )
              ?: 'https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=600';
$disciplines = wp_get_post_terms( get_the_ID(), 'discipline', [ 'fields' => 'names' ] );
?>
<article class="horse-card">
    <a href="<?php the_permalink(); ?>" class="horse-card-img" aria-hidden="true" tabindex="-1">
        <img src="<?= esc_url( $thumb_url ) ?>"
             alt="<?php the_title_attribute(); ?>"
             loading="lazy"
             width="600" height="450">
        <span class="badge-status <?= esc_attr( $status['class'] ) ?>"><?= esc_html( $status['label'] ) ?></span>
        <?php if ( $meta['country'] ) : ?>
            <img class="horse-card-flag"
                 src="https://flagcdn.com/w20/<?= esc_attr( $flag_code ) ?>.png"
                 width="20" height="15"
                 alt="<?= esc_attr( $meta['country'] ) ?>">
        <?php endif; ?>
    </a>

    <div class="horse-card-body">
        <a href="<?php the_permalink(); ?>">
            <h3 class="horse-card-title"><?php the_title(); ?></h3>
        </a>

        <p class="horse-card-breed">
            <?= esc_html( $meta['breed'] ) ?>
            <?php if ( $meta['gender'] ) : ?>
                · <?= esc_html( gh_gender_label( $meta['gender'] ) ) ?>
            <?php endif; ?>
        </p>

        <?php if ( ! empty( $disciplines ) ) : ?>
            <div class="horse-card-disciplines">
                <?php foreach ( array_slice( $disciplines, 0, 3 ) as $disc ) : ?>
                    <span class="disc-pill"><?= esc_html( $disc ) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="horse-card-meta">
            <?php if ( $meta['age'] ) : ?>
                <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <?= esc_html( $meta['age'] ) ?> <?php esc_html_e( 'ans', 'gallophub' ); ?>
                </span>
            <?php endif; ?>
            <?php if ( $meta['height_cm'] ) : ?>
                <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 3v18M5 8h3M5 12h3M5 16h3M16 8h3M16 12h3M16 16h3"/></svg>
                    <?= esc_html( $meta['height_cm'] ) ?> cm
                </span>
            <?php endif; ?>
            <?php if ( $meta['city'] ) : ?>
                <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    <?= esc_html( $meta['city'] ) ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="horse-card-price">
            <?php if ( $meta['price'] ) : ?>
                <?= esc_html( number_format( $meta['price'], 0, ',', ' ' ) ) ?> €
                <?php if ( $meta['negotiable'] ) : ?>
                    <span class="neg"><?php esc_html_e( 'nég.', 'gallophub' ); ?></span>
                <?php endif; ?>
            <?php else : ?>
                <span style="font-size:14px;color:var(--sub)"><?php esc_html_e( 'Sur demande', 'gallophub' ); ?></span>
            <?php endif; ?>
        </div>
    </div>
</article>
