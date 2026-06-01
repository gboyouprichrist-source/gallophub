<?php
/**
 * GallopHub — AJAX handlers
 *
 * @package GallopHub
 */

defined( 'ABSPATH' ) || exit;

/* ═══════════════════════════════════════════════════════════
   FILTER HORSES (listing + archive)
═══════════════════════════════════════════════════════════ */
add_action( 'wp_ajax_gh_filter_horses',        'gh_ajax_filter_horses' );
add_action( 'wp_ajax_nopriv_gh_filter_horses', 'gh_ajax_filter_horses' );

function gh_ajax_filter_horses(): void {
    check_ajax_referer( 'gh_ajax', 'nonce' );

    /* ── Sanitize inputs ── */
    $disciplines    = array_map( 'sanitize_text_field', (array) ( $_POST['disciplines'] ?? [] ) );
    $disciplines    = array_filter( $disciplines );
    $breed          = sanitize_text_field( $_POST['breed']          ?? '' );
    $gender         = sanitize_key( $_POST['gender']                ?? '' );
    $country        = sanitize_text_field( $_POST['country']        ?? '' );
    $price_min      = (float) ( $_POST['price_min']                 ?? 0 );
    $price_max      = (float) ( $_POST['price_max']                 ?? 0 );
    $age_min        = (int)   ( $_POST['age_min']                   ?? 0 );
    $age_max        = (int)   ( $_POST['age_max']                   ?? 0 );
    $available_only = ! empty( $_POST['available_only'] );
    $status_filter  = sanitize_key( $_POST['status']               ?? '' );
    $orderby        = sanitize_key( $_POST['orderby']              ?? 'date' );

    /* ── Build WP_Query args ── */
    $args = [
        'post_type'      => 'horse',
        'posts_per_page' => 30,
        'post_status'    => 'publish',
        'meta_query'     => [ 'relation' => 'AND' ],
        'tax_query'      => [],
    ];

    // Ordering
    switch ( $orderby ) {
        case 'price_asc':
            $args['meta_key'] = 'gh_price';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            break;
        case 'price_desc':
            $args['meta_key'] = 'gh_price';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;
        case 'age_asc':
            $args['meta_key'] = 'gh_age';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            break;
        default:
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
    }

    // Status filters
    if ( $available_only ) {
        $args['meta_query'][] = [ 'key' => 'gh_status', 'value' => 'available' ];
    } elseif ( $status_filter ) {
        $args['meta_query'][] = [ 'key' => 'gh_status', 'value' => $status_filter ];
    }

    if ( $gender ) {
        $args['meta_query'][] = [ 'key' => 'gh_gender', 'value' => $gender ];
    }
    if ( $country ) {
        $args['meta_query'][] = [ 'key' => 'gh_country', 'value' => $country ];
    }
    if ( $breed ) {
        $args['meta_query'][] = [ 'key' => 'gh_breed', 'value' => $breed, 'compare' => 'LIKE' ];
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

    if ( ! empty( $disciplines ) ) {
        $args['tax_query'][] = [
            'taxonomy' => 'discipline',
            'field'    => 'slug',
            'terms'    => $disciplines,
            'operator' => 'IN',
        ];
    }

    /* ── Run query & render ── */
    $query = new WP_Query( $args );

    ob_start();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/horse-card' );
        }
        wp_reset_postdata();
    } else {
        echo '<div class="horses-no-result">';
        echo '<p>' . esc_html__( 'Aucun cheval trouvé avec ces filtres.', 'gallophub' ) . '</p>';
        echo '<button type="button" onclick="document.getElementById(\'horse-filter-form\').reset();document.getElementById(\'horse-filter-form\').dispatchEvent(new Event(\'change\'));" class="btn-navy" style="margin-top:1rem">';
        echo esc_html__( 'Réinitialiser les filtres', 'gallophub' );
        echo '</button></div>';
    }
    $html = ob_get_clean();

    wp_send_json_success( [
        'html'  => $html,
        'count' => $query->found_posts,
    ] );
}

/* ═══════════════════════════════════════════════════════════
   CONTACT FORM SUBMISSION
═══════════════════════════════════════════════════════════ */
add_action( 'wp_ajax_gh_submit_contact',        'gh_ajax_submit_contact' );
add_action( 'wp_ajax_nopriv_gh_submit_contact', 'gh_ajax_submit_contact' );

function gh_ajax_submit_contact(): void {
    check_ajax_referer( 'gh_ajax', 'nonce' );

    // Basic honeypot
    if ( ! empty( $_POST['website'] ) ) {
        wp_send_json_error( 'Bot detected.' );
    }

    $name     = sanitize_text_field( $_POST['name']     ?? '' );
    $email    = sanitize_email( $_POST['email']         ?? '' );
    $phone    = sanitize_text_field( $_POST['phone']    ?? '' );
    $subject  = sanitize_text_field( $_POST['subject']  ?? '' );
    $message  = sanitize_textarea_field( $_POST['message'] ?? '' );
    $horse_id = absint( $_POST['horse_id']              ?? 0 );

    // Validation
    $errors = [];
    if ( strlen( $name ) < 2 ) {
        $errors[] = __( 'Le nom est requis (minimum 2 caractères).', 'gallophub' );
    }
    if ( ! is_email( $email ) ) {
        $errors[] = __( 'Adresse email invalide.', 'gallophub' );
    }
    if ( strlen( $message ) < 10 ) {
        $errors[] = __( 'Le message est trop court (minimum 10 caractères).', 'gallophub' );
    }
    if ( ! empty( $errors ) ) {
        wp_send_json_error( implode( ' ', $errors ) );
    }

    // Rate limiting (simple: 3 submissions per IP per hour)
    $ip        = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' );
    $cache_key = 'gh_contact_' . md5( $ip );
    $count     = (int) get_transient( $cache_key );
    if ( $count >= 5 ) {
        wp_send_json_error( __( 'Trop de tentatives. Veuillez patienter.', 'gallophub' ) );
    }
    set_transient( $cache_key, $count + 1, HOUR_IN_SECONDS );

    // Save to DB
    global $wpdb;
    $wpdb->insert(
        $wpdb->prefix . 'gh_contacts',
        [
            'horse_id'   => $horse_id ?: null,
            'name'       => $name,
            'email'      => $email,
            'phone'      => $phone,
            'subject'    => $subject,
            'message'    => $message,
            'created_at' => current_time( 'mysql' ),
        ],
        [ '%d', '%s', '%s', '%s', '%s', '%s', '%s' ]
    );

    // Destination email from options (WP Mail SMTP will intercept)
    $dest          = get_option( 'gh_contact_email', get_option( 'admin_email' ) );
    $horse_title   = $horse_id ? get_the_title( $horse_id ) : '';
    $horse_url     = $horse_id ? get_permalink( $horse_id ) : '';

    $subject_line = $horse_title
        ? sprintf( __( '[GallopHub] Demande pour : %s', 'gallophub' ), $horse_title )
        : __( '[GallopHub] Nouveau message de contact', 'gallophub' );

    $body  = sprintf( __( "Nom : %s\nEmail : %s\nTéléphone : %s\nSujet : %s\n\nMessage :\n%s", 'gallophub' ), $name, $email, $phone, $subject, $message );
    if ( $horse_title ) {
        $body .= "\n\n" . sprintf( __( "Cheval : %s\n%s", 'gallophub' ), $horse_title, $horse_url );
    }

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        "Reply-To: {$name} <{$email}>",
    ];

    // Admin notification
    wp_mail( $dest, $subject_line, $body, $headers );

    // Buyer confirmation
    $confirm_subject = __( 'Votre message a bien été reçu — GallopHub', 'gallophub' );
    $confirm_body    = sprintf(
        __( "Bonjour %s,\n\nNous avons bien reçu votre message et vous répondrons dans les plus brefs délais.\n\nL'équipe GallopHub\ncontact@gallophub.es\nhttps://gallophub.es", 'gallophub' ),
        $name
    );
    wp_mail( $email, $confirm_subject, $confirm_body, [ 'Content-Type: text/plain; charset=UTF-8' ] );

    // GA4 event tracking response hint
    wp_send_json_success( [ 'ga4_event' => 'generate_lead' ] );
}
