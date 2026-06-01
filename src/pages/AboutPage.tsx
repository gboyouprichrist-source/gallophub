import { Link } from 'react-router-dom';
import { Shield, Award, Truck, Heart, Star, Globe, ChevronRight } from 'lucide-react';

const COMMITMENTS = [
  { icon: Shield, title: 'Transparencia total', desc: 'Documentación completa, visitas posibles, sin sorpresas.' },
  { icon: Award, title: 'Selección rigurosa', desc: 'Cada caballo es evaluado por su temperamento, salud y aptitudes.' },
  { icon: Truck, title: 'Transporte seguro', desc: 'Transportistas homologados, trayectos bien planificados.' },
  { icon: Heart, title: 'Bienestar animal', desc: 'Nuestros caballos viven en condiciones óptimas hasta la venta.' },
  { icon: Star, title: '20+ años de experiencia', desc: 'Conocemos el mercado, las razas y las expectativas de los compradores.' },
  { icon: Globe, title: 'Red europea', desc: 'Clientes en 15+ países. Entregamos en toda Europa.' },
];

export function AboutPage() {
  return (
    <>
      <title>À propos de GallopHub — Vente directe de chevaux en Espagne</title>

      {/* Hero */}
      <section
        className="py-24"
        style={{ background: 'linear-gradient(135deg, var(--navy) 0%, var(--sky) 100%)' }}
      >
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h1 className="text-5xl font-bold font-serif text-white mb-4">Notre histoire</h1>
          <p className="text-white/80 text-lg">
            Plus de 20 ans de passion pour les chevaux et la vente directe
          </p>
        </div>
      </section>

      {/* Story */}
      <section className="py-20">
        <div className="max-w-7xl mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
              <img
                src="https://images.unsplash.com/photo-1599595546558-26c81b4dcd12?w=800"
                alt="Élevage GallopHub"
                className="rounded-lg w-full object-cover"
                style={{ aspectRatio: '4/3', boxShadow: 'var(--shadow-lg)' }}
              />
            </div>
            <div>
              <span className="inline-block text-xs font-semibold px-3 py-1 rounded-full mb-4" style={{ backgroundColor: 'var(--muted)', color: 'var(--navy)' }}>
                Notre histoire
              </span>
              <h2 className="text-4xl font-serif font-semibold mb-6" style={{ color: 'var(--navy)' }}>
                La passion des chevaux depuis 20 ans
              </h2>
              <p className="text-sm leading-relaxed mb-4" style={{ color: 'var(--sub)' }}>
                GallopHub est né d'une passion profonde pour les chevaux et d'un constat simple :
                le marché de la vente de chevaux manque de transparence et de confiance.
                Nous avons voulu changer cela.
              </p>
              <p className="text-sm leading-relaxed mb-4" style={{ color: 'var(--sub)' }}>
                Basés en Espagne, nous sélectionnons chaque cheval avec soin — toutes les races,
                toutes les disciplines. Notre priorité : que l'acheteur et le cheval soient parfaitement compatibles.
              </p>
              <p className="text-sm leading-relaxed" style={{ color: 'var(--sub)' }}>
                Nous livrons dans toute l'Europe et accompagnons chaque vente de A à Z :
                visites, examens vétérinaires, transport, administratif.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Commitments */}
      <section className="py-20" style={{ backgroundColor: 'var(--muted)' }}>
        <div className="max-w-7xl mx-auto px-4">
          <h2 className="text-4xl font-serif font-bold text-center mb-12" style={{ color: 'var(--navy)' }}>
            Nos engagements
          </h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {COMMITMENTS.map((c) => (
              <div
                key={c.title}
                className="bg-white rounded-lg p-6 transition-all duration-200 hover:-translate-y-1"
                style={{ boxShadow: 'var(--shadow)' }}
              >
                <div
                  className="w-11 h-11 rounded-lg flex items-center justify-center mb-4"
                  style={{ backgroundColor: 'var(--muted)' }}
                >
                  <c.icon size={22} style={{ color: 'var(--navy)' }} />
                </div>
                <h3 className="font-serif font-semibold text-lg mb-2" style={{ color: 'var(--navy)' }}>{c.title}</h3>
                <p className="text-sm leading-relaxed" style={{ color: 'var(--sub)' }}>{c.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Stats */}
      <section style={{ backgroundColor: 'var(--navy)' }}>
        <div className="max-w-7xl mx-auto px-4 py-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
          {[
            { value: '20+', label: 'Années d\'expérience' },
            { value: '200+', label: 'Chevaux vendus' },
            { value: '15+', label: 'Pays' },
            { value: '98%', label: 'Clients satisfaits' },
          ].map((s) => (
            <div key={s.label}>
              <div className="text-4xl font-bold font-serif mb-1" style={{ color: 'var(--gold)' }}>{s.value}</div>
              <div className="text-sm text-white/70">{s.label}</div>
            </div>
          ))}
        </div>
      </section>

      {/* CTA */}
      <section className="py-20">
        <div className="max-w-3xl mx-auto px-4 text-center">
          <h2 className="text-4xl font-serif font-bold mb-4" style={{ color: 'var(--navy)' }}>Prêt à trouver votre cheval ?</h2>
          <p className="mb-8" style={{ color: 'var(--sub)' }}>Parcourez notre sélection ou contactez-nous directement.</p>
          <div className="flex flex-wrap justify-center gap-4">
            <Link
              to="/horses"
              className="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90"
              style={{ backgroundColor: 'var(--navy)' }}
            >
              Voir les chevaux <ChevronRight size={16} />
            </Link>
            <Link
              to="/contact"
              className="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold transition-opacity hover:opacity-90"
              style={{ backgroundColor: 'var(--gold)', color: 'var(--navy)' }}
            >
              Nous contacter
            </Link>
          </div>
        </div>
      </section>
    </>
  );
}
