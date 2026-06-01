import { useState } from 'react';
import { Link } from 'react-router-dom';
import { ChevronDown } from 'lucide-react';

const FAQS = [
  {
    q: '¿Cómo funciona la compra de un caballo en GallopHub?',
    a: 'El proceso es simple: contactas con nosotros por WhatsApp o formulario, te enviamos documentación completa (fotos, vídeos, historial veterinario), organizamos una visita si lo deseas, firmamos un contrato de venta y realizas un acompte del 20–30%. El resto se paga antes de la entrega.',
  },
  {
    q: '¿Puedo visitar el caballo antes de comprarlo?',
    a: 'Absolutamente. Recomendamos siempre una visita presencial. También podemos organizar una visita virtual por videollamada para compradores internacionales. Nunca vendemos un caballo sin que el comprador haya tenido la oportunidad de verlo.',
  },
  {
    q: '¿Realizáis entrega a otros países europeos?',
    a: 'Sí. Entregamos en toda Europa mediante transportistas equinos profesionales homologados. Los plazos son: España 1–3 días, Países Bajos/Bélgica 5–10 días, resto de Europa 7–15 días. Los gastos de transporte son por cuenta del comprador salvo acuerdo contrario.',
  },
  {
    q: '¿Qué documentación se entrega con el caballo?',
    a: 'Entregamos el pasaporte equino (obligatorio en la UE), historial veterinario, vacunas al día, certificado de microchip, y cualquier título o pedigrí disponible. Para exportaciones fuera de España, gestionamos toda la documentación necesaria.',
  },
  {
    q: '¿Cuáles son las formas de pago aceptadas?',
    a: 'Aceptamos transferencia bancaria SEPA (preferida) y pago con tarjeta via Stripe para el acompte. El pago íntegro se exige antes de la entrega del caballo.',
  },
  {
    q: '¿Qué ocurre si el caballo no corresponde a la descripción?',
    a: 'Si el caballo no corresponde a la descripción (raza, edad, sexo incorrectos), debes comunicarlo en 48 horas con fotos y el informe veterinario. Recuperaremos el caballo y te reembolsaremos íntegramente, incluyendo los gastos de transporte de retorno.',
  },
  {
    q: '¿Existe un derecho de desistimiento para la compra de un caballo?',
    a: 'La venta de animales vivos está excluida del derecho de desistimiento de 14 días (animales vivos = bienes no retornables por naturaleza). Sin embargo, nos comprometemos a que el comprador esté plenamente informado antes de la compra: visita, vídeo, documentación veterinaria.',
  },
  {
    q: '¿Podéis ayudarme a encontrar un caballo específico que no está en el catálogo?',
    a: 'Sí. Gracias a nuestra red en España y Europa, podemos buscar caballos según vuestras necesidades específicas (raza, edad, disciplina, nivel, presupuesto). Contactadnos describiendo vuestro perfil de caballo ideal.',
  },
];

function FaqItem({ q, a }: { q: string; a: string }) {
  const [open, setOpen] = useState(false);
  return (
    <div className="border-b" style={{ borderColor: 'var(--border)' }}>
      <button
        onClick={() => setOpen(!open)}
        className="w-full flex items-center justify-between py-5 text-left gap-4"
      >
        <span className="text-sm font-semibold" style={{ color: 'var(--navy)' }}>{q}</span>
        <ChevronDown
          size={18}
          className="shrink-0 transition-transform duration-300"
          style={{ color: 'var(--gold)', transform: open ? 'rotate(180deg)' : 'rotate(0deg)' }}
        />
      </button>
      <div
        className="overflow-hidden transition-all duration-300"
        style={{ maxHeight: open ? '400px' : '0px' }}
      >
        <p className="text-sm leading-relaxed pb-5" style={{ color: 'var(--sub)' }}>{a}</p>
      </div>
    </div>
  );
}

export function FaqPage() {
  return (
    <>
      <title>FAQ — Preguntas frecuentes — GallopHub</title>

      <section
        className="py-20"
        style={{ background: 'linear-gradient(135deg, var(--navy) 0%, var(--sky) 100%)' }}
      >
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h1 className="text-5xl font-bold font-serif text-white mb-4">Preguntas frecuentes</h1>
          <p className="text-white/80">Todo lo que necesitas saber antes de comprar tu caballo</p>
        </div>
      </section>

      <section className="py-20 pb-28 lg:pb-20">
        <div className="max-w-3xl mx-auto px-4">
          <div className="bg-white rounded-lg border p-8" style={{ borderColor: 'var(--border)', boxShadow: 'var(--shadow)' }}>
            {FAQS.map((faq) => <FaqItem key={faq.q} q={faq.q} a={faq.a} />)}
          </div>

          <div className="text-center mt-12">
            <p className="mb-4" style={{ color: 'var(--sub)' }}>¿Tienes otras preguntas?</p>
            <Link
              to="/contact"
              className="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90"
              style={{ backgroundColor: 'var(--navy)' }}
            >
              Contáctanos
            </Link>
          </div>
        </div>
      </section>
    </>
  );
}
