import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { Logo } from '../ui/Logo';
import { Mail, MapPin, Phone } from 'lucide-react';

export function Footer() {
  const { t } = useTranslation();

  return (
    <footer style={{ backgroundColor: 'var(--navy)', color: 'white' }} className="pb-20 lg:pb-0">
      <div className="max-w-7xl mx-auto px-4 py-16">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-10">
          {/* Brand */}
          <div className="md:col-span-1">
            <Logo variant="dark" size="md" />
            <p className="mt-3 text-sm text-white/70 leading-relaxed">
              {t('footer.tagline')}
            </p>
            <div className="mt-4 space-y-2">
              <a href="mailto:contact@gallophub.es" className="flex items-center gap-2 text-sm text-white/70 hover:text-white transition-colors">
                <Mail size={14} />
                contact@gallophub.es
              </a>
              <div className="flex items-center gap-2 text-sm text-white/70">
                <MapPin size={14} />
                España
              </div>
              <div className="flex items-center gap-2 text-sm text-white/70">
                <Phone size={14} />
                <a href={`https://wa.me/${import.meta.env.VITE_WHATSAPP_NUMBER || '34600000000'}`} target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors">
                  WhatsApp
                </a>
              </div>
            </div>
          </div>

          {/* Navigation */}
          <div>
            <h4 className="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Caballos</h4>
            <ul className="space-y-2">
              <li><Link to="/horses" className="text-sm text-white/70 hover:text-white transition-colors">{t('nav.horses')}</Link></li>
              <li><Link to="/horses?discipline=dressage" className="text-sm text-white/70 hover:text-white transition-colors">{t('nav.dressage')}</Link></li>
              <li><Link to="/horses?discipline=jumping" className="text-sm text-white/70 hover:text-white transition-colors">{t('nav.jumping')}</Link></li>
              <li><Link to="/horses?discipline=western" className="text-sm text-white/70 hover:text-white transition-colors">{t('nav.western')}</Link></li>
              <li><Link to="/horses?discipline=leisure" className="text-sm text-white/70 hover:text-white transition-colors">{t('nav.leisure')}</Link></li>
            </ul>
          </div>

          {/* Services */}
          <div>
            <h4 className="text-white font-semibold text-sm mb-4 uppercase tracking-wider">GallopHub</h4>
            <ul className="space-y-2">
              <li><Link to="/about" className="text-sm text-white/70 hover:text-white transition-colors">{t('nav.about')}</Link></li>
              <li><Link to="/services" className="text-sm text-white/70 hover:text-white transition-colors">{t('nav.services')}</Link></li>
              <li><Link to="/faq" className="text-sm text-white/70 hover:text-white transition-colors">FAQ</Link></li>
              <li><Link to="/shop" className="text-sm text-white/70 hover:text-white transition-colors">{t('nav.shop')}</Link></li>
              <li><Link to="/contact" className="text-sm text-white/70 hover:text-white transition-colors">{t('nav.contact')}</Link></li>
            </ul>
          </div>

          {/* Legal */}
          <div>
            <h4 className="text-white font-semibold text-sm mb-4 uppercase tracking-wider">{t('footer.legal')}</h4>
            <ul className="space-y-2">
              <li><Link to="/cgv" className="text-sm text-white/70 hover:text-white transition-colors">{t('footer.cgv')}</Link></li>
              <li><Link to="/politique-de-retour" className="text-sm text-white/70 hover:text-white transition-colors">{t('footer.returns')}</Link></li>
              <li><Link to="/mentions-legales" className="text-sm text-white/70 hover:text-white transition-colors">{t('footer.mentions')}</Link></li>
              <li><Link to="/confidentialite" className="text-sm text-white/70 hover:text-white transition-colors">{t('footer.privacy')}</Link></li>
            </ul>
          </div>
        </div>

        <div className="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-white/50">
          <p>© {new Date().getFullYear()} GallopHub · gallophub.es · España</p>
          <p>Vendeur professionnel · Vente directe de chevaux</p>
        </div>
      </div>
    </footer>
  );
}
