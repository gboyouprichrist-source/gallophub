<?php
/**
 * Horses archive — listing + AJAX filters
 */
defined( 'ABSPATH' ) || exit;

get_header();

gh_seo_meta(
    __( 'Caballos en venta — GallopHub', 'gallophub' ),
    __( 'Más de 30 caballos disponibles. Filtra por disciplina, raza, sexo, precio. Entrega en toda Europa.', 'gallophub' )
);

$disciplines_all = get_terms( [ 'taxonomy' => 'discipline', 'hide_empty' => false ] );

$disc_map = ! is_wp_error( $disciplines_all ) && ! empty( $disciplines_all )
    ? array_column( (array) $disciplines_all, 'name', 'slug' )
    : [
        'dressage'  => __( 'Dressage',  'gallophub' ),
        'jumping'   => __( 'Saut',      'gallophub' ),
        'western'   => __( 'Western',   'gallophub' ),
        'leisure'   => __( 'Loisirs',   'gallophub' ),
        'pony'      => __( 'Poneys',    'gallophub' ),
        'endurance' => __( 'Endurance', 'gallophub' ),
    ];

$countries_all = [
    'España'      => __( 'Espagne',    'gallophub' ),
    'Nederland'   => __( 'Pays-Bas',   'gallophub' ),
    'België'      => __( 'Belgique',   'gallophub' ),
    'France'      => __( 'France',     'gallophub' ),
    'Deutschland' => __( 'Allemagne',  'gallophub' ),
    'Portugal'    => __( 'Portugal',   'gallophub' ),
];

$breeds_all = [
    'Andaluz', 'Lusitano', 'PRE', 'KWPN', 'Hispano-Árabe',
    'Criollo', 'Quarter Horse', 'Appaloosa', 'Warmblood', 'Árabe',
    'Frison', 'Selle Français', 'Hanovrien', 'Oldenbourg',
];

// URL discipline pre-filter
$url_disciplines = array_map( 'sanitize_text_field', (array) ( $_GET['disciplines'] ?? [] ) ); // phpcs:ignore WordPress.Security.NonceVerification

// Initial server-side query (no-JS fallback)
$init_args = [
    'post_type'      => 'horse',
    'posts_per_page' => 30,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => [ 'relation' => 'AND' ],
    'tax_query'      => [],
];
if ( ! empty( $url_disciplines ) ) {
    $init_args['tax_query'][] = [
        'taxonomy' => 'discipline',
        'field'    => 'slug',
        'terms'    => $url_disciplines,
        'operator' => 'IN',
    ];
}
$horses_query = new WP_Query( $init_args );
?>

<!-- ── Archive header ── -->
<div class="archive-header">
    <div class="container">
        <h1><?php esc_html_e( 'Caballos en venta', 'gallophub' ); ?></h1>
        <p><?php esc_html_e( '30+ caballos disponibles · Todas las razas · Entrega en Europa', 'gallophub' ); ?></p>
    </div>
</div>

<div class="container archive-wrap">

    <!-- Top bar: count + orderby -->
    <div class="archive-topbar">
        <p class="horses-count" id="horses-count" aria-live="polite">
            <?php echo esc_html( sprintf( _n( '%d cheval trouvé', '%d chevaux trouvés', $horses_query->found_posts, 'gallophub' ), $horses_query->found_posts ) ); ?>
        </p>
        <div style="display:flex;align-items:center;gap:.75rem">
            <label for="orderby-select" class="sr-only"><?php esc_html_e( 'Trier par', 'gallophub' ); ?></label>
            <select id="orderby-select" name="orderby" class="filter-select" style="width:auto">
                <option value="date"><?php esc_html_e( 'Plus récents', 'gallophub' ); ?></option>
                <option value="price_asc"><?php esc_html_e( 'Prix croissant', 'gallophub' ); ?></option>
                <option value="price_desc"><?php esc_html_e( 'Prix décroissant', 'gallophub' ); ?></option>
                <option value="age_asc"><?php esc_html_e( 'Plus jeunes', 'gallophub' ); ?></option>
            </select>
            <button class="filter-toggle"
                    aria-label="<?php esc_attr_e( 'Afficher les filtres', 'gallophub' ); ?>"
                    aria-expanded="false"
                    aria-controls="horse-filters-sidebar">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
                </svg>
                <?php esc_html_e( 'Filtros', 'gallophub' ); ?>
            </button>
        </div>
    </div>

    <div class="sidebar-overlay" aria-hidden="true"></div>

    <div class="horses-layout">

        <!-- ── Sidebar filters ── -->
        <aside class="horses-sidebar" id="horse-filters-sidebar" aria-label="<?php esc_attr_e( 'Filtres', 'gallophub' ); ?>">
            <form id="horse-filter-form" novalidate>

                <div class="filter-title">
                    <?php esc_html_e( 'Filtros', 'gallophub' ); ?>
                    <button type="button" id="filter-reset" class="filter-reset">
                        <?php esc_html_e( 'Limpiar todo', 'gallophub' ); ?>
                    </button>
                </div>

                <!-- Available only -->
                <label class="filter-checkbox filter-available">
                    <input type="checkbox" name="available_only" value="1">
                    <span><?php esc_html_e( 'Solo disponibles', 'gallophub' ); ?></span>
                </label>

                <!-- Status -->
                <div class="filter-section">
                    <span class="filter-label"><?php esc_html_e( 'Statut', 'gallophub' ); ?></span>
                    <select name="status" class="filter-select">
                        <option value=""><?php esc_html_e( 'Tous', 'gallophub' ); ?></option>
                        <option value="available"><?php esc_html_e( 'Disponible', 'gallophub' ); ?></option>
                        <option value="reserved"><?php esc_html_e( 'Réservé', 'gallophub' ); ?></option>
                        <option value="sold"><?php esc_html_e( 'Vendu', 'gallophub' ); ?></option>
                    </select>
                </div>

                <!-- Discipline -->
                <div class="filter-section">
                    <span class="filter-label"><?php esc_html_e( 'Disciplina', 'gallophub' ); ?></span>
                    <?php foreach ( $disc_map as $slug => $name ) : ?>
                    <label class="filter-checkbox">
                        <input type="checkbox"
                               name="disciplines[]"
                               value="<?php echo esc_attr( $slug ); ?>"
                               <?php checked( in_array( $slug, $url_disciplines, true ) ); ?>>
                        <span><?php echo esc_html( $name ); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>

                <!-- Breed -->
                <div class="filter-section">
                    <span class="filter-label"><?php esc_html_e( 'Raza', 'gallophub' ); ?></span>
                    <select name="breed" class="filter-select">
                        <option value=""><?php esc_html_e( 'Todas las razas', 'gallophub' ); ?></option>
                        <?php foreach ( $breeds_all as $b ) : ?>
                        <option value="<?php echo esc_attr( $b ); ?>"><?php echo esc_html( $b ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Gender -->
                <div class="filter-section">
                    <span class="filter-label"><?php esc_html_e( 'Sexo', 'gallophub' ); ?></span>
                    <select name="gender" class="filter-select">
                        <option value=""><?php esc_html_e( 'Todos', 'gallophub' ); ?></option>
                        <option value="mare"><?php esc_html_e( 'Jument', 'gallophub' ); ?></option>
                        <option value="stallion"><?php esc_html_e( 'Étalon', 'gallophub' ); ?></option>
                        <option value="gelding"><?php esc_html_e( 'Hongre', 'gallophub' ); ?></option>
                        <option value="pony"><?php esc_html_e( 'Poney', 'gallophub' ); ?></option>
                    </select>
                </div>

                <!-- Country -->
                <div class="filter-section">
                    <span class="filter-label"><?php esc_html_e( 'País', 'gallophub' ); ?></span>
                    <select name="country" class="filter-select">
                        <option value=""><?php esc_html_e( 'Todos los países', 'gallophub' ); ?></option>
                        <?php foreach ( $countries_all as $code => $label ) : ?>
                        <option value="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Price -->
                <div class="filter-section">
                    <span class="filter-label"><?php esc_html_e( 'Precio (€)', 'gallophub' ); ?></span>
                    <div class="filter-range-row">
                        <input type="number" name="price_min" placeholder="Min" min="0" step="500">
                        <input type="number" name="price_max" placeholder="Max" min="0" step="500">
                    </div>
                </div>

                <!-- Age -->
                <div class="filter-section">
                    <span class="filter-label"><?php esc_html_e( 'Edad', 'gallophub' ); ?></span>
                    <div class="filter-range-row">
                        <input type="number" name="age_min" placeholder="Min" min="0" max="40">
                        <input type="number" name="age_max" placeholder="Max" min="0" max="40">
                    </div>
                </div>

                <!-- Mobile close -->
                <button type="button" class="filter-close-mobile" aria-label="<?php esc_attr_e( 'Fermer les filtres', 'gallophub' ); ?>">
                    <?php esc_html_e( 'Appliquer les filtres', 'gallophub' ); ?>
                </button>

            </form>
        </aside><!-- /.horses-sidebar -->

        <!-- ── Grid ── -->
        <section class="horses-main" aria-label="<?php esc_attr_e( 'Liste des chevaux', 'gallophub' ); ?>">
            <div class="horses-grid" id="horses-grid">
                <?php if ( $horses_query->have_posts() ) :
                    while ( $horses_query->have_posts() ) {
                        $horses_query->the_post();
                        get_template_part( 'template-parts/horse-card' );
                    }
                    wp_reset_postdata();
                else : ?>
                <div class="horses-no-result">
                    <p><?php esc_html_e( 'Aucun cheval pour le moment.', 'gallophub' ); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Loading spinner (hidden by default) -->
            <div class="horses-loading" id="horses-loading" aria-hidden="true" style="display:none">
                <div class="spinner" aria-label="<?php esc_attr_e( 'Chargement...', 'gallophub' ); ?>"></div>
            </div>
        </section>

    </div><!-- /.horses-layout -->
</div><!-- /.container -->

<?php get_footer(); ?>
