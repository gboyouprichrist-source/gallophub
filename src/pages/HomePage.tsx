import { Link } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { useTranslation } from 'react-i18next';
import { MessageCircle, Shield, Truck, Award, Star, ChevronRight } from 'lucide-react';
import { fetchHorses } from '../lib/supabase';
import { HorseCard } from '../components/horses/HorseCard';
import { HorseCardSkeleton } from '../components/ui/Skeleton';

const WA_NUMBER = import.meta.env.VITE_WHATSAPP_NUMBER || '34600000000';

const MOCK_HORSES = Array.from({ length: 6 }, (_, i) => ({
  id: `mock-${i}`,
  title: ['Andaluz Elegante', 'Lusitano Puro', 'PRE Dressage', 'Hispano-Árabe', 'KWPN Jumping', 'Criollo Western'][i],
  breed: ['Andaluz', 'Lusitano', 'PRE', 'Hispano-Árabe', 'KWPN', 'Criollo'][i],
  age: [5, 7, 4, 8, 6, 9][i],
  gender: (['mare', 'stallion', 'gelding', 'mare', 'gelding', 'stallion'] as const)[i],
  height_cm: [160, 158, 162, 155, 170, 152][i],
  color: ['Tordo', 'Bayo', 'Negro', 'Alazán', 'Castaño', 'Pinto'][i],
  discipline: [['dressage'], ['dressage', 'leisure'], ['dressage'], ['jumping', 'leisure'], ['jumping'], ['western']][i],
  description: 'Caballo de alta calidad, bien educado y con excelente carácter.',
  price: [15000, 22000, 18000, 12000, 35000, 9000][i],
  negotiable: [true, false, true, false, false, true][i],
  city: ['Sevilla', 'Lisboa', 'Madrid', 'Valencia', 'Barcelona', 'Córdoba'][i],
  country: ['España', 'Portugal', 'España', 'España', 'España', 'España'][i],
  pedigree: null,
  youtube_url: null,
  status: (['available', 'available', 'reserved', 'available', 'available', 'sold'] as const)[i],
  views: 0,
  created_at: new Date().toISOString(),
  horse_photos: [{
    id: `photo-${i}`,
    horse_id: `mock-${i}`,
    url: [
      'https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=600',
      'https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?w=600',
      'https://images.unsplash.com/photo-1566068256825-b0ec7d56a99e?w=600',
      'https://images.unsplash.com/photo-1488123046735-a09ee30e99fb?w=600',
      'https://images.unsplash.com/photo-1534073828943-f801091bb18c?w=600',
      'https://images.unsplash.com/photo-1449927007894-7a1ef1ab7b4b?w=600',
    ][i],
    is_main: true,
    display_order: 0,
  }],
}));

const TESTIMONIALS = [
  {
    name: 'María García',
    location: 'España',
    flag: 'es',
    text: 'Compré mi Andaluz de GallopHub y estoy encantada. El proceso fue transparente y el caballo tal como lo describieron.',
    avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=80',
    rating: 5,
  },
  {
    name: 'Jan van der Berg',
    location: 'Nederland',
    flag: 'nl',
    text: 'Uitstekende service! Het paard werd veilig geleverd en komt overeen met de beschrijving. Zeer aan te raden.',
    avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80',
    rating: 5,
  },
  {
    name: 'Sophie Dubois',
    location: 'Belgique',
    flag: 'be',
    text: 'J\'ai acheté un magnifique PRE via GallopHub. Communication parfaite, livraison rapide, cheval conforme.',
    avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=80',
    rating: 5,
  },
];

const WHY_CARDS = [
  {
    icon: Shield,
    title: 'Venta 100% directa',
    desc: 'Sin intermediarios. Trato directo con el propietario. Precios justos y transparentes.',
  },
  {
    icon: Award,
    title: '20+ años de experiencia',
    desc: 'Seleccionamos cada caballo con criterio. Conocemos las razas, disciplinas y necesidades de los compradores.',
  },
  {
    icon: Truck,
    title: 'Entrega en toda Europa',
    desc: 'Transportistas equinos homologados. Cobertura España, Países Bajos, Bélgica y más.',
  },
];

export function HomePage() {
  const { t } = useTranslation();

  const { data: horses, isLoading } = useQuery({
    queryKey: ['horses', 'latest'],
    queryFn: () => fetchHorses({ availableOnly: false }),
    select: (data) => (data.length > 0 ? data.slice(0, 6) : MOCK_HORSES),
    placeholderData: undefined,
  });

  const displayHorses = isLoading ? null : (horses && horses.length > 0 ? horses : MOCK_HORSES);

  return (
    <>
      {/* Meta */}
      <title>GallopHub — Venta directa de caballos en España | 30+ caballos</title>

      {/* HERO */}
      <section
        className="relative min-h-[90vh] flex items-center"
        style={{
          background: `linear-gradient(to right, rgba(26,60,94,0.92) 0%, rgba(26,60,94,0.4) 100%), url('https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=1920') center/cover no-repeat`,
        }}
      >
        <div className="max-w-7xl mx-auto px-4 w-full">
          <div className="max-w-2xl">
            {/* Badge */}
            <span
              className="inline-block text-xs font-semibold px-4 py-1.5 rounded-full mb-6"
              style={{ backgroundColor: 'var(--gold)', color: 'var(--navy)' }}
            >
              {t('hero.badge')}
            </span>

            {/* H1 */}
            <h1
              className="text-5xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6"
              style={{ color: 'white', fontFamily: 'Cormorant Garamond, serif' }}
            >
              {t('hero.title').split('\n').map((line, i) => (
                <span key={i}>
                  {line}
                  {i === 0 && <br />}
                </span>
              ))}
            </h1>

            <p className="text-lg text-white/80 mb-8 leading-relaxed">
              {t('hero.subtitle')}
            </p>

            <div className="flex flex-wrap gap-4">
              <Link
                to="/horses"
                className="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold transition-opacity hover:opacity-90"
                style={{ backgroundColor: 'var(--gold)', color: 'var(--navy)' }}
              >
                {t('hero.cta')}
                <ChevronRight size={16} />
              </Link>
              <a
                href={`https://wa.me/${WA_NUMBER}?text=${encodeURIComponent('Bonjour, je souhaite en savoir plus sur vos chevaux.')}`}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90"
                style={{ backgroundColor: '#25D366' }}
              >
                <MessageCircle size={16} />
                {t('hero.whatsapp')}
              </a>
            </div>
          </div>
        </div>
      </section>

      {/* STATS BAR */}
      <section style={{ backgroundColor: 'var(--navy)' }}>
        <div className="max-w-7xl mx-auto px-4 py-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
          {[
            { label: t('stats.horses'), value: '30+' },
            { label: t('stats.experience'), value: '20+' },
            { label: t('stats.direct'), value: '100%' },
            { label: t('stats.delivery'), value: 'EU' },
          ].map((s) => (
            <div key={s.label}>
              <div className="text-3xl font-bold font-serif" style={{ color: 'var(--gold)' }}>{s.value}</div>
              <div className="text-sm text-white/70 mt-1">{s.label}</div>
            </div>
          ))}
        </div>
      </section>

      {/* LATEST HORSES */}
      <section className="py-20">
        <div className="max-w-7xl mx-auto px-4">
          <div className="flex items-end justify-between mb-10">
            <div>
              <h2 className="text-4xl font-bold font-serif mb-2" style={{ color: 'var(--navy)' }}>
                {t('home.latestHorses')}
              </h2>
              <p className="text-sm" style={{ color: 'var(--sub)' }}>Selección actualizada regularmente</p>
            </div>
            <Link
              to="/horses"
              className="hidden md:inline-flex items-center gap-1.5 text-sm font-semibold transition-colors hover:opacity-80"
              style={{ color: 'var(--gold)' }}
            >
              {t('home.seeAll')} <ChevronRight size={15} />
            </Link>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {isLoading
              ? Array.from({ length: 6 }).map((_, i) => <HorseCardSkeleton key={i} />)
              : displayHorses?.map((horse) => <HorseCard key={horse.id} horse={horse} />)
            }
          </div>

          <div className="text-center mt-10 md:hidden">
            <Link
              to="/horses"
              className="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold text-white"
              style={{ backgroundColor: 'var(--navy)' }}
            >
              {t('home.seeAll')} <ChevronRight size={16} />
            </Link>
          </div>
        </div>
      </section>

      {/* WHY US */}
      <section className="py-20" style={{ backgroundColor: 'var(--muted)' }}>
        <div className="max-w-7xl mx-auto px-4">
          <h2 className="text-4xl font-bold font-serif text-center mb-12" style={{ color: 'var(--navy)' }}>
            {t('home.whyUs')}
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {WHY_CARDS.map((card) => (
              <div
                key={card.title}
                className="bg-white rounded-lg p-8 transition-all duration-200 hover:-translate-y-1"
                style={{ boxShadow: 'var(--shadow)' }}
                onMouseEnter={(e) => { (e.currentTarget as HTMLElement).style.boxShadow = 'var(--shadow-lg)'; }}
                onMouseLeave={(e) => { (e.currentTarget as HTMLElement).style.boxShadow = 'var(--shadow)'; }}
              >
                <div
                  className="w-12 h-12 rounded-lg flex items-center justify-center mb-5"
                  style={{ backgroundColor: 'var(--muted)' }}
                >
                  <card.icon size={24} style={{ color: 'var(--navy)' }} />
                </div>
                <h3 className="text-xl font-serif font-semibold mb-3" style={{ color: 'var(--navy)' }}>{card.title}</h3>
                <p className="text-sm leading-relaxed" style={{ color: 'var(--sub)' }}>{card.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* TESTIMONIALS */}
      <section className="py-20">
        <div className="max-w-7xl mx-auto px-4">
          <h2 className="text-4xl font-bold font-serif text-center mb-12" style={{ color: 'var(--navy)' }}>
            {t('home.testimonials')}
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {TESTIMONIALS.map((t_) => (
              <div
                key={t_.name}
                className="bg-white rounded-lg p-6 border"
                style={{ borderColor: 'var(--border)', boxShadow: 'var(--shadow)' }}
              >
                {/* Stars */}
                <div className="flex gap-1 mb-4">
                  {Array.from({ length: t_.rating }).map((_, i) => (
                    <Star key={i} size={14} fill="var(--gold)" style={{ color: 'var(--gold)' }} />
                  ))}
                </div>
                <p className="text-sm leading-relaxed mb-6" style={{ color: 'var(--text)' }}>"{t_.text}"</p>
                <div className="flex items-center gap-3">
                  <img
                    src={t_.avatar}
                    alt={t_.name}
                    className="w-10 h-10 rounded-full object-cover"
                  />
                  <div>
                    <div className="text-sm font-semibold" style={{ color: 'var(--text)' }}>{t_.name}</div>
                    <div className="flex items-center gap-1.5 text-xs" style={{ color: 'var(--sub)' }}>
                      <img src={`https://flagcdn.com/w20/${t_.flag}.png`} width="16" height="12" alt={t_.location} />
                      {t_.location}
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA BANNER */}
      <section
        className="py-20"
        style={{ background: 'linear-gradient(135deg, var(--navy) 0%, var(--sky) 100%)' }}
      >
        <div className="max-w-3xl mx-auto px-4 text-center">
          <h2 className="text-4xl font-bold font-serif text-white mb-4">{t('home.ctaTitle')}</h2>
          <p className="text-white/80 mb-8">{t('home.ctaSubtitle')}</p>
          <Link
            to="/contact"
            className="inline-flex items-center gap-2 px-8 py-4 rounded-lg text-sm font-semibold transition-opacity hover:opacity-90"
            style={{ backgroundColor: 'var(--gold)', color: 'var(--navy)' }}
          >
            {t('home.ctaBtn')} <ChevronRight size={16} />
          </Link>
        </div>
      </section>
    </>
  );
}
