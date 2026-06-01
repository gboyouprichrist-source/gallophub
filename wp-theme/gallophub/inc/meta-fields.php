<?php
/**
 * GallopHub — Horse meta fields (admin metaboxes + helpers)
 *
 * @package GallopHub
 */

defined( 'ABSPATH' ) || exit;

/* ═══════════════════════════════════════════════════════════
   METABOXES REGISTRATION
═══════════════════════════════════════════════════════════ */
add_action( 'add_meta_boxes', 'gh_horse_meta_boxes' );

function gh_horse_meta_boxes(): void {
    add_meta_box(
        'gh_horse_details',
        __( 'Détails du cheval', 'gallophub' ),
        'gh_horse_details_callback',
        'horse',
        'normal',
        'high'
    );
    add_meta_box(
        'gh_horse_media',
        __( 'Médias & vidéo', 'gallophub' ),
        'gh_horse_media_callback',
        'horse',
        'normal',
        'default'
    );
    add_meta_box(
        'gh_horse_status',
        __( 'Statut & Prix', 'gallophub' ),
        'gh_horse_status_callback',
        'horse',
        'side',
        'high'
    );
    add_meta_box(
        'gh_horse_wc',
        __( 'WooCommerce', 'gallophub' ),
        'gh_horse_wc_callback',
        'horse',
        'side',
        'default'
    );
}

/* ═══════════════════════════════════════════════════════════
   METABOX CALLBACKS
═══════════════════════════════════════════════════════════ */
function gh_horse_details_callback( WP_Post $post ): void {
    wp_nonce_field( 'gh_horse_save', 'gh_horse_nonce' );
    $m = gh_get_horse_meta( $post->ID );
    ?>
    <style>
        .gh-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .gh-field { display: flex; flex-direction: column; gap: 4px; }
        .gh-field.full { grid-column: 1 / -1; }
        .gh-field label { font-weight: 600; font-size: 12px; color: #444; }
        .gh-field input, .gh-field select, .gh-field textarea {
            border: 1px solid #ddd; border-radius: 4px; padding: 6px 9px;
            font-size: 13px; width: 100%;
        }
        .gh-field textarea { resize: vertical; min-height: 80px; }
        .gh-section { margin: 1.25rem 0 .5rem; font-weight: 700; font-size: 12px;
                      text-transform: uppercase; letter-spacing: .06em; color: #1A3C5E;
                      border-bottom: 2px solid #C8A951; padding-bottom: 4px; }
    </style>

    <div class="gh-grid">
        <p class="gh-section" style="grid-column:1/-1"><?php esc_html_e( 'Identité', 'gallophub' ); ?></p>

        <?php gh_field_text( 'gh_breed', __( 'Race', 'gallophub' ), $m['breed'] ); ?>
        <?php gh_field_select( 'gh_gender', __( 'Sexe', 'gallophub' ), $m['gender'], [
            ''         => '— ' . __( 'Choisir', 'gallophub' ) . ' —',
            'mare'     => __( 'Jument', 'gallophub' ),
            'stallion' => __( 'Étalon', 'gallophub' ),
            'gelding'  => __( 'Hongre', 'gallophub' ),
            'pony'     => __( 'Poney', 'gallophub' ),
        ] ); ?>
        <?php gh_field_number( 'gh_age',       __( 'Âge', 'gallophub' ),       $m['age'],       0, 40 ); ?>
        <?php gh_field_number( 'gh_height_cm', __( 'Taille (cm)', 'gallophub' ), $m['height_cm'], 80, 200 ); ?>
        <?php gh_field_text(   'gh_color',     __( 'Robe', 'gallophub' ),       $m['color'] ); ?>
        <?php gh_field_text(   'gh_pedigree',  __( 'Pedigree', 'gallophub' ),   $m['pedigree'], 'full' ); ?>

        <p class="gh-section" style="grid-column:1/-1"><?php esc_html_e( 'Aptitudes', 'gallophub' ); ?></p>

        <?php gh_field_select( 'gh_level_dressage', __( 'Niveau dressage', 'gallophub' ), $m['level_dressage'], [
            ''          => '—',
            'debutant'  => __( 'Débutant', 'gallophub' ),
            'amateur'   => __( 'Amateur', 'gallophub' ),
            'club'      => __( 'Club', 'gallophub' ),
            'regional'  => __( 'Régional', 'gallophub' ),
            'national'  => __( 'National', 'gallophub' ),
            'international' => __( 'International', 'gallophub' ),
        ] ); ?>

        <p class="gh-section" style="grid-column:1/-1"><?php esc_html_e( 'Localisation', 'gallophub' ); ?></p>

        <?php gh_field_text( 'gh_city', __( 'Ville', 'gallophub' ), $m['city'] ); ?>
        <?php gh_field_select( 'gh_country', __( 'Pays', 'gallophub' ), $m['country'], [
            ''            => '— ' . __( 'Choisir', 'gallophub' ) . ' —',
            'España'      => 'Espagne',
            'Nederland'   => 'Pays-Bas',
            'België'      => 'Belgique',
            'France'      => 'France',
            'Deutschland' => 'Allemagne',
            'Portugal'    => 'Portugal',
            'Ireland'     => 'Irlande',
            'United Kingdom' => 'Royaume-Uni',
        ] ); ?>

        <p class="gh-section" style="grid-column:1/-1"><?php esc_html_e( 'Documents', 'gallophub' ); ?></p>

        <?php gh_field_text( 'gh_passport_number', __( 'Numéro de passeport', 'gallophub' ), $m['passport_number'], 'full' ); ?>
    </div>
    <?php
}

function gh_horse_media_callback( WP_Post $post ): void {
    $m = gh_get_horse_meta( $post->ID );
    ?>
    <div class="gh-grid" style="grid-template-columns:1fr 1fr">
        <?php gh_field_url(  'gh_youtube_url', __( 'URL YouTube (vidéo principale)', 'gallophub' ), $m['youtube_url'], 'full' ); ?>
    </div>
    <p style="color:#666;font-size:12px;margin-top:.5rem">
        <?php esc_html_e( 'Pour la galerie photos supplémentaires, uploadez des médias attachés à ce billet (Bibliothèque → Ajouter).', 'gallophub' ); ?>
    </p>
    <?php
}

function gh_horse_status_callback( WP_Post $post ): void {
    $m = gh_get_horse_meta( $post->ID );
    ?>
    <div style="display:flex;flex-direction:column;gap:1rem;padding:.25rem 0">
        <?php gh_field_select( 'gh_status', __( 'Statut', 'gallophub' ), $m['status'], [
            'available' => __( 'Disponible', 'gallophub' ),
            'reserved'  => __( 'Réservé', 'gallophub' ),
            'sold'      => __( 'Vendu', 'gallophub' ),
        ] ); ?>
        <?php gh_field_number( 'gh_price', __( 'Prix (€)', 'gallophub' ), $m['price'], 0 ); ?>
        <div class="gh-field">
            <label style="display:flex;align-items:center;gap:.5rem;font-weight:600;font-size:12px;color:#444">
                <input type="checkbox" name="gh_negotiable" value="1" <?= checked( $m['negotiable'], true, false ) ?>>
                <?php esc_html_e( 'Prix négociable', 'gallophub' ); ?>
            </label>
        </div>
    </div>
    <?php
}

function gh_horse_wc_callback( WP_Post $post ): void {
    $wc_id = (int) get_post_meta( $post->ID, 'gh_wc_product_id', true );
    if ( $wc_id && class_exists( 'WooCommerce' ) ) {
        $product = wc_get_product( $wc_id );
        if ( $product ) {
            echo '<p style="font-size:13px">';
            printf(
                esc_html__( 'Produit WooCommerce lié : %s', 'gallophub' ),
                '<a href="' . esc_url( get_edit_post_link( $wc_id ) ) . '">#' . esc_html( $wc_id ) . ' — ' . esc_html( $product->get_name() ) . '</a>'
            );
            echo '</p>';
        }
    } else {
        echo '<p style="font-size:12px;color:#666">' . esc_html__( 'Aucun produit WooCommerce lié. Sera créé automatiquement à la publication.', 'gallophub' ) . '</p>';
    }
}

/* ═══════════════════════════════════════════════════════════
   SAVE META
═══════════════════════════════════════════════════════════ */
add_action( 'save_post_horse', 'gh_save_horse_meta' );

function gh_save_horse_meta( int $post_id ): void {
    if ( ! isset( $_POST['gh_horse_nonce'] ) || ! wp_verify_nonce( $_POST['gh_horse_nonce'], 'gh_horse_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $text_fields = [
        'gh_breed', 'gh_age', 'gh_height_cm', 'gh_color',
        'gh_price', 'gh_city', 'gh_pedigree', 'gh_youtube_url',
        'gh_passport_number',
    ];
    $key_fields = [ 'gh_gender', 'gh_country', 'gh_status', 'gh_level_dressage' ];

    foreach ( $text_fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
    foreach ( $key_fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_key( $_POST[ $field ] ) );
        }
    }

    update_post_meta( $post_id, 'gh_negotiable', ! empty( $_POST['gh_negotiable'] ) ? '1' : '0' );
}

/* ═══════════════════════════════════════════════════════════
   FIELD HELPERS
═══════════════════════════════════════════════════════════ */
function gh_field_text( string $key, string $label, string $value = '', string $extra_class = '' ): void {
    echo '<div class="gh-field ' . esc_attr( $extra_class ) . '">';
    echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
    echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
    echo '</div>';
}

function gh_field_url( string $key, string $label, string $value = '', string $extra_class = '' ): void {
    echo '<div class="gh-field ' . esc_attr( $extra_class ) . '">';
    echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
    echo '<input type="url" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
    echo '</div>';
}

function gh_field_number( string $key, string $label, mixed $value, int $min = 0, int $max = 99999 ): void {
    echo '<div class="gh-field">';
    echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
    echo '<input type="number" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( (string) $value ) . '" min="' . esc_attr( (string) $min ) . '" max="' . esc_attr( (string) $max ) . '">';
    echo '</div>';
}

function gh_field_select( string $key, string $label, string $value, array $options ): void {
    echo '<div class="gh-field">';
    echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
    echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">';
    foreach ( $options as $opt_val => $opt_label ) {
        echo '<option value="' . esc_attr( $opt_val ) . '" ' . selected( $value, $opt_val, false ) . '>' . esc_html( $opt_label ) . '</option>';
    }
    echo '</select></div>';
}

/* ═══════════════════════════════════════════════════════════
   DATA HELPERS
═══════════════════════════════════════════════════════════ */

/**
 * Get all horse meta as a typed array.
 */
function gh_get_horse_meta( int $post_id ): array {
    return [
        'breed'           => (string) get_post_meta( $post_id, 'gh_breed', true ),
        'age'             => (int)    get_post_meta( $post_id, 'gh_age', true ),
        'gender'          => (string) get_post_meta( $post_id, 'gh_gender', true ),
        'height_cm'       => (int)    get_post_meta( $post_id, 'gh_height_cm', true ),
        'color'           => (string) get_post_meta( $post_id, 'gh_color', true ),
        'price'           => (float)  get_post_meta( $post_id, 'gh_price', true ),
        'negotiable'      => get_post_meta( $post_id, 'gh_negotiable', true ) === '1',
        'city'            => (string) get_post_meta( $post_id, 'gh_city', true ),
        'country'         => (string) get_post_meta( $post_id, 'gh_country', true ),
        'pedigree'        => (string) get_post_meta( $post_id, 'gh_pedigree', true ),
        'youtube_url'     => (string) get_post_meta( $post_id, 'gh_youtube_url', true ),
        'status'          => get_post_meta( $post_id, 'gh_status', true ) ?: 'available',
        'level_dressage'  => (string) get_post_meta( $post_id, 'gh_level_dressage', true ),
        'passport_number' => (string) get_post_meta( $post_id, 'gh_passport_number', true ),
    ];
}

/**
 * Country name → ISO 3166-1 alpha-2 flag code.
 */
function gh_country_to_flag( string $country ): string {
    static $map = [
        'España'         => 'es', 'Spain'          => 'es',
        'Nederland'      => 'nl', 'Netherlands'    => 'nl',
        'België'         => 'be', 'Belgium'        => 'be', 'Belgique'    => 'be',
        'France'         => 'fr', 'Francia'        => 'fr',
        'Deutschland'    => 'de', 'Germany'        => 'de',
        'Portugal'       => 'pt',
        'Ireland'        => 'ie',
        'United Kingdom' => 'gb',
    ];
    return $map[ $country ] ?? 'es';
}

/**
 * Gender key → translated label.
 */
function gh_gender_label( string $gender ): string {
    $labels = [
        'mare'     => __( 'Jument', 'gallophub' ),
        'stallion' => __( 'Étalon', 'gallophub' ),
        'gelding'  => __( 'Hongre', 'gallophub' ),
        'pony'     => __( 'Poney', 'gallophub' ),
    ];
    return $labels[ $gender ] ?? $gender;
}

/**
 * Status key → [ label, CSS class ].
 */
function gh_status_info( string $status ): array {
    $map = [
        'available' => [ 'label' => __( 'Disponible', 'gallophub' ), 'class' => 'badge-available' ],
        'reserved'  => [ 'label' => __( 'Réservé',    'gallophub' ), 'class' => 'badge-reserved' ],
        'sold'      => [ 'label' => __( 'Vendu',      'gallophub' ), 'class' => 'badge-sold' ],
    ];
    return $map[ $status ] ?? $map['available'];
}

/**
 * Dressage level key → translated label.
 */
function gh_level_label( string $level ): string {
    $labels = [
        'debutant'      => __( 'Débutant', 'gallophub' ),
        'amateur'       => __( 'Amateur', 'gallophub' ),
        'club'          => __( 'Club', 'gallophub' ),
        'regional'      => __( 'Régional', 'gallophub' ),
        'national'      => __( 'National', 'gallophub' ),
        'international' => __( 'International', 'gallophub' ),
    ];
    return $labels[ $level ] ?? $level;
}
