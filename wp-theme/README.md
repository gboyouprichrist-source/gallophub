# GallopHub — Thème WordPress

Thème WordPress classique (PHP) pour gallophub.es — vente directe de chevaux.

## Installation

### 1. Copier le thème
```
wp-content/themes/gallophub/
```

### 2. Activer le thème
Apparence → Thèmes → GallopHub → Activer

### 3. Configuration post-activation
Aller dans **Réglages → GallopHub** :
- Numéro WhatsApp (sans +, ex: `34612345678`)
- Email de contact

### 4. Créer les pages WordPress
Créer les pages suivantes avec le bon **Template** :

| Slug de page         | Template WordPress   |
|----------------------|---------------------|
| `/`                  | *(page d'accueil statique — Lecture → Page statique)* |
| `/about/`            | À propos            |
| `/contact/`          | Contact             |
| `/faq/`              | FAQ                 |
| `/services/`         | Services            |
| `/cgv/`              | CGV                 |
| `/politique-de-retour/` | Politique de retour |
| `/mentions-legales/` | Mentions légales    |
| `/confidentialite/`  | Confidentialité     |

### 5. Définir la page d'accueil
Réglages → Lecture → Afficher en page d'accueil : **Page statique** → sélectionner la page vide créée pour `/`

### 6. Archive des chevaux
L'archive des chevaux est disponible à `/horses/` (CPT `horse`).  
Réglages → Permaliens → Enregistrer (pour flush les règles de réécriture).

### 7. Ajouter des chevaux
Articles → **Chevaux** → Ajouter.  
Remplir le formulaire "Détails du cheval" (métadonnées) et assigner les **Disciplines** (taxonomie).  
L'image à la une devient la photo principale.

Pour la galerie, uploader les images supplémentaires comme **médias attachés** au cheval
(Media Library → Upload → cocher "Attacher à ce billet").

### 8. Menu
Apparence → Menus → créer un menu "Menu principal" assigné à l'emplacement **Menu principal**.

---

## Structure du thème

```
gallophub/
├── style.css                   ← En-tête du thème WordPress
├── functions.php               ← Chargement, hooks, options admin
├── header.php                  ← Topbar + Navbar + ouverture <main>
├── footer.php                  ← Footer + bottom nav + WhatsApp float
├── index.php                   ← Fallback
├── page.php                    ← Page générique
├── front-page.php              ← Homepage (hero, stats, chevaux, CTA)
├── archive-horse.php           ← Liste chevaux + filtres AJAX
├── single-horse.php            ← Fiche cheval + galerie + Schema.org
├── page-about.php              ← À propos
├── page-contact.php            ← Contact (formulaire AJAX)
├── page-faq.php                ← FAQ (accordéon JS)
├── page-services.php           ← Services
├── page-cgv.php                ← CGV (Google Merchant Center)
├── page-politique-de-retour.php← Politique de retour (GMC)
├── page-mentions-legales.php   ← Mentions légales
├── page-confidentialite.php    ← RGPD
├── template-parts/
│   └── horse-card.php          ← Composant carte cheval (réutilisé partout)
├── inc/
│   ├── custom-post-types.php   ← CPT horse + taxonomie discipline
│   ├── meta-fields.php         ← Métaboxes admin + helpers PHP
│   ├── ajax.php                ← Filtres AJAX + soumission formulaire
│   └── schema.php              ← Schema.org Product + SEO meta helper
└── assets/
    ├── css/main.css            ← Design system complet (CSS variables)
    └── js/main.js              ← Navbar, FAQ, galerie, filtres AJAX
```

## Design system (CSS Variables)

```css
--navy:   #1A3C5E   /* Principal */
--gold:   #C8A951   /* Accent */
--sky:    #2E6DA4   /* Secondaire */
--bg:     #FAFAF8   /* Fond */
--muted:  #F3F0E8   /* Surface */
```

**Fonts** : Cormorant Garamond (titres) + DM Sans (corps) — Google Fonts

## Plugins recommandés

| Plugin              | Usage                              |
|---------------------|------------------------------------|
| Yoast SEO / RankMath | SEO avancé, sitemap XML           |
| WooCommerce         | Boutique (réservation, acompte, transport) |
| Polylang / WPML     | Multilangue ES / NL / FR           |
| WP Mail SMTP        | Fiabiliser l'envoi d'emails        |
| Smush / ShortPixel  | Optimisation images WebP           |
| Cookie Notice       | Bandeau RGPD / cookies             |

## Conformité Google Merchant Center

- Page CGV accessible : `/cgv/`
- Page Politique de retour : `/politique-de-retour/`
- Prix en € visible sur chaque fiche
- Statut de disponibilité (Disponible / Réservé / Vendu)
- Schema.org Product + shippingDetails + hasMerchantReturnPolicy sur chaque fiche
- Email de contact visible dans le footer
