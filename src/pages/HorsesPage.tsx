import { useState, useEffect } from 'react';
import { useSearchParams } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { useTranslation } from 'react-i18next';
import { Filter, SlidersHorizontal, X } from 'lucide-react';
import { fetchHorses } from '../lib/supabase';
import { HorseCard } from '../components/horses/HorseCard';
import { HorseCardSkeleton } from '../components/ui/Skeleton';
import type { HorseFilters } from '../types';

const DISCIPLINES = ['dressage', 'jumping', 'western', 'leisure', 'pony', 'endurance', 'polo'];
const BREEDS = ['Andaluz', 'Lusitano', 'PRE', 'KWPN', 'Hispano-Árabe', 'Criollo', 'Quarter Horse', 'Appaloosa', 'Warmblood', 'Árabe'];
const COUNTRIES = ['España', 'Portugal', 'Países Bajos', 'Bélgica', 'Francia', 'Alemania'];

const DEFAULT_FILTERS: HorseFilters = {
  disciplines: [],
  breed: '',
  gender: '',
  country: '',
  priceMin: 0,
  priceMax: 0,
  ageMin: 0,
  ageMax: 0,
  availableOnly: false,
};

export function HorsesPage() {
  const { t } = useTranslation();
  const [searchParams] = useSearchParams();
  const [filters, setFilters] = useState<HorseFilters>(() => {
    const disc = searchParams.get('discipline');
    return { ...DEFAULT_FILTERS, disciplines: disc ? [disc] : [] };
  });
  const [sidebarOpen, setSidebarOpen] = useState(false);

  useEffect(() => {
    const disc = searchParams.get('discipline');
    if (disc) setFilters((f) => ({ ...f, disciplines: [disc] }));
  }, [searchParams]);

  const { data: horses, isLoading } = useQuery({
    queryKey: ['horses', filters],
    queryFn: () => fetchHorses(filters),
  });

  const toggleDiscipline = (d: string) => {
    setFilters((f) => ({
      ...f,
      disciplines: f.disciplines.includes(d)
        ? f.disciplines.filter((x) => x !== d)
        : [...f.disciplines, d],
    }));
  };

  const resetFilters = () => setFilters(DEFAULT_FILTERS);

  const activeCount = [
    filters.disciplines.length > 0,
    !!filters.breed,
    !!filters.gender,
    !!filters.country,
    filters.priceMin > 0,
    filters.priceMax > 0,
    filters.ageMin > 0,
    filters.ageMax > 0,
    filters.availableOnly,
  ].filter(Boolean).length;

  const FilterPanel = () => (
    <aside className="space-y-6">
      <div className="flex items-center justify-between">
        <h3 className="font-semibold" style={{ color: 'var(--navy)' }}>Filtros</h3>
        {activeCount > 0 && (
          <button onClick={resetFilters} className="text-xs flex items-center gap-1" style={{ color: 'var(--sub)' }}>
            <X size={12} /> Limpiar ({activeCount})
          </button>
        )}
      </div>

      {/* Available only */}
      <label className="flex items-center gap-2 cursor-pointer">
        <input
          type="checkbox"
          checked={filters.availableOnly}
          onChange={(e) => setFilters((f) => ({ ...f, availableOnly: e.target.checked }))}
          className="rounded"
          style={{ accentColor: 'var(--navy)' }}
        />
        <span className="text-sm" style={{ color: 'var(--text)' }}>Solo disponibles</span>
      </label>

      {/* Discipline */}
      <div>
        <h4 className="text-xs font-semibold uppercase tracking-wider mb-3" style={{ color: 'var(--sub)' }}>Disciplina</h4>
        <div className="space-y-2">
          {DISCIPLINES.map((d) => (
            <label key={d} className="flex items-center gap-2 cursor-pointer">
              <input
                type="checkbox"
                checked={filters.disciplines.includes(d)}
                onChange={() => toggleDiscipline(d)}
                style={{ accentColor: 'var(--navy)' }}
              />
              <span className="text-sm capitalize" style={{ color: 'var(--text)' }}>{d}</span>
            </label>
          ))}
        </div>
      </div>

      {/* Breed */}
      <div>
        <h4 className="text-xs font-semibold uppercase tracking-wider mb-3" style={{ color: 'var(--sub)' }}>Raza</h4>
        <select
          value={filters.breed}
          onChange={(e) => setFilters((f) => ({ ...f, breed: e.target.value }))}
          className="w-full text-sm border rounded-lg px-3 py-2 bg-white"
          style={{ borderColor: 'var(--border)', color: 'var(--text)' }}
        >
          <option value="">Todas las razas</option>
          {BREEDS.map((b) => <option key={b} value={b}>{b}</option>)}
        </select>
      </div>

      {/* Gender */}
      <div>
        <h4 className="text-xs font-semibold uppercase tracking-wider mb-3" style={{ color: 'var(--sub)' }}>Sexo</h4>
        <select
          value={filters.gender}
          onChange={(e) => setFilters((f) => ({ ...f, gender: e.target.value }))}
          className="w-full text-sm border rounded-lg px-3 py-2 bg-white"
          style={{ borderColor: 'var(--border)', color: 'var(--text)' }}
        >
          <option value="">Todos</option>
          <option value="mare">{t('horse.mare')}</option>
          <option value="stallion">{t('horse.stallion')}</option>
          <option value="gelding">{t('horse.gelding')}</option>
          <option value="pony">{t('horse.pony')}</option>
        </select>
      </div>

      {/* Country */}
      <div>
        <h4 className="text-xs font-semibold uppercase tracking-wider mb-3" style={{ color: 'var(--sub)' }}>País</h4>
        <select
          value={filters.country}
          onChange={(e) => setFilters((f) => ({ ...f, country: e.target.value }))}
          className="w-full text-sm border rounded-lg px-3 py-2 bg-white"
          style={{ borderColor: 'var(--border)', color: 'var(--text)' }}
        >
          <option value="">Todos los países</option>
          {COUNTRIES.map((c) => <option key={c} value={c}>{c}</option>)}
        </select>
      </div>

      {/* Price range */}
      <div>
        <h4 className="text-xs font-semibold uppercase tracking-wider mb-3" style={{ color: 'var(--sub)' }}>Precio (€)</h4>
        <div className="flex gap-2">
          <input
            type="number"
            placeholder="Min"
            value={filters.priceMin || ''}
            onChange={(e) => setFilters((f) => ({ ...f, priceMin: Number(e.target.value) }))}
            className="w-full text-sm border rounded-lg px-3 py-2"
            style={{ borderColor: 'var(--border)' }}
          />
          <input
            type="number"
            placeholder="Max"
            value={filters.priceMax || ''}
            onChange={(e) => setFilters((f) => ({ ...f, priceMax: Number(e.target.value) }))}
            className="w-full text-sm border rounded-lg px-3 py-2"
            style={{ borderColor: 'var(--border)' }}
          />
        </div>
      </div>

      {/* Age range */}
      <div>
        <h4 className="text-xs font-semibold uppercase tracking-wider mb-3" style={{ color: 'var(--sub)' }}>Edad</h4>
        <div className="flex gap-2">
          <input
            type="number"
            placeholder="Min"
            value={filters.ageMin || ''}
            onChange={(e) => setFilters((f) => ({ ...f, ageMin: Number(e.target.value) }))}
            className="w-full text-sm border rounded-lg px-3 py-2"
            style={{ borderColor: 'var(--border)' }}
          />
          <input
            type="number"
            placeholder="Max"
            value={filters.ageMax || ''}
            onChange={(e) => setFilters((f) => ({ ...f, ageMax: Number(e.target.value) }))}
            className="w-full text-sm border rounded-lg px-3 py-2"
            style={{ borderColor: 'var(--border)' }}
          />
        </div>
      </div>
    </aside>
  );

  return (
    <>
      <title>Caballos en venta — GallopHub | 30+ caballos disponibles</title>

      {/* Page hero */}
      <div className="py-12 border-b" style={{ backgroundColor: 'var(--muted)', borderColor: 'var(--border)' }}>
        <div className="max-w-7xl mx-auto px-4">
          <h1 className="text-4xl font-bold font-serif mb-2" style={{ color: 'var(--navy)' }}>Caballos en venta</h1>
          <p className="text-sm" style={{ color: 'var(--sub)' }}>Más de 30 caballos disponibles · Todas las razas · Entrega en Europa</p>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 py-10 pb-24 lg:pb-10">
        {/* Mobile filter toggle */}
        <div className="flex items-center justify-between mb-6 lg:hidden">
          <p className="text-sm" style={{ color: 'var(--sub)' }}>
            {isLoading ? '...' : `${horses?.length || 0} caballos`}
          </p>
          <button
            onClick={() => setSidebarOpen(!sidebarOpen)}
            className="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg border"
            style={{ borderColor: 'var(--border)', color: 'var(--navy)' }}
          >
            <SlidersHorizontal size={15} />
            Filtros {activeCount > 0 && `(${activeCount})`}
          </button>
        </div>

        {/* Mobile sidebar drawer */}
        {sidebarOpen && (
          <div className="fixed inset-0 z-50 lg:hidden">
            <div className="absolute inset-0 bg-black/30" onClick={() => setSidebarOpen(false)} />
            <div className="absolute right-0 top-0 h-full w-80 bg-white shadow-xl overflow-y-auto p-6">
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-lg font-serif font-semibold" style={{ color: 'var(--navy)' }}>
                  <Filter size={18} className="inline mr-2" />
                  Filtros
                </h2>
                <button onClick={() => setSidebarOpen(false)}>
                  <X size={20} style={{ color: 'var(--sub)' }} />
                </button>
              </div>
              <FilterPanel />
            </div>
          </div>
        )}

        <div className="flex gap-10">
          {/* Desktop sidebar */}
          <div className="hidden lg:block w-64 shrink-0">
            <div className="sticky top-24 bg-white rounded-lg border p-6" style={{ borderColor: 'var(--border)' }}>
              <FilterPanel />
            </div>
          </div>

          {/* Grid */}
          <div className="flex-1">
            <div className="hidden lg:flex items-center justify-between mb-6">
              <p className="text-sm" style={{ color: 'var(--sub)' }}>
                {isLoading ? 'Cargando...' : `${horses?.length || 0} caballos encontrados`}
              </p>
            </div>

            {isLoading ? (
              <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                {Array.from({ length: 6 }).map((_, i) => <HorseCardSkeleton key={i} />)}
              </div>
            ) : horses && horses.length > 0 ? (
              <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                {horses.map((horse) => <HorseCard key={horse.id} horse={horse} />)}
              </div>
            ) : (
              <div className="text-center py-20">
                <p className="text-lg font-serif mb-2" style={{ color: 'var(--navy)' }}>No se encontraron caballos</p>
                <p className="text-sm mb-6" style={{ color: 'var(--sub)' }}>Prueba con otros filtros</p>
                <button
                  onClick={resetFilters}
                  className="px-6 py-3 rounded-lg text-sm font-semibold text-white"
                  style={{ backgroundColor: 'var(--navy)' }}
                >
                  Ver todos los caballos
                </button>
              </div>
            )}
          </div>
        </div>
      </div>
    </>
  );
}
