<?php
/**
 * Custom Post Type: horse
 */

add_action( 'init', 'gh_register_horse_cpt' );

function gh_register_horse_cpt(): void {
    register_post_type( 'horse', [
        'labels' => [
            'name'               => __( 'Chevaux', 'gallophub' ),
            'singular_name'      => __( 'Cheval', 'gallophub' ),
            'add_new'            => __( 'Ajouter', 'gallophub' ),
            'add_new_item'       => __( 'Ajouter un cheval', 'gallophub' ),
            'edit_item'          => __( 'Modifier le cheval', 'gallophub' ),
            'all_items'          => __( 'Tous les chevaux', 'gallophub' ),
            'search_items'       => __( 'Rechercher', 'gallophub' ),
            'not_found'          => __( 'Aucun cheval trouvé.', 'gallophub' ),
        ],
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'horses' ],
        'menu_icon'          => 'dashicons-image-filter',
        'menu_position'      => 5,
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'show_in_rest'       => true,
    ] );

    // Discipline taxonomy
    register_taxonomy( 'discipline', 'horse', [
        'labels' => [
            'name'          => __( 'Disciplines', 'gallophub' ),
            'singular_name' => __( 'Discipline', 'gallophub' ),
        ],
        'hierarchical'  => false,
        'public'        => true,
        'rewrite'       => [ 'slug' => 'discipline' ],
        'show_in_rest'  => true,
    ] );
}

/* ── Flush rewrite rules on activation ── */
register_activation_hook( GH_FILE, function () {
    gh_register_horse_cpt();
    flush_rewrite_rules();
} );
