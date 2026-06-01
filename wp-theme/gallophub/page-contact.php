<?php /* Template Name: Contact */ get_header();
gh_seo_meta( 'Contactar — GallopHub | Venta directa de caballos', 'Contactez GallopHub par email, WhatsApp ou formulaire. Réponse en 48h.' );
?>
<section class="page-hero">
    <div class="container">
        <h1>Contactar</h1>
        <p>Estamos aquí para ayudarte a encontrar el caballo perfecto</p>
    </div>
</section>

<section class="section-py" style="padding-bottom:7rem">
    <div class="container">
        <div class="two-col" style="align-items:flex-start;gap:4rem">

            <!-- Info -->
            <div>
                <h2 style="font-size:1.75rem;margin-bottom:2rem">Información de contacto</h2>
                <?php
                $info = [
                    [ 'label' => 'Email', 'value' => '<a href="mailto:contact@gallophub.es" style="color:var(--sky)">contact@gallophub.es</a>',
                      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>' ],
                    [ 'label' => 'Teléfono / WhatsApp', 'value' => '<a href="https://wa.me/'.esc_attr(get_option('gh_whatsapp_number','34600000000')).'" target="_blank" rel="noopener noreferrer" style="color:var(--sky)">+'.esc_html(get_option('gh_whatsapp_number','34600000000')).'</a>',
                      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>' ],
                    [ 'label' => 'Ubicación', 'value' => 'España',
                      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>' ],
                    [ 'label' => 'Horarios', 'value' => 'Lun–Vie: 9h–18h<br>Sáb: 9h–14h',
                      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>' ],
                ];
                foreach ( $info as $item ) : ?>
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><?= $item['icon'] ?></svg>
                        </div>
                        <div>
                            <div class="contact-info-label"><?= esc_html( $item['label'] ) ?></div>
                            <div class="contact-info-value"><?= $item['value'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <a href="https://wa.me/<?= esc_attr( get_option( 'gh_whatsapp_number', '34600000000' ) ) ?>?text=<?= urlencode( 'Bonjour, je souhaite en savoir plus sur vos chevaux.' ) ?>"
                   target="_blank" rel="noopener noreferrer" class="btn-wa"
                   style="display:flex;align-items:center;justify-content:center;gap:.65rem;padding:1rem;font-size:15px;margin-top:1.5rem">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Contacter via WhatsApp
                </a>
            </div>

            <!-- Form -->
            <div>
                <div class="contact-card" style="box-shadow:var(--shadow-lg)">
                    <h3 style="font-size:1.5rem;margin-bottom:1.5rem">Formulario de contacto</h3>
                    <form class="gh-contact-form" novalidate>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                            <div class="form-field"><input type="text" name="name" placeholder="Nombre *" required></div>
                            <div class="form-field"><input type="email" name="email" placeholder="Email *" required></div>
                        </div>
                        <div class="form-field"><input type="tel" name="phone" placeholder="Teléfono"></div>
                        <div class="form-field">
                            <select name="subject">
                                <option value="">Seleccionar asunto…</option>
                                <option value="purchase">Comprar un caballo</option>
                                <option value="info">Información general</option>
                                <option value="transport">Transporte</option>
                                <option value="visit">Organizar una visita</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                        <div class="form-field"><textarea name="message" rows="5" placeholder="Mensaje *" required></textarea></div>
                        <button type="submit" class="btn-submit">Enviar mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
