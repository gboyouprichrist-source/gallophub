interface LogoProps {
  variant?: 'light' | 'dark';
  size?: 'sm' | 'md' | 'lg';
}

export function Logo({ variant = 'light', size = 'md' }: LogoProps) {
  const sizes = { sm: 28, md: 36, lg: 48 };
  const textSizes = { sm: 'text-xl', md: 'text-2xl', lg: 'text-3xl' };
  const s = sizes[size];

  const navyColor = variant === 'dark' ? '#FFFFFF' : '#1A3C5E';

  return (
    <div className="flex items-center gap-2">
      <svg width={s} height={s} viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect width="40" height="40" rx="6" fill="#1A3C5E" />
        {/* Horse silhouette */}
        <path
          d="M8 28 C8 28 10 22 14 20 C14 20 13 17 15 15 C17 13 20 13 21 14 C22 15 23 13 25 12 C27 11 29 12 29 14 C29 16 28 17 27 17 C28 18 30 20 30 23 C30 26 28 28 26 28 L8 28Z"
          fill="#C8A951"
        />
        <path d="M14 28 L14 32 L16 32 L16 28" fill="#C8A951" />
        <path d="M20 28 L20 32 L22 32 L22 28" fill="#C8A951" />
        <path d="M25 28 L25 32 L27 32 L27 28" fill="#C8A951" />
        <path d="M9 28 L9 32 L11 32 L11 28" fill="#C8A951" />
      </svg>
      <span className={`font-serif font-bold ${textSizes[size]} leading-none`}>
        <span style={{ color: navyColor }}>Gallop</span>
        <span style={{ color: '#C8A951' }}>Hub</span>
      </span>
    </div>
  );
}
