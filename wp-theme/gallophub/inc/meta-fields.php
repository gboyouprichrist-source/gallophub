<?php
/**
 * Horse custom meta fields (admin metabox)
 */

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
}

function gh_horse_details_callback( WP_Post $post ): void {
    wp_nonce_field( 'gh_horse_save', 'gh_horse_nonce' );

    $fields = [
        'gh_breed'       => [ 'label' => 'Race', 'type' => 'text' ],
        'gh_age'         => [ 'label' => 'Âge', 'type' => 'number', 'min' => 0, 'max' => 40 ],
        'gh_gender'      => [ 'label' => 'Sexe', 'type' => 'select', 'options' => [
            '' => '— Choisir —', 'mare' => 'Jument', 'stallion' => 'Étalon',
            'gelding' => 'Hongre', 'pony' => 'Poney',
        ]],
        'gh_height_cm'   => [ 'label' => 'Taille (cm)', 'type' => 'number', 'min' => 80, 'max' => 200 ],
        'gh_color'       => [ 'label' => 'Robe', 'type' => 'text' ],
        'gh_price'       => [ 'label' => 'Prix (€)', 'type' => 'number', 'min' => 0 ],
        'gh_negotiable'  => [ 'label' => 'Prix négociable', 'type' => 'checkbox' ],
        'gh_city'        => [ 'label' => 'Ville', 'type' => 'text' ],
        'gh_country'     => [ 'label' => 'Pays', 'type' => 'select', 'options' => [
            '' => '— Choisir —', 'España' => 'Espagne', 'Nederland' => 'Pays-Bas',
            'België' => 'Belgique', 'France' => 'France', 'Deutschland' => 'Allemagne', 'Portugal' => 'Portugal',
        ]],
        'gh_pedigree'    => [ 'label' => 'Pedigree', 'type' => 'text' ],
        'gh_youtube_url' => [ 'label' => 'URL YouTube', 'type' => 'url' ],
        'gh_status'      => [ 'label' => 'Statut', 'type' => 'select', 'options' => [
            'available' => 'Disponible', 'reserved' => 'Réservé', 'sold' => 'Vendu',
        ]],
    ];
    ?>
    <style>
        .gh-meta-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .gh-meta-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 12px; color: #555; }
        .gh-meta-field input, .gh-meta-field select { width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 4px; }
        .gh-meta-field.full { grid-column: 1 / -1; }
    </style>
    <div class="gh-meta-grid">
    <?php foreach ( $fields as $key => $field ) :
        $value = get_post_meta( $post->ID, $key, true );
        $is_full = in_array( $key, [ 'gh_pedigree', 'gh_youtube_url' ], true );
    ?>
        <div class="gh-meta-field <?= $is_full ? 'full' : '' ?>">
            <label for="<?= esc_attr( $key ) ?>"><?= esc_html( $field['label'] ) ?></label>
            <?php if ( $field['type'] === 'select' ) : ?>
                <select id="<?= esc_attr( $key ) ?>" name="<?= esc_attr( $key ) ?>">
                    <?php foreach ( $field['options'] as $opt_val => $opt_label ) : ?>
                        <option value="<?= esc_attr( $opt_val ) ?>" <?= selected( $value, $opt_val, false ) ?>><?= esc_html( $opt_label ) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ( $field['type'] === 'checkbox' ) : ?>
                <input type="checkbox" id="<?= esc_attr( $key ) ?>" name="<?= esc_attr( $key ) ?>" value="1" <?= checked( $value, '1', false ) ?>>
            <?php else : ?>
                <input type="<?= esc_attr( $field['type'] ) ?>"
                       id="<?= esc_attr( $key ) ?>"
                       name="<?= esc_attr( $key ) ?>"
                       value="<?= esc_attr( $value ) ?>"
                       <?= isset( $field['min'] ) ? 'min="' . esc_attr( $field['min'] ) . '"' : '' ?>
                       <?= isset( $field['max'] ) ? 'max="' . esc_attr( $field['max'] ) . '"' : '' ?>
                >
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>
    <?php
}

add_action( 'save_post_horse', 'gh_save_horse_meta' );

function gh_save_horse_meta( int $post_id ): void {
    if ( ! isset( $_POST['gh_horse_nonce'] ) || ! wp_verify_nonce( $_POST['gh_horse_nonce'], 'gh_horse_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $text_fields = [ 'gh_breed', 'gh_age', 'gh_height_cm', 'gh_color', 'gh_price',
                     'gh_city', 'gh_country', 'gh_pedigree', 'gh_youtube_url' ];
    $select_fields = [ 'gh_gender', 'gh_status' ];

    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
    foreach ( $select_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_key( $_POST[ $field ] ) );
        }
    }

    // Checkbox
    update_post_meta( $post_id, 'gh_negotiable', isset( $_POST['gh_negotiable'] ) ? '1' : '0' );
}

/* ── Helper: get all horse meta ── */
function gh_get_horse_meta( int $post_id ): array {
    return [
        'breed'       => get_post_meta( $post_id, 'gh_breed', true ),
        'age'         => (int) get_post_meta( $post_id, 'gh_age', true ),
        'gender'      => get_post_meta( $post_id, 'gh_gender', true ),
        'height_cm'   => (int) get_post_meta( $post_id, 'gh_height_cm', true ),
        'color'       => get_post_meta( $post_id, 'gh_color', true ),
        'price'       => (float) get_post_meta( $post_id, 'gh_price', true ),
        'negotiable'  => get_post_meta( $post_id, 'gh_negotiable', true ) === '1',
        'city'        => get_post_meta( $post_id, 'gh_city', true ),
        'country'     => get_post_meta( $post_id, 'gh_country', true ),
        'pedigree'    => get_post_meta( $post_id, 'gh_pedigree', true ),
        'youtube_url' => get_post_meta( $post_id, 'gh_youtube_url', true ),
        'status'      => get_post_meta( $post_id, 'gh_status', true ) ?: 'available',
    ];
}

/* ── Country → ISO flag code ── */
function gh_country_to_flag( string $country ): string {
    $map = [
        'España'      => 'es', 'Spain'       => 'es',
        'Nederland'   => 'nl', 'Netherlands' => 'nl',
        'België'      => 'be', 'Belgium'     => 'be', 'Belgique' => 'be',
        'France'      => 'fr', 'Francia'     => 'fr',
        'Deutschland' => 'de', 'Germany'     => 'de',
        'Portugal'    => 'pt',
    ];
    return $map[ $country ] ?? 'es';
}

/* ── Gender label ── */
function gh_gender_label( string $gender ): string {
    $labels = [
        'mare'     => __( 'Jument', 'gallophub' ),
        'stallion' => __( 'Étalon', 'gallophub' ),
        'gelding'  => __( 'Hongre', 'gallophub' ),
        'pony'     => __( 'Poney', 'gallophub' ),
    ];
    return $labels[ $gender ] ?? $gender;
}

/* ── Status label + class ── */
function gh_status_info( string $status ): array {
    $info = [
        'available' => [ 'label' => __( 'Disponible', 'gallophub' ), 'class' => 'badge-available' ],
        'reserved'  => [ 'label' => __( 'Réservé', 'gallophub' ),    'class' => 'badge-reserved' ],
        'sold'      => [ 'label' => __( 'Vendu', 'gallophub' ),      'class' => 'badge-sold' ],
    ];
    return $info[ $status ] ?? $info['available'];
}
