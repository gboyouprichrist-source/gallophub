import { Link } from 'react-router-dom';
import { MapPin, Ruler, Calendar } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import type { Horse } from '../../types';
import { getMainPhoto } from '../../lib/supabase';

interface HorseCardProps {
  horse: Horse;
}

const STATUS_STYLES: Record<string, { bg: string; text: string; label: string }> = {
  available: { bg: '#10B981', text: 'white', label: 'horse.available' },
  reserved: { bg: '#F59E0B', text: 'white', label: 'horse.reserved' },
  sold: { bg: '#EF4444', text: 'white', label: 'horse.sold' },
};

export function HorseCard({ horse }: HorseCardProps) {
  const { t } = useTranslation();
  const mainPhoto = getMainPhoto(horse);
  const status = STATUS_STYLES[horse.status] || STATUS_STYLES.available;

  return (
    <Link
      to={`/horses/${horse.id}`}
      className="group block rounded-lg overflow-hidden border bg-white transition-all duration-200 hover:-translate-y-1"
      style={{ borderColor: 'var(--border)', boxShadow: 'var(--shadow)' }}
      onMouseEnter={(e) => {
        (e.currentTarget as HTMLElement).style.boxShadow = 'var(--shadow-lg)';
      }}
      onMouseLeave={(e) => {
        (e.currentTarget as HTMLElement).style.boxShadow = 'var(--shadow)';
      }}
    >
      {/* Image */}
      <div className="relative aspect-[4/3] overflow-hidden bg-gray-100">
        <img
          src={mainPhoto}
          alt={horse.title}
          loading="lazy"
          className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
        />
        {/* Status badge */}
        <span
          className="absolute top-3 left-3 text-xs font-semibold px-2 py-1 rounded-full"
          style={{ backgroundColor: status.bg, color: status.text }}
        >
          {t(status.label)}
        </span>
        {/* Country flag */}
        {horse.country && (
          <span className="absolute top-3 right-3">
            <img
              src={`https://flagcdn.com/w20/${getCountryCode(horse.country)}.png`}
              width="20"
              height="15"
              alt={horse.country}
              className="rounded-sm shadow"
            />
          </span>
        )}
      </div>

      {/* Content */}
      <div className="p-4">
        <h3 className="font-serif text-lg font-semibold leading-tight mb-1 group-hover:text-[var(--navy)] transition-colors" style={{ color: 'var(--text)' }}>
          {horse.title}
        </h3>

        <p className="text-sm mb-3" style={{ color: 'var(--sub)' }}>
          {horse.breed}
          {horse.gender && ` · ${t(`horse.${horse.gender}`)}`}
        </p>

        {/* Disciplines */}
        {horse.discipline && horse.discipline.length > 0 && (
          <div className="flex flex-wrap gap-1.5 mb-3">
            {horse.discipline.slice(0, 3).map((d) => (
              <span
                key={d}
                className="text-xs px-2 py-0.5 rounded-full capitalize"
                style={{ backgroundColor: 'var(--muted)', color: 'var(--navy)' }}
              >
                {d}
              </span>
            ))}
          </div>
        )}

        {/* Meta */}
        <div className="flex items-center gap-3 text-xs mb-4" style={{ color: 'var(--sub)' }}>
          {horse.age && (
            <span className="flex items-center gap-1">
              <Calendar size={12} />
              {horse.age} {t('horse.years')}
            </span>
          )}
          {horse.height_cm && (
            <span className="flex items-center gap-1">
              <Ruler size={12} />
              {horse.height_cm} cm
            </span>
          )}
          {horse.city && (
            <span className="flex items-center gap-1">
              <MapPin size={12} />
              {horse.city}
            </span>
          )}
        </div>

        {/* Price */}
        <div className="flex items-center justify-between">
          {horse.price ? (
            <span className="font-bold text-lg font-serif" style={{ color: 'var(--gold)' }}>
              {horse.price.toLocaleString('es-ES', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 })}
              {horse.negotiable && <span className="text-xs font-normal ml-1" style={{ color: 'var(--sub)' }}>neg.</span>}
            </span>
          ) : (
            <span className="text-sm font-medium" style={{ color: 'var(--sub)' }}>Sur demande</span>
          )}
        </div>
      </div>
    </Link>
  );
}

function getCountryCode(country: string): string {
  const map: Record<string, string> = {
    'España': 'es', 'Spain': 'es',
    'Países Bajos': 'nl', 'Netherlands': 'nl', 'Nederland': 'nl',
    'Bélgica': 'be', 'Belgium': 'be', 'Belgique': 'be',
    'Francia': 'fr', 'France': 'fr',
    'Alemania': 'de', 'Germany': 'de', 'Deutschland': 'de',
    'Portugal': 'pt',
  };
  return map[country] || 'es';
}
