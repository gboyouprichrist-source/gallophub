<?php
/**
 * AJAX handlers
 */

add_action( 'wp_ajax_gh_filter_horses',        'gh_ajax_filter_horses' );
add_action( 'wp_ajax_nopriv_gh_filter_horses', 'gh_ajax_filter_horses' );

function gh_ajax_filter_horses(): void {
    check_ajax_referer( 'gh_ajax', 'nonce' );

    $disciplines    = array_filter( (array) ( $_POST['disciplines'] ?? [] ), 'sanitize_text_field' );
    $breed          = sanitize_text_field( $_POST['breed']         ?? '' );
    $gender         = sanitize_key( $_POST['gender']               ?? '' );
    $country        = sanitize_text_field( $_POST['country']       ?? '' );
    $price_min      = (float) ( $_POST['price_min']                ?? 0 );
    $price_max      = (float) ( $_POST['price_max']                ?? 0 );
    $age_min        = (int)   ( $_POST['age_min']                  ?? 0 );
    $age_max        = (int)   ( $_POST['age_max']                  ?? 0 );
    $available_only = ! empty( $_POST['available_only'] );

    $args = [
        'post_type'      => 'horse',
        'posts_per_page' => 30,
        'post_status'    => 'publish',
        'meta_query'     => [ 'relation' => 'AND' ],
        'tax_query'      => [],
    ];

    if ( $available_only ) {
        $args['meta_query'][] = [ 'key' => 'gh_status', 'value' => 'available' ];
    }
    if ( $gender ) {
        $args['meta_query'][] = [ 'key' => 'gh_gender', 'value' => $gender ];
    }
    if ( $country ) {
        $args['meta_query'][] = [ 'key' => 'gh_country', 'value' => $country ];
    }
    if ( $price_min > 0 ) {
        $args['meta_query'][] = [ 'key' => 'gh_price', 'value' => $price_min, 'compare' => '>=', 'type' => 'NUMERIC' ];
    }
    if ( $price_max > 0 ) {
        $args['meta_query'][] = [ 'key' => 'gh_price', 'value' => $price_max, 'compare' => '<=', 'type' => 'NUMERIC' ];
    }
    if ( $age_min > 0 ) {
        $args['meta_query'][] = [ 'key' => 'gh_age', 'value' => $age_min, 'compare' => '>=', 'type' => 'NUMERIC' ];
    }
    if ( $age_max > 0 ) {
        $args['meta_query'][] = [ 'key' => 'gh_age', 'value' => $age_max, 'compare' => '<=', 'type' => 'NUMERIC' ];
    }
    if ( $breed ) {
        $args['meta_query'][] = [ 'key' => 'gh_breed', 'value' => $breed, 'compare' => 'LIKE' ];
    }
    if ( ! empty( $disciplines ) ) {
        $args['tax_query'][] = [
            'taxonomy' => 'discipline',
            'field'    => 'slug',
            'terms'    => $disciplines,
            'operator' => 'IN',
        ];
    }

    $query = new WP_Query( $args );
    ob_start();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/horse-card' );
        }
        wp_reset_postdata();
    } else {
        echo '<div style="grid-column:1/-1;text-align:center;padding:3rem"><p style="color:var(--sub)">Aucun cheval trouvé avec ces filtres.</p></div>';
    }
    $html = ob_get_clean();

    wp_send_json_success( [ 'html' => $html, 'count' => $query->found_posts ] );
}

/* ── Contact form submission ── */
add_action( 'wp_ajax_gh_submit_contact',        'gh_ajax_submit_contact' );
add_action( 'wp_ajax_nopriv_gh_submit_contact', 'gh_ajax_submit_contact' );

function gh_ajax_submit_contact(): void {
    check_ajax_referer( 'gh_ajax', 'nonce' );

    $name     = sanitize_text_field( $_POST['name']     ?? '' );
    $email    = sanitize_email( $_POST['email']         ?? '' );
    $phone    = sanitize_text_field( $_POST['phone']    ?? '' );
    $subject  = sanitize_text_field( $_POST['subject']  ?? '' );
    $message  = sanitize_textarea_field( $_POST['message'] ?? '' );
    $horse_id = (int) ( $_POST['horse_id'] ?? 0 );

    if ( ! $name || ! is_email( $email ) || strlen( $message ) < 10 ) {
        wp_send_json_error( __( 'Veuillez remplir tous les champs obligatoires.', 'gallophub' ) );
    }

    // Save to DB
    global $wpdb;
    $wpdb->insert( $wpdb->prefix . 'gh_contacts', [
        'horse_id'   => $horse_id ?: null,
        'name'       => $name,
        'email'      => $email,
        'phone'      => $phone,
        'subject'    => $subject,
        'message'    => $message,
        'created_at' => current_time( 'mysql' ),
    ] );

    // Send email to admin
    $horse_title = $horse_id ? get_the_title( $horse_id ) : '';
    $horse_url   = $horse_id ? get_permalink( $horse_id ) : '';

    $admin_email = get_option( 'admin_email' );
    $subject_line = $horse_title
        ? sprintf( '[GallopHub] Demande pour : %s', $horse_title )
        : '[GallopHub] Nouveau message de contact';

    $body = "Nom : {$name}\nEmail : {$email}\nTéléphone : {$phone}\nSujet : {$subject}\n\nMessage :\n{$message}";
    if ( $horse_title ) $body .= "\n\nCheval : {$horse_title}\n{$horse_url}";

    wp_mail( $admin_email, $subject_line, $body, [
        'Content-Type: text/plain; charset=UTF-8',
        "Reply-To: {$name} <{$email}>",
    ] );

    // Confirmation to sender
    wp_mail( $email, 'Votre message a bien été reçu — GallopHub',
        "Bonjour {$name},\n\nNous avons bien reçu votre message et vous répondrons dans les plus brefs délais.\n\nL'équipe GallopHub\ncontact@gallophub.es",
        [ 'Content-Type: text/plain; charset=UTF-8' ]
    );

    wp_send_json_success();
}
