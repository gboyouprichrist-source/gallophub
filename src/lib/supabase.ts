import { createClient } from '@supabase/supabase-js';
import type { Horse, HorsePhoto, ContactMessage } from '../types';

const supabaseUrl = import.meta.env.VITE_SUPABASE_URL as string;
const supabaseAnonKey = import.meta.env.VITE_SUPABASE_ANON_KEY as string;

export const supabase = createClient(supabaseUrl, supabaseAnonKey);

export async function fetchHorses(filters?: {
  disciplines?: string[];
  breed?: string;
  gender?: string;
  country?: string;
  priceMin?: number;
  priceMax?: number;
  ageMin?: number;
  ageMax?: number;
  availableOnly?: boolean;
}): Promise<Horse[]> {
  let query = supabase
    .from('horses')
    .select('*, horse_photos(*)')
    .order('created_at', { ascending: false });

  if (filters?.availableOnly) {
    query = query.eq('status', 'available');
  }
  if (filters?.gender) {
    query = query.eq('gender', filters.gender);
  }
  if (filters?.country) {
    query = query.eq('country', filters.country);
  }
  if (filters?.breed) {
    query = query.ilike('breed', `%${filters.breed}%`);
  }
  if (filters?.priceMin !== undefined && filters.priceMin > 0) {
    query = query.gte('price', filters.priceMin);
  }
  if (filters?.priceMax !== undefined && filters.priceMax > 0) {
    query = query.lte('price', filters.priceMax);
  }
  if (filters?.ageMin !== undefined && filters.ageMin > 0) {
    query = query.gte('age', filters.ageMin);
  }
  if (filters?.ageMax !== undefined && filters.ageMax > 0) {
    query = query.lte('age', filters.ageMax);
  }

  const { data, error } = await query;
  if (error) throw error;

  let result = (data as Horse[]) || [];

  if (filters?.disciplines && filters.disciplines.length > 0) {
    result = result.filter((h) =>
      h.discipline?.some((d) => filters.disciplines!.includes(d))
    );
  }

  return result;
}

export async function fetchHorseById(id: string): Promise<Horse | null> {
  const { data, error } = await supabase
    .from('horses')
    .select('*, horse_photos(*)')
    .eq('id', id)
    .single();

  if (error) return null;

  // Increment views (fire-and-forget)
  supabase.rpc('increment_views', { horse_id: id }).then(() => null, () => null);

  return data as Horse;
}

export async function fetchSimilarHorses(horse: Horse, limit = 3): Promise<Horse[]> {
  const { data } = await supabase
    .from('horses')
    .select('*, horse_photos(*)')
    .neq('id', horse.id)
    .eq('status', 'available')
    .limit(limit);

  return (data as Horse[]) || [];
}

export async function submitContact(msg: ContactMessage): Promise<void> {
  const { error } = await supabase.from('contact_messages').insert(msg);
  if (error) throw error;
}

export function getMainPhoto(horse: Horse): string {
  const photos = horse.horse_photos || [];
  const main = photos.find((p: HorsePhoto) => p.is_main);
  return (main || photos[0])?.url || 'https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=800';
}

export function getAllPhotos(horse: Horse): HorsePhoto[] {
  return (horse.horse_photos || []).sort(
    (a: HorsePhoto, b: HorsePhoto) => a.display_order - b.display_order
  );
}
