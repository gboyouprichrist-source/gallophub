export type HorseGender = 'mare' | 'stallion' | 'gelding' | 'pony';
export type HorseStatus = 'available' | 'reserved' | 'sold';

export interface Horse {
  id: string;
  title: string;
  breed: string | null;
  age: number | null;
  gender: HorseGender | null;
  height_cm: number | null;
  color: string | null;
  discipline: string[] | null;
  description: string | null;
  price: number | null;
  negotiable: boolean;
  city: string | null;
  country: string | null;
  pedigree: string | null;
  youtube_url: string | null;
  status: HorseStatus;
  views: number;
  created_at: string;
  horse_photos?: HorsePhoto[];
}

export interface HorsePhoto {
  id: string;
  horse_id: string;
  url: string;
  is_main: boolean;
  display_order: number;
}

export interface ContactMessage {
  id?: string;
  horse_id?: string | null;
  name: string;
  email: string;
  phone?: string | null;
  subject?: string | null;
  message: string;
}

export interface HorseFilters {
  disciplines: string[];
  breed: string;
  gender: string;
  country: string;
  priceMin: number;
  priceMax: number;
  ageMin: number;
  ageMax: number;
  availableOnly: boolean;
}
