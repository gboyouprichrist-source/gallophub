import { Link } from 'react-router-dom';
import { Truck, Search, FileCheck, ChevronRight } from 'lucide-react';

const SERVICES = [
  {
    icon: Search,
    title: 'Búsqueda personalizada',
    desc: 'No encuentras lo que buscas en nuestro catálogo? Describenos tu caballo ideal y lo encontraremos.',
    points: [
      'Análisis de tus necesidades (disciplina, nivel, presupuesto)',
      'Búsqueda en nuestra red española y europea',
      'Preselección de candidatos con vídeos',
      'Visita organizada con el vendedor',
    ],
  },
  {
    icon: Truck,
    title: 'Transporte équestre',
    desc: 'Transporte seguro de tu caballo en toda España y Europa, con transportistas homologados.',
    points: [
      'Transportistas equinos profesionales homologados EU',
      'Cobertura España, Países Bajos, Bélgica, Francia, Alemania',
      'Seguimiento GPS en tiempo real',
      'Paradas eau y alimentation prévues',
    ],
  },
  {
    icon: FileCheck,
    title: 'Acompañamiento administrativo',
    desc: 'Nos encargamos de toda la documentación para que la compra sea simple y segura.',
    points: [
      'Verificación del pasaporte equino',
      'Gestión de la documentación de exportación',
      'Coordinación con el veterinario',
      'Contrato de venta bilingüe',
    ],
  },
];

export function ServicesPage() {
  return (
    <>
      <title>Servicios — GallopHub | Transporte, búsqueda, asesoramiento</title>

      {/* Hero */}
      <section
        className="py-20"
        style={{ background: 'linear-gradient(135deg, var(--navy) 0%, var(--sky) 100%)' }}
      >
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h1 className="text-5xl font-bold font-serif text-white mb-4">Nuestros servicios</h1>
          <p className="text-white/80">Todo lo que necesitas para tu compra de caballo</p>
        </div>
      </section>

      <section className="py-20 pb-28 lg:pb-20">
        <div className="max-w-7xl mx-auto px-4">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            {SERVICES.map((s) => (
              <div
                key={s.title}
                className="bg-white rounded-lg p-8 border transition-all duration-200 hover:-translate-y-1"
                style={{ borderColor: 'var(--border)', boxShadow: 'var(--shadow)' }}
              >
                <div
                  className="w-12 h-12 rounded-lg flex items-center justify-center mb-6"
                  style={{ backgroundColor: 'var(--muted)' }}
                >
                  <s.icon size={24} style={{ color: 'var(--navy)' }} />
                </div>
                <h2 className="text-2xl font-serif font-semibold mb-3" style={{ color: 'var(--navy)' }}>{s.title}</h2>
                <p className="text-sm leading-relaxed mb-6" style={{ color: 'var(--sub)' }}>{s.desc}</p>
                <ul className="space-y-2">
                  {s.points.map((p) => (
                    <li key={p} className="flex items-start gap-2 text-sm" style={{ color: 'var(--text)' }}>
                      <span className="mt-1.5 w-1.5 h-1.5 rounded-full shrink-0" style={{ backgroundColor: 'var(--gold)' }} />
                      {p}
                    </li>
                  ))}
                </ul>
              </div>
            ))}
          </div>

          {/* CTA */}
          <div
            className="rounded-lg p-12 text-center"
            style={{ background: 'linear-gradient(135deg, var(--navy) 0%, var(--sky) 100%)' }}
          >
            <h2 className="text-3xl font-serif font-bold text-white mb-4">¿Preguntas sobre nuestros servicios?</h2>
            <p className="text-white/80 mb-8">Nuestro equipo está disponible para asesorarte.</p>
            <Link
              to="/contact"
              className="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold transition-opacity hover:opacity-90"
              style={{ backgroundColor: 'var(--gold)', color: 'var(--navy)' }}
            >
              Contactar <ChevronRight size={16} />
            </Link>
          </div>
        </div>
      </section>
    </>
  );
}
