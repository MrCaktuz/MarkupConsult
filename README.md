# Markup Consult – Thème WordPress v2

Design fidèle au projet NextJS original, avec :
- Hero centré (badge, titre 2 lignes, sous-titre, 2 boutons)
- Mouse tracker + pattern de points en fond
- Services en grille 2×2 avec coins rotatifs et icônes
- Page Parcours avec timeline, sidebar sticky, contact toujours visible
- Bouton PDF / impression visible à tout moment sur la page Parcours
- Mode sombre automatique (prefers-color-scheme)
- Print CSS : navigation cachée, tout le reste visible (contact + timeline)

## Installation

1. Copier `markup-consult-theme/` dans `wp-content/themes/`
2. Activer le thème dans *Apparence → Thèmes*
3. Créer les pages (voir ci-dessous)
4. Renseigner les options dans *Apparence → Options Markup Consult*
5. Ajouter les services, expériences et formations

## Pages à créer

### Page d'accueil
- *Pages → Ajouter* — Titre : `Accueil`
- *Réglages → Lecture → Page statique → Accueil*

### Page Parcours
- *Pages → Ajouter* — Titre : `Parcours` — Slug : `parcours`
- Template : **Parcours (CV)**

## Options du thème
*Apparence → Options Markup Consult*

| Champ | Valeur par défaut |
|-------|-------------------|
| Hero – Ligne 1 | Transformez vos idées en |
| Hero – Ligne 2 (colorée) | expériences digitales |
| Hero – Sous-titre | Développeur Front-End passionné… |
| Email | contact@markupconsult.com |
| Téléphone | +32 476 52 42 85 |
| Localisation | Liège – BE |
| GitHub | MrCaktuz |
| LinkedIn | mathieuclaessens |

## Services (CPT mc_service)
*Tableau de bord → Services → Ajouter*

- **Titre** : nom du service
- **Résumé** (excerpt) : description affichée sur la carte
- **Ordre d'affichage** : 1, 2, 3, 4 (les 4 premiers ont les coins rotatifs)
- **Couleur de fond icône** : laisser vide pour le défaut

Les icônes SVG par défaut sont assignées automatiquement selon la position.
Pour des icônes custom, utiliser le champ `_mc_icon_svg` via ACF ou wp_postmeta.

## Parcours (CPT mc_career)
*Tableau de bord → Parcours → Ajouter*

- **Titre** : ex. `Team Lead & Developer @ Synerglass`
- **Contenu** : description du poste
- **Type** : `career` (expérience) ou `education` (formation)
- **De** : année de début
- **À** : année de fin (laisser vide si poste actuel)
- **Tags** : `NextJS;ReactJS;SASS` (séparés par `;`)
- **Lien** : URL de l'entreprise
- **Ordre** : 1, 2, 3…

## Structure des fichiers

```
markup-consult-theme/
├── style.css
├── functions.php
├── header.php
├── footer.php
├── index.php
├── front-page.php          ← Hero + Services
├── page-parcours.php       ← CV / Parcours
├── template-parts/
│   └── contact-cards.php  ← Cards contact réutilisables
├── assets/
│   ├── css/main.css
│   ├── js/main.js
│   └── img/bg_pattern.png ← Pattern de points pour le fond
└── README.md
```
