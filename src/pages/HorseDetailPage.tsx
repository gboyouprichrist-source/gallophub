import { useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { useTranslation } from 'react-i18next';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import {
  MapPin, Calendar, Ruler, Tag, MessageCircle,
  ChevronLeft, ExternalLink, Share2
} from 'lucide-react';
import { fetchHorseById, fetchSimilarHorses, getAllPhotos, getMainPhoto, submitContact } from '../lib/supabase';
import { HorseCard } from '../components/horses/HorseCard';
import { Skeleton } from '../components/ui/Skeleton';

const WA_NUMBER = import.meta.env.VITE_WHATSAPP_NUMBER || '34600000000';

const contactSchema = z.object({
  name: z.string().min(2, 'Minimum 2 caractères'),
  email: z.string().email('Email invalide'),
  phone: z.string().optional(),
  message: z.string().min(10, 'Minimum 10 caractères'),
});
type ContactForm = z.infer<typeof contactSchema>;

export function HorseDetailPage() {
  const { id } = useParams<{ id: string }>();
  const { t } = useTranslation();
  const [activePhoto, setActivePhoto] = useState(0);
  const [submitted, setSubmitted] = useState(false);
  const [submitError, setSubmitError] = useState('');

  const { data: horse, isLoading } = useQuery({
    queryKey: ['horse', id],
    queryFn: () => fetchHorseById(id!),
    enabled: !!id,
  });

  const { data: similar } = useQuery({
    queryKey: ['horses', 'similar', id],
    queryFn: () => horse ? fetchSimilarHorses(horse) : Promise.resolve([]),
    enabled: !!horse,
  });

  const { register, handleSubmit, formState: { errors, isSubmitting } } = useForm<ContactForm>({
    resolver: zodResolver(contactSchema),
  });

  const onSubmit = async (data: ContactForm) => {
    try {
      await submitContact({ ...data, horse_id: id });
      setSubmitted(true);
    } catch {
      setSubmitError(t('contact.error'));
    }
  };

  if (isLoading) {
    return (
      <div className="max-w-7xl mx-auto px-4 py-10">
        <Skeleton className="h-8 w-48 mb-6" />
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-10">
          <div className="lg:col-span-2">
            <Skeleton className="aspect-[16/9] w-full rounded-lg mb-4" />
            <div className="grid grid-cols-4 gap-2">
              {Array.from({ length: 4 }).map((_, i) => <Skeleton key={i} className="aspect-square rounded" />)}
            </div>
          </div>
          <div className="space-y-4">
            <Skeleton className="h-8 w-3/4" />
            <Skeleton className="h-6 w-1/2" />
            <Skeleton className="h-32 w-full" />
          </div>
        </div>
      </div>
    );
  }

  if (!horse) {
    return (
      <div className="max-w-7xl mx-auto px-4 py-20 text-center">
        <h1 className="text-3xl font-serif mb-4" style={{ color: 'var(--navy)' }}>Caballo no encontrado</h1>
        <Link to="/horses" className="text-sm underline" style={{ color: 'var(--gold)' }}>← Ver todos los caballos</Link>
      </div>
    );
  }

  const photos = getAllPhotos(horse);
  const mainPhoto = photos[activePhoto]?.url || getMainPhoto(horse);

  const statusMap = {
    available: { label: t('horse.available'), bg: '#10B981' },
    reserved: { label: t('horse.reserved'), bg: '#F59E0B' },
    sold: { label: t('horse.sold'), bg: '#EF4444' },
  };
  const status = statusMap[horse.status];

  const waMessage = encodeURIComponent(
    `${t('horse.whatsappMessage')}${horse.title} — ${window.location.href}`
  );

  // Schema.org Product JSON-LD
  const priceValidUntil = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
  const schemaOrg = {
    '@context': 'https://schema.org',
    '@type': 'Product',
    name: horse.title,
    description: horse.description || `${horse.breed} ${horse.age} ans — ${horse.discipline?.join(', ')}`,
    image: mainPhoto,
    offers: {
      '@type': 'Offer',
      priceCurrency: 'EUR',
      price: horse.price?.toString() || '0',
      availability: horse.status === 'available' ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut',
      priceValidUntil,
      shippingDetails: {
        '@type': 'OfferShippingDetails',
        shippingRate: { '@type': 'MonetaryAmount', value: '800', currency: 'EUR' },
        deliveryTime: {
          '@type': 'ShippingDeliveryTime',
          handlingTime: { '@type': 'QuantitativeValue', minValue: 1, maxValue: 3, unitCode: 'DAY' },
          transitTime: { '@type': 'QuantitativeValue', minValue: 5, maxValue: 15, unitCode: 'DAY' },
        },
      },
      hasMerchantReturnPolicy: {
        '@type': 'MerchantReturnPolicy',
        returnPolicyCategory: 'https://schema.org/MerchantReturnNotPermitted',
        merchantReturnDays: 30,
        returnMethod: 'https://schema.org/ReturnByMail',
        returnFees: 'https://schema.org/FreeReturn',
      },
    },
  };

  const specs = [
    { label: t('horse.breed'), value: horse.breed },
    { label: t('horse.age'), value: horse.age ? `${horse.age} ${t('horse.years')}` : null },
    { label: t('horse.gender'), value: horse.gender ? t(`horse.${horse.gender}`) : null },
    { label: t('horse.height'), value: horse.height_cm ? `${horse.height_cm} cm` : null },
    { label: t('horse.color'), value: horse.color },
    { label: t('horse.pedigree'), value: horse.pedigree },
  ].filter((s) => s.value);

  return (
    <>
      <script type="application/ld+json">{JSON.stringify(schemaOrg)}</script>
      <title>{horse.title} — {horse.breed} — GallopHub</title>
      <meta name="description" content={`${horse.title}, ${horse.breed}, ${horse.age} ans. ${horse.price?.toLocaleString('es-ES')} €. ${horse.discipline?.join(', ')}.`} />

      <div className="max-w-7xl mx-auto px-4 py-8 pb-28 lg:pb-10">
        {/* Breadcrumb */}
        <Link
          to="/horses"
          className="inline-flex items-center gap-1.5 text-sm mb-6 hover:opacity-80 transition-opacity"
          style={{ color: 'var(--sub)' }}
        >
          <ChevronLeft size={14} /> Todos los caballos
        </Link>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-10">
          {/* Left — gallery + description */}
          <div className="lg:col-span-2">
            {/* Main photo */}
            <div className="relative rounded-lg overflow-hidden bg-gray-100 mb-3" style={{ aspectRatio: '16/9' }}>
              <img
                src={mainPhoto}
                alt={horse.title}
                className="w-full h-full object-cover cursor-zoom-in"
              />
              <span
                className="absolute top-4 left-4 text-sm font-semibold px-3 py-1 rounded-full text-white"
                style={{ backgroundColor: status.bg }}
              >
                {status.label}
              </span>
            </div>

            {/* Thumbnails */}
            {photos.length > 1 && (
              <div className="grid grid-cols-4 gap-2 mb-8">
                {photos.map((photo, i) => (
                  <button
                    key={photo.id}
                    onClick={() => setActivePhoto(i)}
                    className="aspect-square rounded-lg overflow-hidden border-2 transition-all"
                    style={{ borderColor: i === activePhoto ? 'var(--gold)' : 'transparent' }}
                  >
                    <img src={photo.url} alt="" className="w-full h-full object-cover" />
                  </button>
                ))}
              </div>
            )}

            {/* YouTube embed */}
            {horse.youtube_url && (
              <div className="mb-8 rounded-lg overflow-hidden" style={{ aspectRatio: '16/9' }}>
                <iframe
                  src={horse.youtube_url.replace('watch?v=', 'embed/')}
                  className="w-full h-full"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowFullScreen
                />
              </div>
            )}

            {/* Specs grid */}
            <div className="mb-8">
              <h2 className="text-2xl font-serif font-semibold mb-5" style={{ color: 'var(--navy)' }}>Características</h2>
              <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
                {specs.map((spec) => (
                  <div
                    key={spec.label}
                    className="rounded-lg p-4"
                    style={{ backgroundColor: 'var(--muted)' }}
                  >
                    <div className="text-xs uppercase tracking-wider mb-1" style={{ color: 'var(--sub)' }}>{spec.label}</div>
                    <div className="text-sm font-semibold" style={{ color: 'var(--text)' }}>{spec.value}</div>
                  </div>
                ))}
                {horse.discipline && horse.discipline.length > 0 && (
                  <div className="rounded-lg p-4" style={{ backgroundColor: 'var(--muted)' }}>
                    <div className="text-xs uppercase tracking-wider mb-1" style={{ color: 'var(--sub)' }}>{t('horse.discipline')}</div>
                    <div className="flex flex-wrap gap-1">
                      {horse.discipline.map((d) => (
                        <span key={d} className="text-xs capitalize font-semibold" style={{ color: 'var(--navy)' }}>{d}</span>
                      ))}
                    </div>
                  </div>
                )}
                {horse.city && (
                  <div className="rounded-lg p-4" style={{ backgroundColor: 'var(--muted)' }}>
                    <div className="text-xs uppercase tracking-wider mb-1" style={{ color: 'var(--sub)' }}>{t('horse.location')}</div>
                    <div className="text-sm font-semibold flex items-center gap-1.5" style={{ color: 'var(--text)' }}>
                      <MapPin size={12} /> {horse.city}, {horse.country}
                    </div>
                  </div>
                )}
              </div>
            </div>

            {/* Description */}
            {horse.description && (
              <div className="mb-8">
                <h2 className="text-2xl font-serif font-semibold mb-4" style={{ color: 'var(--navy)' }}>Descripción</h2>
                <p className="text-sm leading-relaxed whitespace-pre-wrap" style={{ color: 'var(--text)' }}>{horse.description}</p>
              </div>
            )}

            {/* GMC info */}
            <div className="rounded-lg p-5 border text-sm space-y-2" style={{ borderColor: 'var(--border)', backgroundColor: 'var(--muted)' }}>
              <div className="flex items-center gap-2" style={{ color: 'var(--sub)' }}>
                <Ruler size={14} /> {t('horse.delivery')}
              </div>
              <div className="flex items-center gap-2" style={{ color: 'var(--sub)' }}>
                <Tag size={14} /> Paiement : virement SEPA ou carte via Stripe
              </div>
              <Link
                to="/politique-de-retour"
                className="flex items-center gap-2 hover:underline"
                style={{ color: 'var(--navy)' }}
              >
                <ExternalLink size={14} /> {t('horse.returnPolicy')}
              </Link>
            </div>
          </div>

          {/* Sidebar */}
          <div className="lg:col-span-1">
            <div className="sticky top-24 space-y-5">
              {/* Price card */}
              <div
                className="rounded-lg p-6 border"
                style={{ borderColor: 'var(--border)', boxShadow: 'var(--shadow-lg)' }}
              >
                <h1 className="text-2xl font-serif font-semibold mb-1" style={{ color: 'var(--navy)' }}>
                  {horse.title}
                </h1>
                <div className="flex items-center gap-2 text-sm mb-4" style={{ color: 'var(--sub)' }}>
                  {horse.age && <span><Calendar size={12} className="inline mr-1" />{horse.age} {t('horse.years')}</span>}
                  {horse.city && <span><MapPin size={12} className="inline mr-1" />{horse.city}</span>}
                </div>

                {horse.price ? (
                  <div className="mb-4">
                    <span className="text-3xl font-bold font-serif" style={{ color: 'var(--gold)' }}>
                      {horse.price.toLocaleString('es-ES', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 })}
                    </span>
                    {horse.negotiable && (
                      <span className="ml-2 text-xs" style={{ color: 'var(--sub)' }}>{t('horse.negotiable')}</span>
                    )}
                  </div>
                ) : (
                  <div className="text-lg font-serif mb-4" style={{ color: 'var(--sub)' }}>Prix sur demande</div>
                )}

                {/* WhatsApp CTA */}
                <a
                  href={`https://wa.me/${WA_NUMBER}?text=${waMessage}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex items-center justify-center gap-2 w-full py-3 rounded-lg text-sm font-semibold text-white mb-3 transition-opacity hover:opacity-90"
                  style={{ backgroundColor: '#25D366' }}
                >
                  <MessageCircle size={16} /> WhatsApp
                </a>

                {/* Share */}
                <button
                  onClick={() => navigator.share?.({ title: horse.title, url: window.location.href })}
                  className="flex items-center justify-center gap-2 w-full py-2.5 rounded-lg text-sm font-medium border transition-colors hover:bg-gray-50"
                  style={{ borderColor: 'var(--border)', color: 'var(--sub)' }}
                >
                  <Share2 size={14} /> Partager
                </button>
              </div>

              {/* Contact form */}
              <div
                className="rounded-lg p-6 border"
                style={{ borderColor: 'var(--border)' }}
              >
                <h3 className="text-lg font-serif font-semibold mb-4" style={{ color: 'var(--navy)' }}>
                  {t('horse.contactSeller')}
                </h3>

                {submitted ? (
                  <p className="text-sm text-center py-4" style={{ color: '#10B981' }}>{t('contact.success')}</p>
                ) : (
                  <form onSubmit={handleSubmit(onSubmit)} className="space-y-3">
                    <div>
                      <input
                        {...register('name')}
                        placeholder={t('contact.name')}
                        className="w-full text-sm border rounded-lg px-3 py-2"
                        style={{ borderColor: errors.name ? '#EF4444' : 'var(--border)' }}
                      />
                      {errors.name && <p className="text-xs mt-1" style={{ color: '#EF4444' }}>{errors.name.message}</p>}
                    </div>
                    <div>
                      <input
                        {...register('email')}
                        type="email"
                        placeholder={t('contact.email')}
                        className="w-full text-sm border rounded-lg px-3 py-2"
                        style={{ borderColor: errors.email ? '#EF4444' : 'var(--border)' }}
                      />
                      {errors.email && <p className="text-xs mt-1" style={{ color: '#EF4444' }}>{errors.email.message}</p>}
                    </div>
                    <div>
                      <input
                        {...register('phone')}
                        type="tel"
                        placeholder={t('contact.phone')}
                        className="w-full text-sm border rounded-lg px-3 py-2"
                        style={{ borderColor: 'var(--border)' }}
                      />
                    </div>
                    <div>
                      <textarea
                        {...register('message')}
                        placeholder={t('contact.message')}
                        rows={4}
                        className="w-full text-sm border rounded-lg px-3 py-2 resize-none"
                        style={{ borderColor: errors.message ? '#EF4444' : 'var(--border)' }}
                        defaultValue={`Me interesa el caballo ${horse.title}. ¿Podría darme más información?`}
                      />
                      {errors.message && <p className="text-xs mt-1" style={{ color: '#EF4444' }}>{errors.message.message}</p>}
                    </div>
                    {submitError && <p className="text-xs" style={{ color: '#EF4444' }}>{submitError}</p>}
                    <button
                      type="submit"
                      disabled={isSubmitting}
                      className="w-full py-3 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 disabled:opacity-60"
                      style={{ backgroundColor: 'var(--navy)' }}
                    >
                      {isSubmitting ? 'Envoi...' : t('contact.send')}
                    </button>
                  </form>
                )}
              </div>
            </div>
          </div>
        </div>

        {/* Similar horses */}
        {similar && similar.length > 0 && (
          <div className="mt-16">
            <h2 className="text-3xl font-serif font-semibold mb-8" style={{ color: 'var(--navy)' }}>
              Caballos similares
            </h2>
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {similar.map((h) => <HorseCard key={h.id} horse={h} />)}
            </div>
          </div>
        )}
      </div>
    </>
  );
}
