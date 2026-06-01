import { Link } from 'react-router-dom';

function LegalHero({ title }: { title: string }) {
  return (
    <section
      className="py-16"
      style={{ background: 'linear-gradient(135deg, var(--navy) 0%, var(--sky) 100%)' }}
    >
      <div className="max-w-4xl mx-auto px-4">
        <h1 className="text-4xl font-bold font-serif text-white">{title}</h1>
        <p className="text-white/70 mt-2 text-sm">gallophub.es · contact@gallophub.es</p>
      </div>
    </section>
  );
}

function LegalContent({ children }: { children: React.ReactNode }) {
  return (
    <div className="max-w-4xl mx-auto px-4 py-16 pb-28 lg:pb-16">
      <div
        className="bg-white rounded-lg border p-8 prose-sm max-w-none"
        style={{ borderColor: 'var(--border)', boxShadow: 'var(--shadow)', color: 'var(--text)' }}
      >
        {children}
      </div>
    </div>
  );
}

function Section({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div className="mb-8">
      <h2 className="text-xl font-serif font-semibold mb-3" style={{ color: 'var(--navy)' }}>{title}</h2>
      <div className="text-sm leading-relaxed space-y-2" style={{ color: 'var(--sub)' }}>{children}</div>
    </div>
  );
}

export function CgvPage() {
  return (
    <>
      <title>Conditions Générales de Vente — GallopHub</title>
      <LegalHero title="Conditions Générales de Vente" />
      <LegalContent>
        <Section title="1. Identification du vendeur">
          <p>GallopHub — gallophub.es</p>
          <p>Email : <a href="mailto:contact@gallophub.es" className="underline">contact@gallophub.es</a></p>
          <p>Pays : Espagne</p>
        </Section>
        <Section title="2. Objet">
          <p>Les présentes CGV régissent toutes les ventes de chevaux et services réalisées via le site gallophub.es.</p>
        </Section>
        <Section title="3. Produits">
          <p>Nous vendons des chevaux vivants ainsi que des services associés (transport, conseil). Chaque cheval est décrit avec ses caractéristiques principales (race, âge, sexe, discipline, prix). Les informations sont fournies de bonne foi et à titre indicatif.</p>
        </Section>
        <Section title="4. Prix">
          <p>Les prix sont indiqués en euros (€) TTC. GallopHub se réserve le droit de modifier ses prix à tout moment. Le prix applicable est celui affiché au moment de la prise de contact.</p>
        </Section>
        <Section title="5. Commande et confirmation">
          <p>La vente est réputée parfaite après :</p>
          <p>a) Échange de messages entre les parties,</p>
          <p>b) Signature d'un contrat de vente écrit,</p>
          <p>c) Versement d'un acompte de 20 à 30 % du prix total.</p>
          <p>Aucune vente n'est conclue par simple navigation sur le site.</p>
        </Section>
        <Section title="6. Modalités de paiement">
          <p>— Virement bancaire SEPA (préféré)</p>
          <p>— Carte bancaire via Stripe (acompte uniquement)</p>
          <p>Paiement intégral exigé avant expédition/livraison.</p>
        </Section>
        <Section title="7. Livraison et transport">
          <p>La livraison est organisée par GallopHub via des transporteurs équins professionnels homologués.</p>
          <p>Délais estimatifs :</p>
          <p>— Espagne : 1 à 3 jours ouvrés</p>
          <p>— Pays-Bas / Belgique : 5 à 10 jours ouvrés</p>
          <p>— Reste de l'Europe : 7 à 15 jours ouvrés</p>
          <p>Les délais sont indicatifs et peuvent varier. Les frais de transport sont à la charge de l'acheteur sauf accord contraire.</p>
        </Section>
        <Section title="8. Transfert de propriété et des risques">
          <p>Le transfert de propriété intervient dès le paiement intégral du prix. Le transfert des risques intervient au moment de la remise du cheval au transporteur.</p>
        </Section>
        <Section title="9. Droit de rétractation">
          <p>Conformément à la directive européenne sur les droits des consommateurs, le droit de rétractation de 14 jours NE S'APPLIQUE PAS à la vente d'animaux vivants (article L.221-28 du Code de la consommation — biens périssables ou susceptibles de se détériorer rapidement).</p>
          <p>Cependant, GallopHub s'engage à :</p>
          <p>— Permettre une visite physique et/ou des essais AVANT l'achat</p>
          <p>— Fournir une vidéo détaillée sur demande</p>
          <p>— Donner accès aux documents vétérinaires avant paiement</p>
        </Section>
        <Section title="10. Politique de retour">
          <p>Voir la page dédiée : <Link to="/politique-de-retour" className="underline" style={{ color: 'var(--navy)' }}>gallophub.es/politique-de-retour</Link></p>
        </Section>
        <Section title="11. Garanties légales">
          <p>Conformément au droit espagnol et européen, GallopHub garantit que les chevaux vendus correspondent à leur description. En cas de défaut caché significatif, l'acheteur dispose de 2 ans pour agir.</p>
        </Section>
        <Section title="12. Responsabilité">
          <p>GallopHub ne peut être tenu responsable des dommages résultant d'une mauvaise utilisation du cheval ou d'une incompatibilité cheval/cavalier non signalée lors de l'achat.</p>
        </Section>
        <Section title="13. Litiges">
          <p>En cas de litige, les parties s'engagent à rechercher une solution amiable avant tout recours judiciaire.</p>
          <p>Juridiction compétente : tribunaux espagnols (Espagne).</p>
          <p>Droit applicable : droit espagnol.</p>
        </Section>
        <Section title="14. Contact">
          <p>Pour toute question : <a href="mailto:contact@gallophub.es" className="underline">contact@gallophub.es</a></p>
        </Section>
      </LegalContent>
    </>
  );
}

export function ReturnPolicyPage() {
  return (
    <>
      <title>Politique de retour — GallopHub</title>
      <LegalHero title="Politique de retour et remboursement" />
      <LegalContent>
        <div className="mb-6 p-4 rounded-lg text-sm" style={{ backgroundColor: 'var(--muted)', color: 'var(--navy)' }}>
          <strong>Nature particulière des produits :</strong> GallopHub vend des animaux vivants (chevaux). Cette nature particulière implique des conditions spécifiques.
        </div>
        <Section title="1. Droit de rétractation">
          <p>La vente de chevaux vivants est exclue du droit de rétractation de 14 jours (animaux vivants = biens non retournables par nature).</p>
        </Section>
        <Section title="2. Retour en cas de vice caché">
          <p>Si le cheval présente un défaut caché non mentionné lors de la vente :</p>
          <p>— Délai de signalement : dans les 30 jours suivant la livraison</p>
          <p>— Procédure : contacter GallopHub par email (contact@gallophub.es) avec rapport vétérinaire à l'appui</p>
          <p>— Résolution : remplacement du cheval, avoir, ou remboursement partiel selon accord entre les parties</p>
          <p>— Remboursement maximum : prix d'achat du cheval hors transport</p>
        </Section>
        <Section title="3. Retour en cas de non-conformité">
          <p>Si le cheval livré ne correspond PAS à la description de l'annonce (race, âge, sexe incorrects) :</p>
          <p>— Délai de signalement : dans les 48 heures suivant la livraison</p>
          <p>— Procédure : email + photos/vidéos + rapport vétérinaire</p>
          <p>— Résolution : reprise du cheval + remboursement intégral (prix + frais de transport retour)</p>
        </Section>
        <Section title="4. Services">
          <p>— Réservation visite (50€) : remboursable si annulation 48h avant</p>
          <p>— Acompte (500€) : non remboursable si l'acheteur se rétracte sans motif légitime</p>
          <p>— Transport (800€) : remboursable si annulation 7 jours avant la date de transport prévue</p>
        </Section>
        <Section title="5. Procédure de réclamation">
          <p>1. Envoyer un email à contact@gallophub.es avec :</p>
          <p>   — Numéro de référence du cheval</p>
          <p>   — Description détaillée du problème</p>
          <p>   — Photos ou vidéos à l'appui</p>
          <p>   — Rapport vétérinaire (obligatoire pour vice caché)</p>
          <p>2. GallopHub répond sous 48 heures ouvrées</p>
          <p>3. Solution proposée dans les 7 jours ouvrés</p>
        </Section>
        <Section title="6. Remboursements">
          <p>Les remboursements validés sont effectués par virement bancaire sur le compte de l'acheteur dans un délai de 14 jours ouvrés.</p>
        </Section>
        <Section title="7. Contact réclamations">
          <p>Email : <a href="mailto:contact@gallophub.es" className="underline">contact@gallophub.es</a></p>
          <p>Objet email : "RÉCLAMATION — [référence cheval]"</p>
        </Section>
      </LegalContent>
    </>
  );
}

export function MentionsLegalesPage() {
  return (
    <>
      <title>Mentions légales — GallopHub</title>
      <LegalHero title="Mentions légales" />
      <LegalContent>
        <Section title="Éditeur du site">
          <p>GallopHub — gallophub.es</p>
          <p>Email : contact@gallophub.es</p>
          <p>Pays : Espagne</p>
        </Section>
        <Section title="Hébergement">
          <p>Le site est hébergé sur des serveurs sécurisés en Europe.</p>
        </Section>
        <Section title="Propriété intellectuelle">
          <p>Le contenu du site (textes, images, logos) est protégé par le droit d'auteur. Toute reproduction sans autorisation est interdite.</p>
        </Section>
        <Section title="Responsabilité">
          <p>GallopHub s'efforce de maintenir les informations du site à jour mais ne peut garantir l'exactitude de toutes les données. Les prix et disponibilités peuvent évoluer.</p>
        </Section>
        <Section title="Contact">
          <p>Pour toute question : <a href="mailto:contact@gallophub.es" className="underline">contact@gallophub.es</a></p>
        </Section>
      </LegalContent>
    </>
  );
}

export function PrivacyPage() {
  return (
    <>
      <title>Politique de confidentialité — GallopHub</title>
      <LegalHero title="Politique de confidentialité" />
      <LegalContent>
        <Section title="Responsable du traitement">
          <p>GallopHub — gallophub.es — contact@gallophub.es</p>
        </Section>
        <Section title="Données collectées">
          <p>Nous collectons les données suivantes :</p>
          <p>— Formulaire de contact : nom, email, téléphone, message</p>
          <p>— Cookies analytiques : Google Analytics 4 (anonymisé)</p>
          <p>— Cookies publicitaires : Google Ads, Facebook Pixel (avec consentement)</p>
        </Section>
        <Section title="Finalités">
          <p>— Répondre à vos demandes de contact</p>
          <p>— Analyser l'audience du site</p>
          <p>— Améliorer nos publicités (avec consentement)</p>
        </Section>
        <Section title="Base légale (RGPD)">
          <p>— Intérêt légitime (contact commercial)</p>
          <p>— Consentement (cookies publicitaires)</p>
        </Section>
        <Section title="Conservation des données">
          <p>Messages de contact : 3 ans. Données analytiques : 14 mois (GA4).</p>
        </Section>
        <Section title="Vos droits">
          <p>Conformément au RGPD, vous disposez du droit d'accès, de rectification, d'effacement et de portabilité de vos données.</p>
          <p>Pour exercer vos droits : <a href="mailto:contact@gallophub.es" className="underline">contact@gallophub.es</a></p>
        </Section>
        <Section title="Cookies">
          <p>Nous utilisons des cookies analytiques (GA4) et publicitaires (Google Ads, Facebook Pixel). Vous pouvez refuser les cookies non essentiels via la bannière de consentement.</p>
        </Section>
      </LegalContent>
    </>
  );
}
