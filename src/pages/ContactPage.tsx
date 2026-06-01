import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Mail, Phone, MapPin, Clock, MessageCircle, CheckCircle } from 'lucide-react';
import { submitContact } from '../lib/supabase';

const WA_NUMBER = import.meta.env.VITE_WHATSAPP_NUMBER || '34600000000';

const schema = z.object({
  name: z.string().min(2),
  email: z.string().email(),
  phone: z.string().optional(),
  subject: z.string().min(1, 'Choisissez un sujet'),
  message: z.string().min(10),
});
type FormData = z.infer<typeof schema>;

export function ContactPage() {
  const { t } = useTranslation();
  const [submitted, setSubmitted] = useState(false);
  const [error, setError] = useState('');

  const { register, handleSubmit, formState: { errors, isSubmitting } } = useForm<FormData>({
    resolver: zodResolver(schema),
  });

  const onSubmit = async (data: FormData) => {
    try {
      await submitContact(data);
      setSubmitted(true);
      if (typeof window !== 'undefined' && 'gtag' in window) {
        (window as unknown as Record<string, (...args: unknown[]) => void>).gtag('event', 'generate_lead');
      }
    } catch {
      setError(t('contact.error'));
    }
  };

  return (
    <>
      <title>Contactar — GallopHub | Venta directa de caballos</title>

      {/* Hero */}
      <section
        className="py-20"
        style={{ background: 'linear-gradient(135deg, var(--navy) 0%, var(--sky) 100%)' }}
      >
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h1 className="text-5xl font-bold font-serif text-white mb-4">Contactar</h1>
          <p className="text-white/80">Estamos aquí para ayudarte a encontrar el caballo perfecto</p>
        </div>
      </section>

      <section className="py-20 pb-28 lg:pb-20">
        <div className="max-w-7xl mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16">
            {/* Contact info */}
            <div>
              <h2 className="text-3xl font-serif font-semibold mb-8" style={{ color: 'var(--navy)' }}>
                Información de contacto
              </h2>
              <div className="space-y-6 mb-10">
                <div className="flex items-start gap-4">
                  <div className="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style={{ backgroundColor: 'var(--muted)' }}>
                    <Mail size={18} style={{ color: 'var(--navy)' }} />
                  </div>
                  <div>
                    <div className="text-sm font-semibold mb-1" style={{ color: 'var(--text)' }}>Email</div>
                    <a href="mailto:contact@gallophub.es" className="text-sm hover:underline" style={{ color: 'var(--sky)' }}>
                      contact@gallophub.es
                    </a>
                  </div>
                </div>
                <div className="flex items-start gap-4">
                  <div className="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style={{ backgroundColor: 'var(--muted)' }}>
                    <Phone size={18} style={{ color: 'var(--navy)' }} />
                  </div>
                  <div>
                    <div className="text-sm font-semibold mb-1" style={{ color: 'var(--text)' }}>Teléfono / WhatsApp</div>
                    <a
                      href={`https://wa.me/${WA_NUMBER}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-sm hover:underline"
                      style={{ color: 'var(--sky)' }}
                    >
                      +{WA_NUMBER}
                    </a>
                  </div>
                </div>
                <div className="flex items-start gap-4">
                  <div className="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style={{ backgroundColor: 'var(--muted)' }}>
                    <MapPin size={18} style={{ color: 'var(--navy)' }} />
                  </div>
                  <div>
                    <div className="text-sm font-semibold mb-1" style={{ color: 'var(--text)' }}>Ubicación</div>
                    <p className="text-sm" style={{ color: 'var(--sub)' }}>España</p>
                  </div>
                </div>
                <div className="flex items-start gap-4">
                  <div className="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style={{ backgroundColor: 'var(--muted)' }}>
                    <Clock size={18} style={{ color: 'var(--navy)' }} />
                  </div>
                  <div>
                    <div className="text-sm font-semibold mb-1" style={{ color: 'var(--text)' }}>Horarios</div>
                    <p className="text-sm" style={{ color: 'var(--sub)' }}>Lun–Vie: 9h–18h<br/>Sáb: 9h–14h</p>
                  </div>
                </div>
              </div>

              {/* WhatsApp CTA */}
              <a
                href={`https://wa.me/${WA_NUMBER}?text=${encodeURIComponent('Bonjour, je souhaite en savoir plus sur vos chevaux.')}`}
                target="_blank"
                rel="noopener noreferrer"
                className="flex items-center justify-center gap-3 w-full py-4 rounded-lg text-white font-semibold transition-opacity hover:opacity-90"
                style={{ backgroundColor: '#25D366' }}
              >
                <MessageCircle size={20} />
                Contacter via WhatsApp
              </a>
            </div>

            {/* Form */}
            <div>
              <div className="bg-white rounded-lg p-8 border" style={{ borderColor: 'var(--border)', boxShadow: 'var(--shadow-lg)' }}>
                <h2 className="text-2xl font-serif font-semibold mb-6" style={{ color: 'var(--navy)' }}>
                  Formulario de contacto
                </h2>

                {submitted ? (
                  <div className="text-center py-10">
                    <CheckCircle size={48} className="mx-auto mb-4" style={{ color: '#10B981' }} />
                    <p className="font-serif text-xl mb-2" style={{ color: 'var(--navy)' }}>Mensaje enviado</p>
                    <p className="text-sm" style={{ color: 'var(--sub)' }}>{t('contact.success')}</p>
                  </div>
                ) : (
                  <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                      <div>
                        <label className="text-xs font-medium block mb-1" style={{ color: 'var(--sub)' }}>{t('contact.name')} *</label>
                        <input
                          {...register('name')}
                          className="w-full text-sm border rounded-lg px-3 py-2.5"
                          style={{ borderColor: errors.name ? '#EF4444' : 'var(--border)' }}
                        />
                      </div>
                      <div>
                        <label className="text-xs font-medium block mb-1" style={{ color: 'var(--sub)' }}>{t('contact.email')} *</label>
                        <input
                          {...register('email')}
                          type="email"
                          className="w-full text-sm border rounded-lg px-3 py-2.5"
                          style={{ borderColor: errors.email ? '#EF4444' : 'var(--border)' }}
                        />
                      </div>
                    </div>
                    <div>
                      <label className="text-xs font-medium block mb-1" style={{ color: 'var(--sub)' }}>{t('contact.phone')}</label>
                      <input
                        {...register('phone')}
                        type="tel"
                        className="w-full text-sm border rounded-lg px-3 py-2.5"
                        style={{ borderColor: 'var(--border)' }}
                      />
                    </div>
                    <div>
                      <label className="text-xs font-medium block mb-1" style={{ color: 'var(--sub)' }}>{t('contact.subject')} *</label>
                      <select
                        {...register('subject')}
                        className="w-full text-sm border rounded-lg px-3 py-2.5 bg-white"
                        style={{ borderColor: errors.subject ? '#EF4444' : 'var(--border)' }}
                      >
                        <option value="">Seleccionar...</option>
                        <option value="purchase">Comprar un caballo</option>
                        <option value="info">Información general</option>
                        <option value="transport">Transporte</option>
                        <option value="visit">Organizar una visita</option>
                        <option value="other">Otro</option>
                      </select>
                    </div>
                    <div>
                      <label className="text-xs font-medium block mb-1" style={{ color: 'var(--sub)' }}>{t('contact.message')} *</label>
                      <textarea
                        {...register('message')}
                        rows={5}
                        className="w-full text-sm border rounded-lg px-3 py-2.5 resize-none"
                        style={{ borderColor: errors.message ? '#EF4444' : 'var(--border)' }}
                      />
                    </div>
                    {error && <p className="text-xs" style={{ color: '#EF4444' }}>{error}</p>}
                    <button
                      type="submit"
                      disabled={isSubmitting}
                      className="w-full py-3 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 disabled:opacity-60"
                      style={{ backgroundColor: 'var(--navy)' }}
                    >
                      {isSubmitting ? 'Enviando...' : t('contact.send')}
                    </button>
                  </form>
                )}
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
