import { Link } from 'react-router-dom';
import { Calendar, Truck, CreditCard } from 'lucide-react';

const PRODUCTS = [
  {
    icon: Calendar,
    title: 'Réservation visite',
    price: 50,
    desc: 'Réservez votre créneau de visite pour voir le cheval en personne. Remboursable si annulation 48h avant.',
    details: ['Créneau de 2h sur le site', 'Essai à cheval possible', 'Questions avec le propriétaire', 'Rapport d\'impression inclus'],
  },
  {
    icon: CreditCard,
    title: 'Acompte réservation',
    price: 500,
    desc: 'Bloquez votre cheval avec un acompte. Le montant est déduit du prix final à l\'achat.',
    details: ['Cheval réservé 30 jours', 'Acompte déduit du prix', 'Contrat de réservation fourni', 'Non remboursable sauf défaut vendeur'],
  },
  {
    icon: Truck,
    title: 'Transport Europe',
    price: 800,
    desc: 'Transport professionnel de votre cheval depuis l\'Espagne vers votre pays (hors cas particuliers).',
    details: ['Transporteurs homologués EU', 'Assurance transport incluse', 'Suivi en temps réel', 'Livraison à votre porte'],
  },
];

export function ShopPage() {
  return (
    <>
      <title>Boutique — GallopHub | Réservation, acompte, transport</title>

      {/* Hero */}
      <section
        className="py-20"
        style={{ background: 'linear-gradient(135deg, var(--navy) 0%, var(--sky) 100%)' }}
      >
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h1 className="text-5xl font-bold font-serif text-white mb-4">Boutique</h1>
          <p className="text-white/80">Réservation, acompte et transport — paiement sécurisé via Stripe</p>
        </div>
      </section>

      <section className="py-20 pb-28 lg:pb-20">
        <div className="max-w-6xl mx-auto px-4">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {PRODUCTS.map((product) => (
              <div
                key={product.title}
                className="bg-white rounded-lg border overflow-hidden transition-all duration-200 hover:-translate-y-1"
                style={{ borderColor: 'var(--border)', boxShadow: 'var(--shadow)' }}
              >
                <div className="p-8">
                  <div
                    className="w-12 h-12 rounded-lg flex items-center justify-center mb-5"
                    style={{ backgroundColor: 'var(--muted)' }}
                  >
                    <product.icon size={24} style={{ color: 'var(--navy)' }} />
                  </div>
                  <h2 className="text-2xl font-serif font-semibold mb-2" style={{ color: 'var(--navy)' }}>
                    {product.title}
                  </h2>
                  <div className="text-3xl font-bold font-serif mb-4" style={{ color: 'var(--gold)' }}>
                    {product.price.toLocaleString('es-ES', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 })}
                  </div>
                  <p className="text-sm leading-relaxed mb-6" style={{ color: 'var(--sub)' }}>{product.desc}</p>
                  <ul className="space-y-2 mb-8">
                    {product.details.map((d) => (
                      <li key={d} className="flex items-start gap-2 text-sm" style={{ color: 'var(--text)' }}>
                        <span className="mt-1.5 w-1.5 h-1.5 rounded-full shrink-0" style={{ backgroundColor: 'var(--green)' }} />
                        {d}
                      </li>
                    ))}
                  </ul>
                </div>
                <div className="px-8 pb-8">
                  <Link
                    to="/contact"
                    className="block w-full text-center py-3 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90"
                    style={{ backgroundColor: 'var(--navy)' }}
                  >
                    Commander — {product.price} €
                  </Link>
                </div>
              </div>
            ))}
          </div>

          <p className="text-center text-xs mt-8" style={{ color: 'var(--sub)' }}>
            Paiement sécurisé par Stripe. Voir nos{' '}
            <Link to="/cgv" className="underline" style={{ color: 'var(--navy)' }}>conditions de vente</Link>{' '}
            et{' '}
            <Link to="/politique-de-retour" className="underline" style={{ color: 'var(--navy)' }}>politique de retour</Link>.
          </p>
        </div>
      </section>
    </>
  );
}
