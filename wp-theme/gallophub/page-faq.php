<?php /* Template Name: FAQ */ get_header();
gh_seo_meta( 'FAQ — Preguntas frecuentes — GallopHub', 'Tout ce qu\'il faut savoir avant d\'acheter un cheval : processus de vente, livraison, paiement, retours.' );
$faqs = [
    [ '¿Cómo funciona la compra de un caballo en GallopHub?', 'El proceso es simple: contactas con nosotros por WhatsApp o formulario, te enviamos documentación completa (fotos, vídeos, historial veterinario), organizamos una visita si lo deseas, firmamos un contrato de venta y realizas un acompte del 20–30%. El resto se paga antes de la entrega.' ],
    [ '¿Puedo visitar el caballo antes de comprarlo?', 'Absolutamente. Recomendamos siempre una visita presencial. También podemos organizar una visita virtual por videollamada para compradores internacionales. Nunca vendemos un caballo sin que el comprador haya tenido la oportunidad de verlo.' ],
    [ '¿Realizáis entrega a otros países europeos?', 'Sí. Entregamos en toda Europa mediante transportistas equinos profesionales homologados. Los plazos son: España 1–3 días, Países Bajos/Bélgica 5–10 días, resto de Europa 7–15 días. Los gastos de transporte son por cuenta del comprador salvo acuerdo contrario.' ],
    [ '¿Qué documentación se entrega con el caballo?', 'Entregamos el pasaporte equino (obligatorio en la UE), historial veterinario, vacunas al día, certificado de microchip, y cualquier título o pedigrí disponible. Para exportaciones fuera de España, gestionamos toda la documentación necesaria.' ],
    [ '¿Cuáles son las formas de pago aceptadas?', 'Aceptamos transferencia bancaria SEPA (preferida) y pago con tarjeta via Stripe para el acompte. El pago íntegro se exige antes de la entrega del caballo.' ],
    [ '¿Qué ocurre si el caballo no corresponde a la descripción?', 'Si el caballo no corresponde a la descripción (raza, edad, sexo incorrectos), debes comunicarlo en 48 horas con fotos y el informe veterinario. Recuperaremos el caballo y te reembolsaremos íntegramente, incluyendo los gastos de transporte de retorno.' ],
    [ '¿Existe un derecho de desistimiento para la compra de un caballo?', 'La venta de animales vivos está excluida del derecho de desistimiento de 14 días. Sin embargo, nos comprometemos a que el comprador esté plenamente informado antes de la compra : visita, vídeo, documentación veterinaria.' ],
    [ '¿Podéis ayudarme a encontrar un caballo específico que no está en el catálogo?', 'Sí. Gracias a nuestra red en España y Europa, podemos buscar caballos según vuestras necesidades específicas (raza, edad, disciplina, nivel, presupuesto). Contactadnos describiendo vuestro perfil de caballo ideal.' ],
];
?>
<section class="page-hero">
    <div class="container">
        <h1>Preguntas frecuentes</h1>
        <p>Todo lo que necesitas saber antes de comprar tu caballo</p>
    </div>
</section>

<section class="section-py" style="padding-bottom:7rem">
    <div class="container" style="max-width:800px">
        <div class="legal-card">
            <div class="faq-list">
                <?php foreach ( $faqs as $i => [$q, $a] ) : ?>
                    <div class="faq-item <?= $i === 0 ? 'open' : '' ?>">
                        <button class="faq-question" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>">
                            <?= esc_html( $q ) ?>
                            <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="faq-answer">
                            <p><?= esc_html( $a ) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="text-center" style="margin-top:3rem">
            <p style="color:var(--sub);margin-bottom:1rem">¿Tienes otras preguntas?</p>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-navy">Contáctanos</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
