import { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Menu, X, MessageCircle } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import { Logo } from '../ui/Logo';

const LANGS = [
  { code: 'es', flag: 'es', label: 'Español' },
  { code: 'nl', flag: 'nl', label: 'Nederlands' },
  { code: 'fr', flag: 'fr', label: 'Français' },
];

const WA_NUMBER = import.meta.env.VITE_WHATSAPP_NUMBER || '34600000000';

export function Navbar() {
  const { t, i18n } = useTranslation();
  const location = useLocation();
  const [scrolled, setScrolled] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);

  useEffect(() => {
    const handler = () => setScrolled(window.scrollY > 10);
    window.addEventListener('scroll', handler);
    return () => window.removeEventListener('scroll', handler);
  }, []);

  useEffect(() => { setMenuOpen(false); }, [location.pathname]);

  const changeLang = (code: string) => {
    i18n.changeLanguage(code);
    localStorage.setItem('lang', code);
  };

  const navLinks = [
    { to: '/horses', label: t('nav.horses') },
    { to: '/horses?discipline=dressage', label: t('nav.dressage') },
    { to: '/horses?discipline=jumping', label: t('nav.jumping') },
    { to: '/horses?discipline=western', label: t('nav.western') },
    { to: '/horses?discipline=leisure', label: t('nav.leisure') },
    { to: '/horses?discipline=pony', label: t('nav.ponies') },
    { to: '/shop', label: t('nav.shop') },
  ];

  return (
    <>
      {/* Top bar */}
      <div className="text-white text-xs py-1.5 px-4 text-center hidden md:block" style={{ backgroundColor: 'var(--navy)' }}>
        Venta directa · 30+ caballos disponibles · España · Entrega Europa
      </div>

      {/* Main navbar */}
      <nav
        className={`sticky top-0 z-40 transition-shadow duration-200 ${scrolled ? 'shadow-md' : ''}`}
        style={{ backgroundColor: 'rgba(250,250,248,0.97)', backdropFilter: 'blur(8px)' }}
      >
        <div className="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
          <Link to="/" aria-label="GallopHub accueil">
            <Logo variant="light" size="md" />
          </Link>

          {/* Desktop nav */}
          <ul className="hidden lg:flex items-center gap-6 text-sm font-medium" style={{ color: 'var(--text)' }}>
            {navLinks.map((link) => (
              <li key={link.to}>
                <Link
                  to={link.to}
                  className="hover:text-[var(--navy)] transition-colors duration-200 pb-1"
                  style={{
                    borderBottom: location.pathname === link.to ? '2px solid var(--gold)' : '2px solid transparent',
                    color: location.pathname === link.to ? 'var(--navy)' : undefined,
                  }}
                >
                  {link.label}
                </Link>
              </li>
            ))}
          </ul>

          {/* Right side */}
          <div className="flex items-center gap-3">
            {/* Language switcher */}
            <div className="hidden md:flex items-center gap-1.5">
              {LANGS.map((lang) => (
                <button
                  key={lang.code}
                  onClick={() => changeLang(lang.code)}
                  className="rounded overflow-hidden transition-opacity hover:opacity-80"
                  style={{ opacity: i18n.language === lang.code ? 1 : 0.5 }}
                  aria-label={lang.label}
                >
                  <img
                    src={`https://flagcdn.com/w20/${lang.flag}.png`}
                    width="20"
                    height="15"
                    alt={lang.label}
                    className="block"
                  />
                </button>
              ))}
            </div>

            {/* WhatsApp */}
            <a
              href={`https://wa.me/${WA_NUMBER}`}
              target="_blank"
              rel="noopener noreferrer"
              className="hidden md:flex items-center gap-1.5 text-sm font-medium px-3 py-1.5 rounded-lg transition-colors"
              style={{ color: '#25D366', border: '1px solid #25D366' }}
            >
              <MessageCircle size={15} />
              WhatsApp
            </a>

            {/* CTA */}
            <Link
              to="/contact"
              className="hidden md:inline-flex items-center text-sm font-semibold px-4 py-2 rounded-lg text-white transition-opacity hover:opacity-90"
              style={{ backgroundColor: 'var(--gold)' }}
            >
              {t('nav.contact')}
            </Link>

            {/* Mobile menu toggle */}
            <button
              onClick={() => setMenuOpen(!menuOpen)}
              className="lg:hidden p-2"
              style={{ color: 'var(--navy)' }}
              aria-label="Menu"
            >
              {menuOpen ? <X size={22} /> : <Menu size={22} />}
            </button>
          </div>
        </div>

        {/* Mobile drawer */}
        {menuOpen && (
          <div
            className="fixed inset-0 z-30 lg:hidden"
            style={{ top: '64px' }}
          >
            <div className="absolute inset-0 bg-black/30" onClick={() => setMenuOpen(false)} />
            <div
              className="absolute right-0 top-0 h-full w-72 shadow-xl py-6 px-6 flex flex-col gap-4"
              style={{ backgroundColor: 'var(--bg)' }}
            >
              {navLinks.map((link) => (
                <Link
                  key={link.to}
                  to={link.to}
                  className="text-base font-medium py-2 border-b transition-colors hover:text-[var(--navy)]"
                  style={{ borderColor: 'var(--border)', color: 'var(--text)' }}
                >
                  {link.label}
                </Link>
              ))}
              <Link to="/about" className="text-base font-medium py-2 border-b" style={{ borderColor: 'var(--border)', color: 'var(--text)' }}>
                {t('nav.about')}
              </Link>
              <Link to="/contact" className="text-base font-medium py-2 border-b" style={{ borderColor: 'var(--border)', color: 'var(--text)' }}>
                {t('nav.contact')}
              </Link>

              {/* Lang switcher mobile */}
              <div className="flex gap-3 mt-2">
                {LANGS.map((lang) => (
                  <button
                    key={lang.code}
                    onClick={() => changeLang(lang.code)}
                    className="flex items-center gap-1.5 text-sm"
                    style={{ opacity: i18n.language === lang.code ? 1 : 0.5 }}
                  >
                    <img src={`https://flagcdn.com/w20/${lang.flag}.png`} width="20" height="15" alt={lang.label} />
                    {lang.label}
                  </button>
                ))}
              </div>
            </div>
          </div>
        )}
      </nav>

      {/* Mobile bottom nav */}
      <nav className="fixed bottom-0 left-0 right-0 z-40 lg:hidden border-t flex" style={{ backgroundColor: 'var(--bg)', borderColor: 'var(--border)' }}>
        <Link to="/" className="flex-1 flex flex-col items-center py-2.5 text-xs font-medium gap-0.5" style={{ color: 'var(--sub)' }}>
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
          Inicio
        </Link>
        <Link to="/horses" className="flex-1 flex flex-col items-center py-2.5 text-xs font-medium gap-0.5" style={{ color: 'var(--sub)' }}>
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
          Caballos
        </Link>
        <Link to="/contact" className="flex-1 flex flex-col items-center py-2.5 text-xs font-medium gap-0.5" style={{ color: 'var(--sub)' }}>
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
          Contacto
        </Link>
      </nav>
    </>
  );
}
