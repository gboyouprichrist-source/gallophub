<?php /* Template Name: Services */ get_header();
gh_seo_meta( 'Services — GallopHub | Transport, recherche, conseil', 'Recherche de cheval personnalisée, transport équin européen, accompagnement administratif.' );
$services = [
    [ 'title' => 'Búsqueda personalizada',
      'desc'  => 'No encuentras lo que buscas en nuestro catálogo? Descríbenos tu caballo ideal y lo encontraremos.',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>',
      'points'=> [ 'Análisis de tus necesidades (disciplina, nivel, presupuesto)', 'Búsqueda en nuestra red española y europea', 'Preselección de candidatos con vídeos', 'Visita organizada con el vendedor' ] ],
    [ 'title' => 'Transporte équestre',
      'desc'  => 'Transporte seguro de tu caballo en toda España y Europa, con transportistas homologados.',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>',
      'points'=> [ 'Transportistas equinos profesionales homologados EU', 'Cobertura España, Países Bajos, Bélgica, Francia, Alemania', 'Seguimiento GPS en tiempo real', 'Paradas água e alimentação previstas' ] ],
    [ 'title' => 'Acompañamiento administrativo',
      'desc'  => 'Nos encargamos de toda la documentación para que la compra sea simple y segura.',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
      'points'=> [ 'Verificación del pasaporte equino', 'Gestión de la documentación de exportación', 'Coordinación con el veterinario', 'Contrato de venta bilingüe' ] ],
];
?>
<section class="page-hero">
    <div class="container">
        <h1>Nuestros servicios</h1>
        <p>Todo lo que necesitas para tu compra de caballo</p>
    </div>
</section>

<section class="section-py" style="padding-bottom:0">
    <div class="container">
        <div class="grid-3" style="margin-bottom:5rem">
            <?php foreach ( $services as $s ) : ?>
                <div class="service-card">
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><?= $s['icon'] ?></svg>
                    </div>
                    <h2><?= esc_html( $s['title'] ) ?></h2>
                    <p><?= esc_html( $s['desc'] ) ?></p>
                    <ul class="service-points">
                        <?php foreach ( $s['points'] as $pt ) : ?>
                            <li><?= esc_html( $pt ) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="container">
        <h2>¿Preguntas sobre nuestros servicios?</h2>
        <p>Nuestro equipo está disponible para asesorarte.</p>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-gold" style="font-size:15px;padding:.85rem 2rem">
            Contactar
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

<?php get_footer(); ?>
