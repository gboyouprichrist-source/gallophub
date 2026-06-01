<?php
/**
 * Horses archive — listing + filters
 */
get_header();
gh_seo_meta(
    'Caballos en venta — GallopHub | 30+ caballos disponibles',
    'Más de 30 caballos disponibles. Filtra por disciplina, raza, sexo, precio. Entrega en Europa.',
);

$disciplines_all = get_terms( [ 'taxonomy' => 'discipline', 'hide_empty' => false ] );
$countries_all   = [ 'España', 'Nederland', 'België', 'France', 'Deutschland', 'Portugal' ];
$breeds_all      = [ 'Andaluz', 'Lusitano', 'PRE', 'KWPN', 'Hispano-Árabe', 'Criollo', 'Quarter Horse', 'Appaloosa', 'Warmblood', 'Árabe' ];

// Initial query (shown without JS too)
$init_args = [
    'post_type'      => 'horse',
    'posts_per_page' => 30,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
];
$horses_query = new WP_Query( $init_args );
?>

<!-- Page header -->
<div style="padding:3.5rem 0 2rem;background:var(--muted);border-bottom:1px solid var(--border)">
    <div class="container">
        <h1 style="font-size:clamp(2rem,4vw,2.75rem);margin-bottom:.35rem">Caballos en venta</h1>
        <p style="color:var(--sub);font-size:13px">Más de 30 caballos disponibles · Todas las razas · Entrega en Europa</p>
    </div>
</div>

<div class="container" style="padding-top:2.5rem;padding-bottom:7rem">

    <!-- Mobile filter toggle -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
        <p class="horses-count" id="horses-count"><?php echo esc_html( $horses_query->found_posts ); ?> cheval(aux) trouvé(s)</p>
        <button class="filter-toggle" aria-label="Filtres" aria-expanded="false">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
            Filtros
        </button>
    </div>
    <div class="sidebar-overlay"></div>

    <div class="horses-layout">

        <!-- Sidebar -->
        <aside class="horses-sidebar" id="horse-filters-sidebar">
            <form id="horse-filter-form" novalidate>

                <div class="filter-title">
                    Filtros
                    <button type="button" id="filter-reset" class="filter-reset">Limpiar todo</button>
                </div>

                <!-- Available only -->
                <label class="filter-checkbox" style="margin-bottom:1.25rem">
                    <input type="checkbox" name="available_only" value="1">
                    <span>Solo disponibles</span>
                </label>

                <!-- Discipline -->
                <div class="filter-section">
                    <span class="filter-label">Disciplina</span>
                    <?php
                    $disc_list = ! is_wp_error( $disciplines_all ) && ! empty( $disciplines_all )
                        ? array_column( (array) $disciplines_all, 'name', 'slug' )
                        : [ 'dressage' => 'Dressage', 'jumping' => 'Saut', 'western' => 'Western', 'leisure' => 'Loisirs', 'pony' => 'Poney', 'endurance' => 'Endurance' ];
                    foreach ( $disc_list as $slug => $name ) : ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="disciplines[]" value="<?= esc_attr( $slug ) ?>">
                            <span><?= esc_html( $name ) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <!-- Breed -->
                <div class="filter-section">
                    <span class="filter-label">Raza</span>
                    <select name="breed" class="filter-select">
                        <option value="">Todas las razas</option>
                        <?php foreach ( $breeds_all as $b ) : ?>
                            <option value="<?= esc_attr( $b ) ?>"><?= esc_html( $b ) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Gender -->
                <div class="filter-section">
                    <span class="filter-label">Sexo</span>
                    <select name="gender" class="filter-select">
                        <option value="">Todos</option>
                        <option value="mare">Jument</option>
                        <option value="stallion">Étalon</option>
                        <option value="gelding">Hongre</option>
                        <option value="pony">Poney</option>
                    </select>
                </div>

                <!-- Country -->
                <div class="filter-section">
                    <span class="filter-label">País</span>
                    <select name="country" class="filter-select">
                        <option value="">Todos los países</option>
                        <?php foreach ( $countries_all as $c ) : ?>
                            <option value="<?= esc_attr( $c ) ?>"><?= esc_html( $c ) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Price -->
                <div class="filter-section">
                    <span class="filter-label">Precio (€)</span>
                    <div class="filter-range-row">
                        <input type="number" name="price_min" placeholder="Min" min="0">
                        <input type="number" name="price_max" placeholder="Max" min="0">
                    </div>
                </div>

                <!-- Age -->
                <div class="filter-section">
                    <span class="filter-label">Edad</span>
                    <div class="filter-range-row">
                        <input type="number" name="age_min" placeholder="Min" min="0" max="40">
                        <input type="number" name="age_max" placeholder="Max" min="0" max="40">
                    </div>
                </div>

            </form>
        </aside><!-- /.horses-sidebar -->

        <!-- Grid -->
        <div class="horses-main">
            <div class="grid-3" id="horses-grid">
                <?php if ( $horses_query->have_posts() ) :
                    while ( $horses_query->have_posts() ) { $horses_query->the_post(); get_template_part( 'template-parts/horse-card' ); }
                    wp_reset_postdata();
                else : ?>
                    <div style="grid-column:1/-1;text-align:center;padding:4rem 0">
                        <p style="color:var(--sub)">Aucun cheval pour le moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div><!-- /.horses-layout -->
</div>

<?php get_footer(); ?>
