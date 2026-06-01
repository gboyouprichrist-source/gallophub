import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import '../src/i18n';

import { Navbar } from './components/layout/Navbar';
import { Footer } from './components/layout/Footer';
import { WhatsAppFloat } from './components/ui/WhatsAppFloat';

import { HomePage } from './pages/HomePage';
import { HorsesPage } from './pages/HorsesPage';
import { HorseDetailPage } from './pages/HorseDetailPage';
import { AboutPage } from './pages/AboutPage';
import { ContactPage } from './pages/ContactPage';
import { FaqPage } from './pages/FaqPage';
import { ServicesPage } from './pages/ServicesPage';
import { ShopPage } from './pages/ShopPage';
import { CgvPage, ReturnPolicyPage, MentionsLegalesPage, PrivacyPage } from './pages/LegalPages';

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 5 * 60 * 1000,
      retry: 1,
    },
  },
});

export default function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <BrowserRouter>
        <div className="min-h-screen flex flex-col" style={{ backgroundColor: 'var(--bg)' }}>
          <Navbar />
          <main className="flex-1">
            <Routes>
              <Route path="/" element={<HomePage />} />
              <Route path="/horses" element={<HorsesPage />} />
              <Route path="/horses/:id" element={<HorseDetailPage />} />
              <Route path="/about" element={<AboutPage />} />
              <Route path="/contact" element={<ContactPage />} />
              <Route path="/faq" element={<FaqPage />} />
              <Route path="/services" element={<ServicesPage />} />
              <Route path="/shop" element={<ShopPage />} />
              <Route path="/cgv" element={<CgvPage />} />
              <Route path="/politique-de-retour" element={<ReturnPolicyPage />} />
              <Route path="/mentions-legales" element={<MentionsLegalesPage />} />
              <Route path="/confidentialite" element={<PrivacyPage />} />
            </Routes>
          </main>
          <Footer />
          <WhatsAppFloat />
        </div>
      </BrowserRouter>
    </QueryClientProvider>
  );
}
